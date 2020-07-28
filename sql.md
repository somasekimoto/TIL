# DB のカラム名を探す

参考記事

https://qiita.com/iika0220/items/2e2adf48a1218a89e4a4

```sql
select * from information_schema.columns where COLUMN_NAME = "カラム名" and table_schema = "データベース名"; 
```