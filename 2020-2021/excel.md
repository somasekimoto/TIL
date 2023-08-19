# excel 関数

## CONCATENATE or CONCAT : 二つ以上の文字列を連結させる
```
CONCAT('おはようございます', &B4&'さん')
```

## TEXT と NOW 関数で日時表示

```
TEXT(E9,"yyyy-mm-dd")
TEXT(NOW(),"yyyy-mm-dd hh:mm:ss")
```

## MATCH, INDEX

```
MATCH([値], [列指定], 0)  0は全一致
MATCH(A2, B:B, 0) 

INDEX([列指定], [行番号]) 
INDEX(A:A, B2)
INDEX(A:A, MATCH(A2, B:B))
```

## if, iferror

条件分岐

```
if([条件], [条件を満たした時の値], [満たさない時の値])
iferror([第一引数], [第一引数がエラーのときの値])
```

## IMPORTRANGE

他のスプレッドシートからデータを表示させる

```
IMPORTRANGE("https://docs.google.com/spreadsheets/d/XXXXXXXXX", "[シート名]!A4:D20")
```

## AVERAGEIF
条件に合ったものの平均を出す

```
AVERAGEIF([条件の範囲], [条件], [平均範囲])
```

## LARGE, SMALL

MAX, MINのように1番の値ではなく、2番目や3番目の値をとるときに使う。

```
LARGE([範囲], [何番目か])
SMALL([範囲], [何番目か])
```
