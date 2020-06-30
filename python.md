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