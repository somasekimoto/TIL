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

# group_concat

```sql
select name, age, group_concat(concat(user_id, last_name)) from users where  age = 23  group by member_cd
```

# generated as

一つのカラムの値に対して処理された値を別カラムに保存する時に使う。

```sql
alter table [table_name] add column [column_name] generated as (another_column_name * 2) stored;
```