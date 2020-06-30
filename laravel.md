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

## json_encode() : JSON化 

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