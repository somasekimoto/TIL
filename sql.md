# DB のカラム名を探す

参考記事

https://qiita.com/iika0220/items/2e2adf48a1218a89e4a4

```sql
select * from information_schema.columns where COLUMN_NAME = "カラム名" and table_schema = "データベース名"; 
```

# ユーザー作成

```sql
create user '[ユーザー名]'@'[ホスト名]' identified by '[パスワード]'
```

# ユーザーに権限を与える

参考記事

https://proengineer.internous.co.jp/content/columnfeature/6638#section300

```sql
grant [権限] on [データベース].[テーブル] to '[ユーザー名]'@'[ホスト名]' identified by '[パスワード]';

flush privileges;
# 権限の変更をデータベースに反映させる。
```