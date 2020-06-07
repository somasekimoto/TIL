# SQL bolt NOTE

## lesson1

## lesson2

## lesson3

## lesson4

- DISTINCT : discard rows which have duplicate column value
- ORDER BY column ASC/DESC : sort result by certain column
- LIMIT num_limit : limit the number of rows
- OFFSET num_set : specify where to start counting

## Review

## lesson6

## lesson7

- JOIN を使うと、複数のテーブルが一つのテーブルかのように扱える。

## lesson8

- NULL のデータの扱いは重要。0 や empty など、デフォルトの値を適当なものにすることが大事。
- IS NULL, IS NOT NULL : test whether the value is NULL or not.

## lesson9

- AS を利用して、読みやすいカラム名、テーブル名などを設定する。

## lesson10

- you can simplify or summarize the query by using aggregate functions. ex. SUM, AVG, MIN, MAX
- GROUP BY : grouping rows that have the same value in the column specified.

## lesson11

- HAVING : It's clause like WHERE, which is basically used with GROUP BY.

## lesson12

- It's important to understand the order of the execution so that you can easily read the query.

## lesson13

- INSERT INTO table_name (column 1, column 2, ..., column N) VALUES(data 1, data 2, ..., data N)

## lesson14

If you decide to leave out the WHERE constraint, then all rows are updated or removed.

## lesson15

## lesson16

- need to understand the constraints and data types, such as FLOAT, DOUBLE, REAL, BLOB, and AUTOINCREMENT.

## lesson17

- ALTER TABLE : change something in the table by using ADD, DROP, RENAME etc.
- DEFAULT : to set default value in the column.

## lesson18

### when you use "DROP TABLE IF EXISTS mytable;"

"**In addition, if you have another table that is dependent on columns in table you are removing (for example, with a FOREIGN KEY dependency) then you will have to either update all dependent tables first to remove the dependent rows or to remove those tables entirely**"

## lessonX

Congrats!
