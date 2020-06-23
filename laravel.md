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

1. 以下を実行。s3 と laravel を連携させるパッケージです。

```
$ composer require league/flysystem-aws-s3-v3
```

## To be continued..

# S3 に アップロードした view ファイルの URL を一時的なものにする。

1. .env の "AWS_ACCESS_KEY=" などの AWS 関連の部分を複製する。そして、S3 のバケットのポリシーを持つキーを入力する。

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

2. config/filesystems.php に以下のように記述

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

3. app/helpers.php 作成・編集

```php
<?php
if (!function_exists('s3_asset')) {
    function s3_asset($path)
    {
        return \Illuminate\Support\Facades\Storage::disk('s3')->temporaryUrl($path, now()->addMinute());
    }
}
```

4. composer.json に追記

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

5. composer dump-autoload 実行。これで、helpers.php の s3_asset 関数が使えるようになりました。

6. resources/views/layouts/app.blade.php に 関数の記述

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
