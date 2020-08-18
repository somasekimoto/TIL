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

# if 
if([条件],[処理],[条件を満たさない時の処理])

# concat_ws

```sql
select concat_ws('/', name, age, address) as personal_info from users
/*/ 第一引数で区切り文字を入れられる
```


# ifnull
第一引数が NULL のときに第二引数の値を返す。

```sql
select ifnull(address, 0) from users
```