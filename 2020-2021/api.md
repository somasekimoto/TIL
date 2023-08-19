# Slack API

## 設定方法

### 1. App を作る

"slack api" で検索して、公式のページにいく。

右上の Your App をクリック

create new app をクリックして、アプリを制作。

### 2. 設定

左のバーの 'Basic Information' をクリック。

'Add features and functionality' をクリックして、'Permissions' をクリック。

権限(user Token Scopes か Bot Token Scopes)を付与して、install app をクリック。

ここで一旦は、app 制作完了。

### 3. 利用

公式の Document と、ご自身のプランをみて、利用できる api を選択し、権限に加えていく。

Tester などを利用して、実際のアプリで使う前に、テストを行う。それから、アプリに応用。

## url の見方

```
https://app.slack.com/client/[ワークスペースID]/[ユーザー or チャンネル slack ID]
```

## rate limits

https://api.slack.com/docs/rate-limits

## chat.postMessage

参考記事

グループメンションをつける
text に <!subteam^> をつける

```php
$client->Post($url, [
        'form_params' => [
            "token" =>　[token],
            "channel" => [slack_id],
            'text' => "<!subteam^" . [groupId] . ">\n" . "[message]",
        ]
]);
```

groupId は usergroups.list api で確認する

メンションをつける

```python
'<@' + [ユーザーID] + '>'
```

## conversations.hisotry

ワークスペースのメッセージを取得できる

https://api.slack.com/methods/conversations.history

```python
import requests

url = "https://slack.com/api/conversations.history"

response = requests.get(url, {'channel': [取得するチャンネルのID],'token':[トークン],'limit':[取得するメッセージの数（デフォルトは100）],})

```

## chat.delete

https://api.slack.com/methods/chat.delete


## chat.update

https://api.slack.com/methods/chat.update


# Google App Script

参考記事

https://qiita.com/HeRo/items/4e65dcc82783b2766c03

https://qiita.com/ume3003/items/cd9d05dff014952a73f8

大まかな流れ

```
$ npm i clasp -g
$ clasp login
$ clasp create
$ clasp open
$ clasp pull
```

- **Google App Script の有効化を忘れない**
- **Google アカウントの認証していないと、エラーがでる。("もう少し待ってから試してください" みたいな)**

## GAS の基本的なメソッド

```js
function doPost(e) {
  var c = e.postData.contents
  var contents = JSON.parse(e)
  // 引数で送られてきたパラメータを取得する
  var title = contents.title

  var spreadsheet = SpreadsheetApp.create(title)
  // 新しいスプレッドシートを作成する

  var spreadsheet = SpreadsheetApp.openById(spreadsheetId)
  // 既存のスプレッドシートを取得

  var copiedSpreadsheet = spreadsheet.copy(title)
  // スプレッドシートを複製

  var sheet = spreadsheet.getSheets()[0]
  // 一番最初のシートを取得

  var sheet = spreadsheet.insertSheet()
  // 新しいシートを追加する

  sheet.getRange([開始行], [開始列], [行数], [列数]).setValues([配列])
  // 開始位置や範囲の指定をし、値を挿入する。(配列の数と、行数や列数が同じでないとエラーが出る)
  sheet.getRange([開始行], [開始列], [行数], [列数]).createFilter()
  // フィルターをかける

  sheet.setName([シート名])
  // シート名をセットする

  var url = spreadsheet.getUrl()
  // スプレッドシートの URL を取得

  ContentService.createTextOutput(JSON.stringify({ hoge: "fuga" })).setMimeType(
    ContentService.MimeType.JSON
  )
}
```

# AKASHI API

- 50 以上の API トークンは作成できない。

参考記事

https://akashi.zendesk.com/hc/ja/articles/115000475854-AKASHI-%E5%85%AC%E9%96%8BAPI-%E4%BB%95%E6%A7%98

# Linebot Messaging API

python

```python
from linebot import (
    LineBotApi, WebhookParser
)


from linebot.exceptions import (
    InvalidSignatureError, LineBotApiError
)

from linebot.models import (
    TextSendMessage, ImageSendMessage
)

```


# 市区町村取得API

国土交通省
https://www.land.mlit.go.jp/webland/

https://www.land.mlit.go.jp/webland/api/CitySearch?area=13


RESAS API

https://opendata.resas-portal.go.jp/
