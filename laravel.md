# "php artisan ui [vue/react/bootstrap] --auth" を開発途中からやると、、、

ログイン機能を簡単に実装できるコマンド。

**結論からいうと、上のコマンドは開発の最初に行うべき。**

今回確認できただけで、

- webpack.mix.js
- resource/js/ExampleComponent
- resource/js/app.js

に変更が加えられていた。

# " composer dump-autoload"

スクラッチ開発なら、" require ~~" みたいな記述で、他クラスやファイルを利用できるが、

laravel は " composer dump-autoload "

# S3 view ファイルをアップロード( sls で deploy)

AWS コンソールで、IAM と S3 bucket を作成した後、

## 1. .env を編集

```
ASSET_URL=https://[S3 のバケット名].s3-ap-northeast-1.amazonaws.com


AWS_ACCESS_KEY_ID=XXXXXXXXXXXXX
AWS_SECRET_ACCESS_KEY=XXXXXXXXXXXXX
AWS_DEFAULT_REGION=ap-northeast-1
AWS_BUCKET= S3 のバケット名
```

## 2. Serverless Framework にプラグインインストール

```
$ sls plugin install -n serverless-s3-sync
```

## 3. serverless.yml への記述

```yml
plugins:
   - ./vendor/bref/bref
   - serverless-domain-manager
+  - serverless-s3-sync

 custom:
+  s3Sync:
+    # A simple configuration for copying static assets
+    - bucketName: ${self:service.name}-${opt:stage, self:provider.stage}-asset
+      localDir: public # required

+resources:
+  Resources:
+    StaticContentS3:
+      Type: AWS::S3::Bucket
+      Properties:
+        BucketName: ${self:service.name}-${opt:stage, self:provider.stage}-asset
```

## 4. s3 と laravel を連携させるパッケージをインストール

```
$ composer require league/flysystem-aws-s3-v3
```

# S3 に アップロードした view ファイルの URL を一時的なものにする。

## 1. .env の "AWS_ACCESS_KEY=" などの AWS 関連の部分を複製する。そして、S3 のバケットのポリシーを持つキーを入力する。

```.env
AWS_ACCESS_KEY_ID=
AWS_SECRET_ACCESS_KEY=
AWS_DEFAULT_REGION=us-east-1
AWS_BUCKET=

AWS_ACCESS_KEY_ID_S3=XXXXXXXXXXXX
AWS_SECRET_ACCESS_KEY_S3=XXXXXXXXXXXX
AWS_DEFAULT_REGION_S3=XXXXXXXXXXXX
AWS_BUCKET_S3=XXXXXXXXXXXX



# 理由としては、serverless framework を利用したときの、lambda の 環境変数がすでにセットされているため、それを使って S3 にアクセスしようとするため、Access Denied のエラーが出るから。

```

- 補足

.env

```
SESSION_DRIVER=cookie
```

の方がいいかもしれない。（理由はわかりません）

## 2. config/filesystems.php に以下のように記述

```php
disks => {
  ~省略~
  's3'=> [
    'driver' => 's3',
    'key' => env('AWS_ACCESS_KEY_ID_S3'),
    'secret' => env('AWS_SECRET_ACCESS_KEY_S3'),
    'region' => env('AWS_DEFAULT_REGION_S3'),
    'bucket' => env('AWS_BUCKET_S3'),
    'url' => env('AWS_URL_S3'),
    'endpoint' => env('AWS_ENDPOINT_S3'),
  ]
  ~省略~
}
```

## 3. app/helpers.php 作成・編集

```php
<?php
if (!function_exists('s3_asset')) {
    function s3_asset($path)
    {
        return \Illuminate\Support\Facades\Storage::disk('s3')->temporaryUrl($path, now()->addMinute());
    }
}
```

## 4. composer.json に追記

```json
"autoload": {
    "psr-4": {
        "App\\": "app/"
    },
    "classmap": [
        "database/seeds",
        "database/factories"
    ],
+    "files" : [        #<= ここから追記
+       "app/helpers.php"
+    ]
},
```

## 5. composer dump-autoload 実行。これで、helpers.php の s3_asset 関数が使えるようになりました。

## 6. resources/views/layouts/app.blade.php に 関数の記述

```php
@if(env('APP_ENV') == 'local')
    <link href=" {{ mix('css/app.css') }} " rel="stylesheet"></link>
@else
    <link href=" {{ s3_asset('css/app.css') }} " rel="stylesheet"></link>
@endif

# 条件分岐で、localは違うところからassetをとってきています。

```

7. 以下コマンドを実行

```
$ npm run dev
$ php artisan config:clear
$ sls deploy -v
```

# array_filter

array_filter(配列, 関数)

## Ex.1

```php
$nums = ['a' => 100, 'b' => 200, 'c' => 300, 'd' => 400, 'e' => 500]

$result = array_filter($nums, function($num){
    return $num > 300;
});

#=> [ 'd' => 400, 'e' => 500 ]

```

## Ex.2 key を 使いたい場合

```php
$nums = ['a' => 100, 'b' => 200, 'c' => 300, 'd' => 400, 'e' => 500]

$result = array_filter(
    $nums,
    function($num){  #<== $num が key として扱われる
        return $num == 'd';
    },
    ARRAY_FILTER_USE_KEY
);

#=> [ 'd' => 400 ]

```

in_array, array_search というのもあるらしい。

## js と php の配列・オブジェクトの解釈の違いから起こりうるエラー

```php
$array = [
    0 => ['name' => 'Kate', 'age' => 19],
    1 => ['name' => 'Soma', 'age' => 23],
    2 => ['name' => 'Tailor', 'age' => 17],
]

$new_array = array_filter($array, function($member){
    return $member->age < 20;
})

// $new_array = [
//     0 => ['name' => 'Kate', 'age' => 19],
//     2 => ['name' => 'Tailor', 'age' => 17]
// ]
//　php ではこれは配列として解釈される。
```

仮にこのような配列をフロント側(vue.js など)に返す場合は注意が必要
配列を返したつもりでも、オブジェクトとして解釈されてしまう。

```php
$new_array = array_values(
    array_filter($array, function($member){
        return $member->age < 20;
    })
)

// $new_array = [
//     0 => ['name' => 'Kate', 'age' => 19],
//     1 => ['name' => 'Tailor', 'age' => 17]
// ]
// インデックス値が振り直されるので、こうすれば配列のままjs側に渡せる
```

# Str::random()

laravel6.0 系から、文字列や配列のヘルパはデフォルトから削除されたことにより、str_random() が使えなくな離ました。

```php
use Illuminate\Support\Str;

Str::random(10)  // ランダムで10文字の文字列が生成されます
```

他の、str* や array* ヘルパーメソッドも削除されたようなので、注意が必要。

# get()

all(), toArray(), find() などと比較されることが多い。僕自身もしっかり理解や区別はできていない。
今回現場でわかったことを列挙していく。

- Collection 型の戻り値。( find() は モデルのオブジェクト型)
- 第１引数で、配列の中のキー、第２引数で、そのキーが存在しなかったときのデフォルト値を設定できる

これがわかっていると、シーンによっては、条件分岐の記述を書かなくて済む。

```php
$password = $request->all()['password'] ? $request->all()['password'] : 'dummy';
```

これを、get() を使えば、

```
$password = $request->get('password', 'dummy');
```

# route::redirect

routing の ファイルで、

```php
Route::redirect('here', 'there');
```

とすればリダイレクトが簡単にできる。

```php
Route::redirect('here', 'there', statu_code);
// 第３引数でステータスコードを設定できる。デフォルトは 302
```

# explode

文字列を、指定した delimiter で分割し、その分割した文字列を含む collection 型で返す。
js や ruby のときのように、毎度 split を使わずに済むようになった。

```php
$message = 'Hello New World';

explode(' ', $message);

#=> ['Hello', 'New', 'World']

explode(' ', $message, 2); #<=最大要素数を2に指定

#=> ['Hello', 'New World']
```

# ログレベル

参考記事 https://reffect.co.jp/laravel/laravel-logging-setting#i

重要度によって、

**emergency, alert, critical, error, warning, notice, info, debug**

に分けられる

```php
use Illuminate\Support\Facades\Log

Log::emergency($message);
Log::alert($message);
Log::critical($message);
Log::error($message);
```

という風にログを出力できる。

# slack にエラーメッセージを送る

## 1. slack app

設定と管理 -> アプリの管理する -> Incoming Webhook -> slack app に追加

そして、チャンネルを選んだり、設定をしていきます。

最後に、https://hooks.slack.com/services/ から始まる url をコピーしておく

## 2. .env

```

LOG_CHANNEL=stack
LOG_SLACK_WEBHOOK_URL=https://hooks.slack.com/services/XXXXXXXXXXXXXXXX/XXXXXXXXXXXXXXXX
# 先ほどコピーした URL を貼り付ける

```

## 3. logging.php

```php
'channels' => [
        'stack' => [
            'driver' => 'stack',
            'channels' => ['stderr', 'slack'],  //'slack' を追記
            'ignore_exceptions' => false,
        ],

        〜省略〜

        'slack' => [
            'driver' => 'slack',
            'url' => env('LOG_SLACK_WEBHOOK_URL'),
            'username' => 'Laravel Log',
            'emoji' => ':boom:',
            'level' => 'error',  //通知を表示のエラーレベルを下げるため、'critical' から 'error' に変更
        ],
```

これで、エラーが出たときに slack に通知がいく

# larave x sls framework x ELB で local 環境ではログインできて、 staging 環境ではできない謎エラー

参考記事 https://qiita.com/hisash/items/4b3bb1ea47c38b0d8c86

結論からいうと、下記で解決。

app/Http/Middleware/TrustProxies.php

```php
<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Fideloper\Proxy\TrustProxies as Middleware;

class TrustProxies extends Middleware
{
    /**
     * The trusted proxies for this application.
     *
     * @var array
     */
    protected $proxies = '*'; //ここを * もしくは ** にする。
```

最初はいまいち理解しがたかったが、

**リクエストの取得の信用するプロキシの設定**

https://readouble.com/laravel/7.x/ja/requests.html

**Laravel で AWS の LB を通した環境でエンドユーザの IP を取得したい**

https://qiita.com/yamotuki/items/a51e7e6e7edb243b6150

を読んで理解ができ始めた。

要は、クライアントとアプリの間に、ロードバランサーが入っている構造だと、

**クライアント - ロードバランサー間は、HTTPS 通信**してくれるが、

**ロードバランサー - アプリ間は、HTTP 通信**になってしまうので、

そこで laravel の auth が 安全ではない通信だと判断して、ログインしようとしても、通信が途中で終わってしまっていた。

# microtime()

現在の時間をマイクロ秒まで返してくれる関数

```php

$before = microtime(true);

//ここに処理を記述

$after = microtime(true);

$time = $after - $before;

echo $time;
```

これで処理時間を測ることもできる

# middleware

参考記事 https://www.ritolab.com/entry/69

HTTP リクエストに対してその入口と出口で動作するメカニズム。

入口は、routing と controller の間で処理される。
入口で動作する処理の具体例は、認証やバリデーションなど。

出口は、View を通った後、つまり Blade テンプレートへのバインドも済んだ後での処理。
出口の処理の具体例は、あまり想像しにくい。

## 1. middlewre クラスの生成

```
$ php artisan make:middleware <Middleware名>
```

## 2. 初期のソース

```php
<?php

namespace App\Http\Middleware;

use Closure;

class HtmlOperation
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        return $next($request);
    }
```

## 3. 入口での処理の書き方

```php
public function handle($request, Closure $next)
{
  /*
   * ここに処理を実装する
   */

  return $next($request);
}
```

## 4. 出口での処理の書き方

```php
public function handle($request, Closure $next)
{
  $response = $next($request); //ここで何の処理もせず、入口の処理が終了する

  /*
   * ここに処理を実装する
   */

  return $response; //上記の処理が実行されて、出口の処理終了
}
```

# SESSION_DRIVER

参考記事 https://readouble.com/laravel/7.x/ja/session.html

.env ファイルにこんな記載がある。

```
SESSION_DRIVER=
```

セッション管理をどこでするか決められる環境変数

file: storage/framework/sessions に保存される。

array: PHP の配列として保存されるだけで、リクエスト間で継続しません。

cookie: 暗号化され安全なクッキーに保存されます。しかし、cookie に保存されすぎると、'Bad Request' の chrome のエラーが出ることがある。

database: リレーショナルデータベースへ保存されます。

memcached/redis: スピードの早いキャッシュベースの保存域に保存されます。

# \$hidden

データ取得しないフィールドを指定する。外には見せたくない情報があるときに使う。(password 等)

Model に記述

```php
protected $hidden = [
  'password', 'profit', 'fullname'
];
```

# json_encode(), json_decode()

参考記事

https://qiita.com/shosho/items/34d0e9cc68c376a0a972

## json_encode() : JSON 化

```php
$array = [];
$json = json_encode($array,JSON_PRETTY_PRINT);
// JSON_PRETTY_PRINT にすると、読みやすい形になる。
```

## json_decode() : 配列化

```php
$members = Member::all();

$json = json_decode($members, true);
// 第二引数 true/false は array/object
dd($json);
```

# whereNotNull, whereNull

```php
users::whereNotNull('age')->get();
// age が null ではないレコードを取ってくる。
users::whereNull('age')->get();
// age が null のレコードを取ってくる。
```

# toArray と all()

参考記事

https://qiita.com/ucan-lab/items/47638a7b52090f59c2bf

# array_map

参考記事

https://www.sejuku.net/blog/22549

```php
$nums = [200, 300, 400, 500];
$ten = 10;

$array = array_map(function($num) use ($ten) {
    return $num * $ten;
}, $nums);

// use は使わなくても良い。
// $array = [2000, 3000, 4000, 5000];
```

## array_map で index を使いたい時

参考記事

https://qiita.com/kazuhei/items/87acf094d82cc58afe72

第三引数に、range メソッドを使う

```php
array_map(function($array, $index){
    return $array;
}, $arrays, range(0, count($arrays)));
```

# array_column

参考記事

https://qiita.com/jacksuzuki/items/eae943735bda747be09c

pluck なども利用して、配列を自由に作り変えられる。

```php
array array_column ( array $input , mixed $column_key [, mixed $index_key = null ] )

// 第二引数と第三引数は、array のインデックス番号でも指定できる

$users = [
    [
    "id" => 1,
    "name" => "soma",
    "age" => 23,
    ],
    [
    "id" => 2,
    "name" => "kate",
    "age" => 21,
    ],
    [
    "id" => 3,
    "name" => "Tyle",
    "age" => 20,
    ]
]

$array = array_column($users, 2, 1);
// $array = [
//     [
//         "soma" => 23,
//     ],
//     [
//         "kate" => 21,
//     ],
//     [
//         "Tyle" => 20
//     ],
// ]
```

# array_merge

```php
$array1 = ["a", "b", "c"];
$array2 = ["d", "e", "f"];

array3 = array_merge(array1, array2);
array4 = array_merge(array2, array1);

// array3 = ["a", "b", "c", "d", "e", "f"]
// array3 = ["d", "e", "f", "a", "b", "c",]
```

# Guzzle

Http リクエストを投げられるライブラリ

参考記事

https://qiita.com/yousan/items/2a4d9eac82c77be8ba8b

```php
$client = new \GuzzleHttp\Client();
try {
    $res = $client->post("[URL]", params);
} catch (\GuzzleHttp\Exception\ClientException $e) {
    $response = $e->getResponse();
    return $responseBodyAsString = $response->getBody()->getContents();
}
```

## basic 認証の値を引数にいれる

```php
$client = new \GuzzleHttp\Client();
$res = $client->get('[URL]', ['auth' => ['[user]', '[password]'])
```

# is_null

参考記事

isset, empty, is_null の違い

https://qiita.com/hirossyi73/items/6e6b9b3ff155a8b05075

```php
$var = "";
is_null($var);
// false

$var = null;
is_null($var);
// true
```

# auth()

```php
auth()->user();
// ログインしているユーザーの情報取得
```

# json の返し方

参考記事

https://storehouse-techhack.com/laravel-response/

```php
public function index(){
    $array = [
        "first_name" => "soma",
        "last_name" => "sekimoto",
    ];

    return response()->json($array);
}
```

# Guzzle の例外時のレスポンス拾う

```php
try {
    $client = new \GuzzleHttp\Client();
    $response = $client->get('URL');
} catch
    (\GuzzleHttp\Exception\ClientException $e) {
        $responseBodyAsString = $e->getResponse()->getBody()->getContents();
        return json_decode($responseBodyAsString, true);
}
```

例外にレスポンスをとりたいときは、getResponse や getRequest を利用してからメソッドチェーンで繋ぐ。

# フラッシュメッセージを表示

参考記事

https://qiita.com/usaginooheso/items/6a99e565f16de2f9ddf7

Controller などで、

```php
public function index(){
    \Session::flash('message', 'この項目は必須です。')
}
```

これで session に'message'という名前で保存される。

view

index.blade.php

```php
@if (session('message'))
<div class='alert alert-primary'>
    {{session('message')}}
</div>
@endif
```

# array_key_exists

参考記事

https://www.softel.co.jp/blogs/tech/archives/5878

https://hacknote.jp/archives/47307/

```php
$var = null;

isset($var); // false
array_key_exists($var); //true
```

array_key_exists と isset では null のときの結果が違うのと、処理時間もかなり変わることがあるらしい。(array_key_exists の方が遅い)

# 例外をログする report()

```php
try {
    //省略
}
catch(\Exception $e){
    report($e)
}
```

# json_encode()で定義済み定数を利用する

```php

$array = [
    "name" => "soma",
    "age" => 23,
];

json_encode($array, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT)

```

- JSON_PRETTY_PRINT : JSON を見やすい形に整形する。
- JSON_UNESCAPED_UNICODE : 日本語などのマルチバイトの文字も正しく表示させる。

# boot() で DB 接続時に共通イベントを走らせる

laravel で DB 保存時とレコード生成時に updated_by と created_by を挿入する。
(DB にはこの２つのカラムはすでに用意してある。)

Model

```php

public static function boot()
    {
        parent::boot();
        static::saving(function (Model $model) { // controller で save() が走った時、DB に保存する直前にこのfunctionが実行される。
            $model->updated_by = auth()->id(); // ログインしているユーザーのID をいれる。
        });
        static::creating(function (Model $model) { // レコード生成直前に実行
            $model->created_by = auth()->id();
        });

    }
```

# さまざまな where 句

参考記事
https://public-constructor.com/laravel-query-builder-where-clauses/

```php
->whereYear()
->whereMonth()
->whereDate()
->whereDay()
->whereTime()
->whereBetween() //あるカラムが持つ値に対して範囲検索をする場合に使用
->whereColumn() //指定した2つのカラムを比較
```

## orWhere

```php
->where('user_id', 1)->orWhere('optional_column', request()->get('optional_column', 'デフォルト'))
```

## where 句の中に function をいれる

```php
->where(function($q){
    $name = User::find(1)->name
    $q->where('name', $name)
});
```

## JSON 型の取り方

```php
->where('json->name', 'Soma')
```

# Carbon

参考記事

https://qiita.com/mackeyTA/items/e8b5e47a9f020a1902c0

```php
new Carbon('today');
new Carbon('2020-04-21'); // 2020/4/21
new Carbon('first day of next month'); // 来月の最初の日
$now = Carbon::now();
$now->format('Y年m月d日') //2020年7月16日
$now->hour //13
```

# is_string()

指定した変数が文字列かどうかを確認するサンプルコード

```php
$var1 = "1"
$var2 = 1
is_string($var1)
// true
is_string($var2)
// false
```

# request()

リクエストを取得できる。値を取得。

参考記事

https://readouble.com/laravel/6.x/ja/helpers.html

```php
$value = request('key', 'default')
// key の value がない時は、デフォルトをセットできる
```

# 辞書型の配列で、value に function をセットできる

```php
$array = [
    'age' => 23,
    'name' => function($user){
        return $user->last_name . $user->first_name;
    },
    //以下省略
];
```

# substr()

文字列から一部の文字列を抽出する。

```php
// substr([文字列], [開始位置], [個数])
substr("abcdef", -3, 1); // "d" を返す
substr("abcdef", -3, -1); // "de" を返す
```

# validation

参考記事

https://readouble.com/laravel/7.x/ja/validation.html

各リクエストのによって、laravel のバリデーションは少し違う働きをする。

- HTTP リクエスト
  自動で session にエラーメッセージが保存される。なので、blade で \$errors 変数が使える。

- AJAX リクエスト
  バリデーションエラーを全部含んだ JSON レスポンスを返す。(422 HTTP ステータスコードで)
- 複雑なバリデーションは、make:request コマンドでクラスを作成して、そこに記述する
- validate()メソッドでバリデーションを行う
- Validator ファサードを利用して、make メソッドでインスタンスを生成して、そこにルールを書くこともできる

```php
$request->validate([ルール])

public function post(){
    $validator = Validator::make([データ], [ルール]);
    // 第１引数は、バリデーションを行うデータです。第２引数はそのデータに適用するバリデーションルール。
    $validator->validate();
    // 自動リダイレクト機能を使いたい時は validate() でバリデーションする
}
```

## required_if, required_unless

```php
$v = Validator::make(
    $array,
    [
        'last_name' => 'required',
        'first_name' => 'nullable',
        'weight' => 'nullable|required_if:last_name,hoge',
        'height' => 'nullable|required_unless:first_name,fuga',
    ]
)

```

# is_int と is_numeric

is_int も is_numeric も整数かどうかを判断するメソッドだが、

- is_numeric は フォームなどに入力された数値文字列にも true を返す。

- is_int は数値文字列には false を返す。

# gettype

変数の型を取得

参考記事
https://www.php.net/manual/ja/function.gettype.php

# Model 内で使える

参考記事

https://qiita.com/tmf16/items/e9169b416ab4011a3a09

```php
getDirty() // 引数に入れたキーが変更されていればその値を返す
isDirty() // 値が変更されたかどうかを返す
getOriginal() // 変更される前の値を返す
```

## isDirty と wasChanged の違い

https://www.larajapan.com/2019/07/05/isdirty-vs-waschanged/

isDirty: DB保存後でも変化を判断不可能
wasChanged: DB保存後でも変化を判断可能

# list

一連の変数に値を代入できる。

```php
$array = ['hoge', 'fuga', 'hage']
list($a, $b, $c) = $array;
// $a = 'hoge', $b = 'fuga', $c = 'hage'
```

# fgets

ファイルポインタから 1 行取得

参考記事

https://www.php.net/manual/ja/function.fgets.php

```php
fgets($handle, [$length])
// $handle には、fopen などで開いたファイルのファイルポインタを指定。
// $length を指定する場合は、バイトで書く

<?php
$input = fgets(STDIN)
echo $input;
// 標準入力を取得する場合はこれでも良い。
?>
```

# trim

文字列の先頭と末尾にある空白を取り除く。

```php
$input = trim(fgets(STDIN));
```

# leftJoin

sql でいう left join

参考記事

https://readouble.com/laravel/7.x/ja/queries.html

```php
\App\Users::leftJoin('posts', 'users.id', '=', 'posts.user_id')
```

## leftjoin した時に同じ名前のカラムの値が上書きされる。

参考記事

https://qiita.com/pipiox/items/395bef543e16c93a1fa6

```php
\App\Users::leftJoin('posts', 'users.id', '=', 'posts.user_id')
    ->select(['users.name', 'posts.title'])
    ->get()

//array(
//    "name" => "Soma",
//    "title" => "タイトル"
//)
```

# strtotime

date 型 の値の操作時に使う。

参考記事

https://pentan.info/php/strtotime_month.html

https://www.flatflag.nir87.com/modify-495#i-5

先月の日付とる場合は注意が必要

```php
date('Y-m-d', strtotime('-1 month'));
date('Y-m-d', strtotime('2016-3-31 -1 month')); // 2016-03-02
date('Y-m-d', strtotime('last day of previous month', $date));　//2016-02-29
```

# preg_match

preg_match([正規表現], [文字列], [マッチした文字列を格納する変数名])

```php
$text = 'Done is better than perfect';
$regex = pre_match('/better/', $text, $better);
// $regex = 1; match する場合は trueを返す。
// $better = ["better"]
// 配列で格納される。
```

# \Illuminate\Support\Facades\DB

database.php

```php
'connections' => [
    'mysql' => [
                'driver' => 'mysql',
                'url' => env('DATABASE_URL'),
                'host' => env('DB_HOST', '127.0.0.1'),
                'port' => env('DB_PORT', '3306'),
                'database' => env('DB_DATABASE', 'laravel'),
                'username' => env('DB_USERNAME', 'laravel'),
                'password' => env('DB_PASSWORD', ''),
                'unix_socket' => env('DB_SOCKET', ''),
                'charset' => 'utf8mb4',
                'collation' => 'utf8mb4_unicode_ci',
            ],
]
```

controller

```php
$user = \Illuminate\Support\Facades\DB::connection('mysql')->table('users')->where('user_id', 23)->first();
```

# $fillable(ホワイトリスト方式) と $guarded(ブラックリスト方式)

参考記事

https://qiita.com/toro_ponz/items/b33c757cb7ba5bb48ed4

```php
protected $fillable = [
	'column_name'
];
// 操作可能なカラムを指定する。(指定したカラム以外は操作を拒否される)

protected $guarded = [
	'column_name'
];
//操作拒否したいカラムを指定する。
```

# \$keyType

Eloquent は主キーをデフォルト状態で自動的に int へキャストされるので、\$keyType を設定して、string 型にする必要がある。

参考記事

https://stackoverflow.com/questions/34582535/laravel-5-2-use-a-string-as-a-custom-primary-key-for-eloquent-table-becomes-0

https://readouble.com/laravel/5.5/ja/eloquent.html

```php
protected $primaryKey = 'your_key_name';

protected $keyType = 'string';
```

# laravel-mix

https://laravel-mix.com/docs/6.0/upgrade#update-your-npm-scripts

## laravel8(laravel-mix 6)からはnpm scriptsの書き方が変わった

package.json

BEFORE

```js

"scripts": {
    "development": "cross-env NODE_ENV=development node_modules/webpack/bin/webpack.js --progress --hide-modules --config=node_modules/laravel-mix/setup/webpack.config.js",
    "watch": "npm run development -- --watch",
    "watch-poll": "npm run watch -- --watch-poll",
    "hot": "cross-env NODE_ENV=development node_modules/webpack-dev-server/bin/webpack-dev-server.js --inline --hot --disable-host-check --config=node_modules/laravel-mix/setup/webpack.config.js",
    "production": "cross-env NODE_ENV=production node_modules/webpack/bin/webpack.js --no-progress --hide-modules --config=node_modules/laravel-mix/setup/webpack.config.js"
}
```

AFTER
```js
"scripts": {
    "development": "mix",
    "watch": "mix watch",
    "watch-poll": "mix watch -- --watch-options-poll=1000",
    "hot": "mix watch --hot",
    "production": "mix --production"
}
```

# mix-env-file

https://laravel-mix.com/extensions/env-file


# laravel-mix-bundle-analyzer

bundle size の状態を確認できる。

127.0.0.0:8888 で確認できる。

https://qiita.com/pakio/items/dda7fea8cef964891c45

インストール

```
npm install laravel-mix-bundle-analyzer --save-dev
```

webpack.mix.js

```js
require("laravel-mix-bundle-analyzer")
mix.bundleAnalyzer()
```

# extract メソッド(ベンダの抽出)

vendor.js

https://readouble.com/laravel/5.4/ja/mix.html

webpack.mix.js

```js
mix.extract()
```

# collection

laravel の collection メソッド一覧

https://qiita.com/nunulk/items/9e0c4a371e94d763aed6

http://qiita.com/nunulk/items/ef82a35aa2b75200afa3

http://qiita.com/nunulk/items/e92d34c4092f202e60fa


# laravel-permission


https://qiita.com/Fell/items/7cd398b8ae65ac42950f

https://spatie.be/docs/laravel-permission/v3/advanced-usage/seeding


# 複合キー追加

```php
$table->unique(['user_id', 'photo_id']);
```

https://readouble.com/laravel/5.5/ja/migrations.html

# 外部キー 

https://mseeeen.msen.jp/laravel-53-eloquent-orm-2/#i-8

```php
$table->unsignedBigInteger('user_id');
$table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
```

# empty() と isEmpty()

https://www.larajapan.com/2019/03/02/isempty%E3%81%A7%E3%81%97%E3%81%9F/

- empty()は引数が配列なら空を認識するが、Collectionのオブジェクトなら空を判断できない


# 親子リレーション

https://www.yoheim.net/blog.php?q=20181104

```php
$post = Post::with('contents')->find(1);
return $post;
```

# api.php と web.php

https://teratail.com/questions/308164

https://awesome-linus.com/2019/04/06/laravel-api-php-csrf/

- api.php は CSRF保護がない


# 外部キーがデフォルトでないパターン

https://qiita.com/saya1001kirinn/items/69e2146691101f92d57b


# boot の saving と creating

https://qiita.com/b_a_a_d_o/items/ca06024d80499f1ece14

# save と updateは処理が異なる

https://qiita.com/gomaaa/items/91e5cbd319279a2db6ec

# take()とlimit()


https://www.javaer101.com/en/article/16765398.html

https://stackoverflow.com/questions/45120135/in-laravel-eloquent-what-is-the-difference-between-limit-vs-take/45120150#:~:text=limit%20only%20works%20for%20eloquent,ORM%20or%20Query%20Builder%20objects.


- take() は limit()のalias

- take()はEloqient + Collectionにも適用可能

# pusher を利用してwebsocketによる両方向通信

https://qiita.com/shotashimura/items/d0019032f941458b0963

https://www.webprofessional.jp/add-real-time-notifications-laravel-pusher/

https://noumenon-th.net/programming/2020/07/11/aravel-broadcast/


# composer install 時のメモリ不足

https://uiuifree.com/blog/develop/php-composer-install-memory-limit/

https://getcomposer.org/doc/articles/troubleshooting.md#memory-limit-errors

https://getcomposer.org/doc/articles/troubleshooting.md#memory-limit-errors


# Eloquent 便利機能

https://qiita.com/henriquebremenkanp/items/e21de43e4b9079265d7f


# NULL許容型

https://qiita.com/ymm1x/items/e53ded283080ca3a42b4


デフォルト値

```php
function($name = null){
}
```

nullable 型宣言

```php
function(?$name){
}
```