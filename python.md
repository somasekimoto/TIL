# if \_\_name\_\_ == "\_\_main\_\_":

よく module コードのテストに使われる記述。

\_\_name\_\_は、そのファイルで import されている module 名をとることができる。

handler.py

```
import requests
print(requests.__name__)

=> requests
```

しかし、terminal 上などで、python コマンドでファイルとして実行する場合は、

handler.py

```
print(__name__)
```

```
$ python handler.py

__main__
```

になる。つまり、" if \_\_name\_\_ == "\_\_main\_\_": " とは、
「もしこのファイルをコマンドラインの python コマンドで実行したら、、」
ということ。

# os.getenv()

```python
import os

host = os.getenv('THIS_APP_HOST', 'http://localhost:8000')
# host = os.environ.get('THIS_APP_HOST', 'http://localhost:8000') と等価。
# host = os.environ['THIS_APP_HOST']だと、存在しないと Keyerror になる。
# THIS_APP_HOST が存在してない場合、localhostになるように設定している。

url = host + '/my_app/login'

res = requests.get(url..... #省略
```

# f 文字列

参考記事

https://note.nkmk.me/python-f-strings/

```python

name = 'Soma'
age = 23

print(f'{name}, {age}years old')

# Soma, 23years old
```

変数を入れられるだけでなく、書式も指定することができる。
pip 3.5 以降でしか使えない。

# Beautiful Soup

参考記事

https://qiita.com/Chanmoro/items/db51658b073acddea4ac

スクレイピング対象の URL から取得した レスポンスの HTML から BeautifulSoup オブジェクトを作る。

```python

import requests
from bs4 import BeautifulSoup

res = requests.get('[URL]')

soup =BeautifulSoup(res.text, 'html.parser')

title_text = soup.find('title').get_text()

print(title_text)

# HTML の title が出力される

```

# with as

何かの処理の開始時と終了時に必須の処理を実行してくれる。

参考記事

https://techacademy.jp/magazine/15823

```python
with open("file.txt", "r") as fileread
    print(fileread.read())

# 終了の処理記述しなくて済む
```

# json.dumps(), json.dump()

参考記事

https://www.sejuku.net/blog/79338

## dumps()

データを JSON 形式に変える

## dump()

ファイルとして保存する

```python
import json

dict = {"name": "tarou", "age": 23, "gender": "man"}

json_file = open('test.json', 'w')

json.dump(dict, json_file)
```

# boto3

参考記事

https://boto3.amazonaws.com/v1/documentation/api/latest/index.html

AWS 環境を操作するためのライブラリ

```python
import boto3

ec2 = boto3.client('ec2')
# client: 低レベルなインターフェイス

s3 = boto3.resource('s3')
# resource: clientより高レベルなインターフェイス
```

# sleep で処理をいったん停止させる

```python
import time

time.sleep([秒数])
```
