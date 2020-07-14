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

# git checkout

作業ブランチの切り替えや作成( -b オプション)くらいでしか使わないと思っていたが、作業中のファイルの変更を最新のコミットまで一気に戻す( 変更を削除する )ことができると最近知った。

```
$ git status   // 一応どこまで変更したか確認しておこう

 (use "git add/rm <file>..." to update what will be committed)
 (use "git restore <file>..." to discard changes in working directory)
    modified:   git.md  // このファイルの変更を取り消す。git addする前のもの

$ git checkout git.md
Updated 1 path from the index
```

# git revert

参考記事

https://qiita.com/chihiro/items/2fa827d0eac98109e7ee

commit を 取り消しできる。
かなり古い変更を削除したいときに便利。
複数 commit に行うこともできる。

```
$ git revert <commit1> <commit2>
```

git revert は、commit を取り消す、という commit をすること。

オプションをつけることにより、コミットせずに、index に戻すだけ、ということもできるようになる。

```
$ git revert <commit1> <commit2> -n
  or
$ git revert <commit1> <commit2> --no-commit
```

複数のコミットを revert するときに、一度にコミットすることができるので便利。

# git reflog

間違えて git reset してしまった時に、 そのログも含めて表示させるコマンド

```terminal
$ git reflog -n 4
d0cab23 HEAD@{0}: d0cab23: updating HEAD
7938107 HEAD@{1}: commit: 適当なcommit2
12f8a66 HEAD@{2}: commit: 適当なcommit1
129d634 HEAD@{3}: 129d634: updating HEAD

$ git reset --hard 7938107
HEAD is now at 7938107 適当なcommit2

$ git log --oneline
7938107 適当なcommit2
12f8a66 適当なcommit1
```

# git merge と git rebase

参考記事

https://qiita.com/kkam0907/items/f92dd5288622387409be