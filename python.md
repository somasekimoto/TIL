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
