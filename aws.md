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

## presign

有効期限付きの署名付きURLの生成

cli で生成

https://dev.classmethod.jp/articles/generate-s3-pre-signed-url-by-aws-cli/

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

# codecommit

create repository

```
aws codecommit create-repository --repository-name $repoName
```

create pull request

```
aws codecommit create-pull-request --title fix_nippo --targets repositoryName=$repoName,sourceReference=$sourceBranch,destinationReference=$destinationBranch
```

merge

```
aws codecommit merge-pull-request-by-three-way --pull-request-id $pullRequestId --repository-name $repoName
```

自身で${現在のgitレポジトリ}の${現在のブランチ}から master へのプルリクを行い、自身でマージするワンライナー。.bash_profile に追記してコマンド化できる。

```
function muscle-merge() {
  command aws codecommit merge-pull-request-by-three-way --repository-name $(basename $(git remote get-url origin) .git) --pull-request-id $(aws codecommit create-pull-request --title "Merge branch $(git branch --show-current)" --targets repositoryName=$(basename $(git remote get-url origin) .git),sourceReference=$(git branch --show-current),destinationReference=master --query 'pullRequest.pullRequestId' | sed -e 's/^"//' -e 's/"$//')
}
```

レポジトリの変更を Slack #log-codecommit に通知するルールを作成する

```
cat <<EOF > rule.json
{
    "Name": "slack-log-codecommit-CodeCommitPlayground",
    "EventTypeIds": [
        "codecommit-repository-pull-request-created",
        "codecommit-repository-pull-request-source-updated",
        "codecommit-repository-pull-request-status-changed",
        "codecommit-repository-pull-request-merged",
        "codecommit-repository-approvals-status-changed",
        "codecommit-repository-approvals-rule-override",
        "codecommit-repository-comments-on-commits",
        "codecommit-repository-comments-on-pull-requests",
        "codecommit-repository-branches-and-tags-created",
        "codecommit-repository-branches-and-tags-deleted",
        "codecommit-repository-branches-and-tags-updated"
    ],
    "Resource": "arn:aws:codecommit:ap-northeast-1:XXXXXXXXXX:CodeCommitPlayground",
    "Targets": [
        {
            "TargetType": "AWSChatbotSlack",
            "TargetAddress": "arn:aws:chatbot::XXXXXXXXX:chat-configuration/slack-channel/log-codecommit"
        }
    ],
    "Status": "ENABLED",
    "DetailType": "FULL"
}
EOF
cat rule.json
aws codestar-notifications create-notification-rule --cli-input-json file://rule.json
rm rule.json
```

# cloudfront と ヘッダ X-Cache

https://sig9.hatenablog.com/entry/2017/03/06/120000

Miss from CloudFront: キャッシュが存在しなかった

Hit from CloudFront: キャッシュがヒットした

RefreshHit from CloudFront: キャッシュの有効時間を超えているが、コンテンツに更新が無いのでキャッシュを再利用する

# Route53でALIASレコード使用時のTTL

https://dev.classmethod.jp/articles/amazon-route-53-alias-ttl/


エイリアスリソースレコードセットが CloudFront ディストリビューション、Elastic Beanstalk 環境、ELB ロードバランサー、または Amazon S3 バケットを指し示す場合は、有効期限 (TTL) を設定できません。

- CloudFront：60秒
- Elastic Beanstalk：60秒
- Elastic Load Balancing(ELB)：60秒
- S3：3秒