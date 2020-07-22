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

## excel 関数

### CONCATENATE or CONCAT : 二つ以上の文字列を連結させる
```
CONCAT('おはようございます', &B4&'さん')
```

### TEXT と NOW 関数で日時表示

```
TEXT(E9,"yyyy-mm-dd")
TEXT(NOW(),"yyyy-mm-dd hh:mm:ss")
```