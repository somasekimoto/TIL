# git add -p

```
$ git add -p

unstaged の変更が、部分ごとに表示される。

y - この hunk を stage する
n - skip する
q - 終了する
a - その以降の hunk をすべて stage する
d - 以降の hunk をすべて skip する
s - hunk を分割する
e - 手動で現在の hunkを修正する
? - ヘルプを表示する
```

＊　変更されたファイルだけ！！　新しく追加されたファイルは出てこない。

# git reset コマンドについて

参考 (https://qiita.com/shuntaro_tamura/items/db1aef9cf9d78db50ffe)

- "HEAD" は "@" と同じ。
- "HEAD^"、"HEAD" "^" があるのとないのとでは変わる。
- 基本的に、"git reset --soft" で巻き戻る。
