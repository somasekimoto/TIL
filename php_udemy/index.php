<!DOCTYPE html>

<head></head>
<body>
<?php 

  /*こんにち
  わんこそば*/
  echo ('php success');
  echo ('<br>');
  echo (123);
  echo ('<br>');
  echo 'こんに""ちは';
  echo ('<br>');
  echo "おっす";
  echo ('<br>');

  // 変数 動的型付け
  $test_1 = 1234;

  // 変数名の先頭は、英文字かアンダーバー
  $test_2 = 'hello';

  // 変数を組み合わせるときは、ピリオドを使う
  // 変数を組み合わせると、文字列になる
  $test_3 = $test_1 . $test_2;
  echo $test_3;
  echo ('<br>');
  // 配列　オブジェクト　コレクションの型を知りたい時に便利
  var_dump ($test_3);
  echo ('<br>');
  //定数　大文字で定義する
  const MAX =10;
  //変更できない
  const MAX_2 =15;
  echo MAX;
  echo ('<br>');

  //配列
  $array_1 = [1,2,3];
  // 多次元配列にすることにより、行と列を作れる
  $array_2 = [['赤','青','黄色'],['red','blue','yellow']];
  // このままだと、Arrayという文字が出てしまう
  echo $array_1;
  echo ('<br>');
  echo $array_1[0];
  echo ('<br>');
  // <pre>で縦並びにできる
  echo ('<pre>');
  var_dump ($array_1);
  echo ('</pre>');
  echo ('<pre>');
  var_dump ($array_2);
  echo ('</pre>');

  // blueを出したい時
  echo $array[1][1];
  echo ('<br>');

  // 連想配列
  $array_member = [
    'name' => 'Soma',
    'height' => 170,
    'hobby' => 'サッカー'
  ];
  echo $array_member['hobby'];
?>
</body>
</html>