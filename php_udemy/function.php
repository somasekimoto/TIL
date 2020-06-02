<!DOCTYPE html>

<head></head>
<body>
<?php
  // function test(引数1, 引数2){
  //   //処理
  //   return //戻り値;
  // }
  $b = '<br>';

  $comment = 'Good';

  function getComment($string){
    echo $string;
  }

  $comment = getComment($comment);

  echo $comment;

  echo $b;
  //文字列の長さ
  //日本語 SJIS, UTF-8 3〜6バイト
  $text = 'アイウエオ';
  echo strlen($text) . $b;
  echo mb_strlen($text) . $b;

  $str = '文字列を置換';

  echo str_replace('置換', 'ちかん', $str) . $b;

  // 指定文字列で分割
  $str_2 = '指定文字列で、分割する';
  echo '<pre>';
  var_dump(explode('、', $str_2));
  echo '</pre>';

  //implode
  //正規表現

  $str_3 = '特定の文字列が含まれるかどうか確認する';
  echo preg_match('/文字列/', $str_3) . $b;
  //指定文字列から文字列を取得する
  echo substr('abcde', 2) . $b;
  echo mb_substr('あいうえお', 3) . $b;

  //配列に配列を追加する
  $array = ['りんご', 'みかん'];
  array_push($array, 'ぶどう', 'なし');
  echo '<pre>';
  var_dump($array);
  echo '</pre>';


  $postalCode = '123-4567';
  function getPostalcode($str){
    $replaced = str_replace('-', '', $str);
    $length = strlen($replaced);

    var_dump($length);
    if ($length === 7) {
      return true;
    }
    return false;
  }
  var_dump(getPostalCode($postalCode));

  require 'common.php';
  echo $commonVariable;
  echo __DIR__;
?>
</body>
</html>