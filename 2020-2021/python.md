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

ssm = boto3.client('ssm')
ssm.get_parameter(Name=[ssm にある環境変数])['Parameter']['Value']
# ssmから環境変数を取ってくることもできる
```

## s3

```python
s3 = boto3.resource('s3')
bucket = s3.Bucket([バケット名])
# バケットを選択する

bucket.put_pbject(Key, Body)
s3.meta.client.upload_file(Key, BucketName, file_name)
```

# sleep で処理をいったん停止させる

```python
import time

time.sleep([秒数])
```

# pymysql

mysql と接続・操作するためのライブラリ

## 接続

```python
conn = pymysql.connect(
    [host],
    [db_username],
    [db_password(alias は passwd)],
    [database(alias は db)],
    [port],
)
```

parameter は他にもある

https://pymysql.readthedocs.io/en/latest/modules/connections.html

## DB 操作

```python
with conn.cursor(pymysql.cursors.DictCursor) as cur:
    # Dict を返す
    cur.execute('[sql文]')
    # sql文実行
    all_results = cur.fetchall()
    # 結果を取得
```

# items(), keys(), values()

dict の for ループ処理時などに使う。

参考記事

https://note.nkmk.me/python-dict-keys-values-items/

```python
jp_dict = {
    "hoge": "fuga",
    "hogehoge": "fugafuga",
}
for key, value in jp_dict.items():
    text = key + "は、" + value + "です。\n"
    print(text)
# hogeは、fugaです。
# dict から key, value を使ってiterate したい場合は、items()を使う

for key in jp_dict.keys():
    text = key + "は、" + jp_dict[key] + "です。\n"
    print(text)
# hogeは、fugaです。
# key だけ、value だけ iterate することもできる
```

# join

```python
array = ['Ruby', 'Java', 'Python']

text = '/'.join(array)
# Ruby/Java/Python
```

# 内包表記

一行でイテレーションして、新しい辞書型 もしくは 配列を返す。

```python
dict = {key1: value1, key2:value2, key3: value3}

'・'.join(value): key for key, value in dict.items()
```

# Tweepy

参考記事

https://qiita.com/kitarikes/items/543ab75a379f0bf903cc

```python
import tweepy

consumer_key = '*********************************'
consumer_secret = '*************************************'
access_token = '*******************************************'
access_token_secret = '****************************************'

auth = tweepy.OAuthHandler(consumer_key, consumer_secret)
auth = tweepy.AppAuthHandler(consumer_key, consumer_secret)
auth.set_access_token(access_token, access_token_secret)

api = tweepy.API(auth)

public_tweets = api.home_timeline()
for tweet in public_tweets:
    print('-------------------------')
    print(tweet.text)
```

# 標準入力

```python
a = input()
b = input().strip()
array = input().strip().split()
# ['3', '4', '5', '6']
array = map(int, input().strip().split())
# <map object at 0x7efc278260a0>  map オブジェクト
array = list(map(int, input().strip().split()))
# [3, 4, 5, 6] list
```

# for で index を扱う方法

```python
array = ['hoge', 'fuga', 'hogehoge', 'fugafuga']
for i in range(len(array)):
    print(array[i])
# hoge
# fuga
# hogehoge
# fugafuga

# もしくは、enumerate を使う。
for i, item in enumerate(array):
    print(i)
    print(item)
# 0
# hoge
# 1
# fuga
# 2
# hogehoge
# 3
# fugafuga
```

# twitter 検索術

tweepy で search メソッド使用時に使える。

参考記事

https://gist.github.com/cucmberium/e687e88565b6a9ca7039

```python
cric_tweet = tweepy.Cursor(
        api.search, q='#松岡茉優 filter:retweets filter:images min_faves:10', lang='ja', result_type='mixed').items(40)
    print(cric_tweet)
    for tweet in cric_tweet:
        print(tweet.created_at)
        print(tweet.text)
        print(tweet.lang)
        print(tweet.favorite_count)

```

# 切り捨て除算

```python
n = 26
n // 5
# 5 ... 5.2 の小数点以下を切り捨て、整数を返す。
```

# getattr() と eval() で動的にファイルと関数を実行する。（ファイル名と関数名同じときのみ）

## getattr()

オブジェクトで指定された属性の値を返す関数

参考記事

https://techacademy.jp/magazine/31147

## eval()

文字列を python コードとして実行する。

## コード

hoge.py

```python
def hoge(a, b):
    return a + b
```

fuga.py

```python
import hoge

def hogehoge():

hoge_string = "hoge"
    sum = getattr(eval(hoge_string), hoge_string)(2, 2)
    # getattr(object, function name)(arg, arg)
    print(sum)
# 4
```

# openpyxl

python で Microsoft Excel を扱うためのモジュール

参考文献

https://gammasoft.jp/support/how-to-use-openpyxl-for-excel-file/

```python
wb = load_workbook(filename = './Book1.xlsx')
ws = wb['シート１']
copy = wb.copy_worksheet(ws)
c4 = ws['C4']
cell = c4.value
wb.save('Book1.xlsx')
```


# assertion

https://codezine.jp/article/detail/12179


# selenium

https://qiita.com/memakura/items/20a02161fa7e18d8a693


## ログイン状態を保持する

https://engineeeer.com/python-selenium-chrome-login/


Google Chrome で chrome://version/ とURLに打ち込むとユーザー情報が出てくる。

「プロフィールパス」--user-data-dir に該当する

```python
from selenium import webdriver

options = webdriver.ChromeOptions()
options.add_argument('--user-data-dir=/Users/[username]/Library/ApplicationSupport/Google/Chrome/Profile 2')
options.add_argument('--profile-directory=Profile 2')
driver = webdriver.Chrome(options=options)
driver.get('[URL]')
```


# コマンドラインから引数を取得する

https://note.nkmk.me/python-command-line-arguments/


test.py
```python
import sys
print(sys.argv)
```

```
test.py 123 Hello

>>>['test.py', '123', 'Hello']
```

# ファイル、ディレクトリ操作

https://note.nkmk.me/python-os-remove-rmdir-removedirs-shutil-rmtree/


# base64

https://stackoverflow.com/questions/61904942/how-to-convert-a-excel-file-to-base64-and-then-convert-base64-back-to-excel-in-p



# Flask と Vue.js 画像ファイルアップロード

https://qiita.com/rockguitar67/items/f8edc33dd221d8f4e965


# Flask ファイルダウンロード

https://qiita.com/5zm/items/760000cf63b176be544c


# itertools

https://qiita.com/anmint/items/37ca0ded5e1d360b51f3