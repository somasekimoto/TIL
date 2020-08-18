# s3

```
aws s3 mb s3://[バケット名] 
バケットの作成
aws s3 rb s3://[バケット名]
バケットの削除
```

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