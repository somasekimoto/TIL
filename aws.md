# s3

```
aws s3 mb s3://[バケット名] 
バケットの作成
aws s3 rb s3://[バケット名]
バケットの削除
```

## sync と cp

```
aws s3 sync . s3://xxyy-$(stage)/ --delete --region ap-northeast-1 --content-type text/html
ディレクトリの同期

aws s3 cp . s3://xxyy-$(stage)/ --recursive --include "*.html" --region ap-northeast-1 --content-type text/html
ファイルのコピーの作成
```

### sync --delete

削除したファイルも同期する

### cp --recursive

https://docs.aws.amazon.com/ja_jp/cli/latest/userguide/cli-services-s3-commands.html

指定のディレクトリ内またはプレフィックス内のすべてのファイルやオブジェクトに対してコマンドが実行される。

# ssm

参考記事

https://qiita.com/umihico/items/8c877efc7d242fd0c167

```
環境変数のセット
aws ssm put-parameter --name $1 --type "String" --overwrite --value "$2";

環境変数の取得
aws ssm get-parameter --name $1 --query 'Parameter.Value' --output text;

環境変数のリストを表示
aws ssm get-parameters-by-path --path "/" --query "Parameters[].[Name,Value]" --output text
```