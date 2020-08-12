# serverless-python-requirements

lambda Python で実行する際、lambda の関数上にない module を import させるための sls のプラグイン。

参考 (https://dev.classmethod.jp/articles/managing-external-modules-with-serverless-framework-plugin/)

## 手順

### 1. "sls plugin install -n" or "npm install --save"

### 2. sls.yml に 追記

### 3. requirements.py 作成・記述 url (https://github.com/UnitedIncome/serverless-python-requirements/blob/c533dac26fdd191f344aab109fdfcf8a33899411/ requirements.py)

### 4. requirements.txt 作成・記述

### 5. デプロイ

# SLS_DEBUG

```
$ sls deploy
```

ここでエラーが出た。

```
For debugging logs, run again after setting the "SLS_DEBUG=*" environment variable.
```

```
$ export SLS_DEBUG=true
```

とすれば直った。

# serverless framework で ELB を利用するために、Lambda にターゲットグループを設定する

lambda はデフォルトではターゲットグループがないので、serverless.yml に設定する必要がある。

```yml
resources:
  Resources:
    InvokePermission:
      Type: AWS::Lambda::Permission
      DependsOn: HelloLambdaFunction # get the function name from .serverless/serverless-state.json
      Properties:
        FunctionName: [Lambda の関数名]
        Action: "lambda:InvokeFunction"
        Principal: elasticloadbalancing.amazonaws.com
    TargetGroup:
      Type: AWS::ElasticLoadBalancingV2::TargetGroup
      DependsOn: InvokePermission
      Properties:
        Name: ターゲットグループ名
        TargetType: lambda
        Targets:
          - Id: arn:aws:lambda:ap-northeast-1:9999999999:function: [Lambda の関数名]
```
# environment にセットする環境変数を file ごとに管理


env/stg.yml

```yml
HOST: hoge
DB_NAME: users
DATABASE: fuga
DB_PASSWORD: hogehoge
```

env/prod.yml

```yml
HOST: fuga
DB_NAME: users
DATABASE: hoge
DB_PASSWORD: fugafuga
```

serverless.yml

```yml
provider:
  name: aws
  runtime: python3.7
  region: ap-northeast-1
  stage: ${opt:stage, 'stg'}
  environment: ${file(./env/${self:provider.stage}.yml)}
```

# sls remove
デプロイされた function を削除する時に使う。

```terminal
sls remove
```