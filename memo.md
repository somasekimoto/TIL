# Memo

## Short Cut

### Php Storm

- Shift + Shift : どこでも検索
- Command + d: 下の行に複製
- Command + e : 履歴からファイル検索
- Command + Shift + option + [, or ] : コード・ブロックの最後/先頭まで選択
- Command + Shift + up arrow/down arrow : 上下の行の入れ替え
- Command + S : "Save and Format" に変更した。url (https://stackoverflow.com/questions/12496669/code-reformatting-on-save-in-phpstorm-or-other-jetbrains-ide)
- command + tab : 起動中のアプリの選択

---

### Alias

- gs = git status
- gad = git add
- gc = git commit
- pas = php artisan serve
- pa = php artisan
- la = ls -a
- ll = ls -l
- lla = ls -lla
- gst = git stash
- gstl = git stash list
- gstp = git stash pop
- gsta = git stash apply
- gb = git branch
- gch = git checkout

---

### Browser

- Command + Shift + r : Full Reload (it clears the cache and reload the browser)
- command + shift + f : タブを非表示にする。表示させる。

---

### Slack
- command + k : チャンネル検索

---

## Useful tips & tools

### **pwgen** (password generator)

easy password maker

```
$ brew install pwgen
```

```
$ pwgen 10 3
osaimieG6o yawa6eiCoo iGheiroo3U
```

options & more info in JP --> https://qiita.com/icedpasta1832/items/57d0d9805f04b6e79875

### **"&&" in CLI**

**example**

```
$ npm run prod
$ sls deploy -s prod
```

you can type 2 commands at once.

```
$ npm run prod && sls deploy -s prod

  # if something wrong happens in any execution, it'll be aborted automatically.
```

### Makefile

参考 https://qiita.com/ucan-lab/items/cfe646a34f6d8c9728ed?utm_campaign=popular_items&utm_medium=feed&utm_source=popular_items

make コマンドが使える環境であれば、どこでもできる。
開発の中で、パターン化したコマンドたちをグループ分けして、一気に行ってくれる。

Makefile

```make
stg:
    php artisan config:clear
    npm run dev
    sls deploy -s stg -v
prod:
    php artisan config:clear
    npm run prod
    sls deploy -s prod -v
local:
    php artisan config:clear
    npm run dev
    php artisan serve
```

```
$ make stg // これで Makefile 内の stg のコマンド3つが実行される。
```

注意：**PhpStorm や VSCODE で Makefile に記述しない( tab と space の関係で)。 vim で書く**

## curl コマンド オプション

参考記事

https://qiita.com/ryuichi1208/items/e4e1b27ff7d54a66dcd9

- -I : HTTP ヘッダのみを取得します。
- -i : HTTP ヘッダを出力に含める。
- -v : リクエストとレスポンスのヘッダを表示。
- -A "ユーザーエージェント" "[URL]" : 特定のユーザーエージェントでのリクエストであると認識される。
- -X POST  "[URL]" : POST, PUT, PATCH, DELETE でリクエスト
- -d "name=hoge&num=30" : パラメーターを指定
- -L "[URL]" : リダイレクトを追従
- -o : レスポンスボディの出力先を指定

## HTTP Response Status Code

参考記事

https://developer.mozilla.org/ja/docs/Web/HTTP/Status

## grepコマンド

参考記事

https://eng-entrance.com/linux-command-grep#-E

```
cat [ファイル名] | grep -E '[検索正規表現 or 検索したい文字列]'
```

## arp コマンド
ARPテーブル一覧を表示させるためのコマンド。

ARP は IPアドレスから MAC アドレスを調べる仕組み

参考記事

https://wa3.i-3-i.info/word11340.html

```
arp -a
? (192.168.250.254) at 74:4d:24:e5:ae:47 on en0 ifscope [ethernet]
? (224.0.0.251) at 1:0:5e:0:0:fe on en0 ifscope permanent [ethernet]
? (230.230.230.230) at 1:0:5e:46:e6:e6 on en0 ifscope permanent [ethernet]
? (239.255.255.250) at 1:0:6e:4f:ff:fa on en0 ifscope permanent [ethernet]
```



## ping

クライアントからサーバまでIPパケットが到達するかどうか確認をするコマンド

参考記事

https://qiita.com/hana_shin/items/c31b0d05a91244c4db83

```
ping [IP アドレス]
```

## ssh

### curlで公開鍵をセット

```
curl https://github.com/somasekimoto.keys >> ~/.ssh/authorized_keys
```

### 公開鍵と秘密鍵で余計な情報を削除する

https://dev.classmethod.jp/articles/ssh-keygen-tips/


## サーバープロセス→configファイル→実行ディレクトリ

https://www.wakuwakubank.com/posts/396-linux-ps-memory-device/

### 実行中のプロセスを確認

```
ps aux
ps aux | grep httpd
```

### .confファイルを探索
```
find . -name "httpd.conf"
```

### サーバーのconfigファイル内より実行ディレクトリを導く

```
cat /etc/httpd/conf/httpd.conf
```



