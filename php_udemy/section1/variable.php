<!DOCTYPE html>

<head></head>
<body>
<?php
  // if(条件){
  //   処理
  // }
  $height = 90;
  $b = '<br>';
  if ($height == 90) {
    echo ' 身長は' . $height . 'cmです。' . $b;
  }
  // == 一致
  // === 型も一致
  $signal = 'red';
  if ($signal === 'red') {
    echo '病院行け'. $b;
    echo ('<br>');
  } else if ($signal === 'yellow'){
    echo '疲れ溜まってる' . $b;
  } else {
    echo '健康' . $b;
  }
  // if文はelseをなるべく使わない方が読みやすい

  //データが入っているかどうか
  //isset empty is_null

  $test = '1';
  if (!empty($test)) {
    echo '変数は空ではありません' . $b;
  }

  // foreach 複数の値
  
  $members = [
    'name' => '本田',
    'height' => 170,
    'hobby' => 'サッカー'
  ];

  foreach($members as $i) {
    echo $i . $b;
  }

  // キーとバリューそれぞれ表示
  foreach($members as $key => $value) {
    echo $key . 'は' . $value . 'です' . $b;
  }

  //繰り返す数が決まっていれば、for

  //繰り返す数が決まっていなければ、while 
  for ($i = 0; $i < 10; $i++ ){
    if( $i === 7) {
    // break;
    continue;
    }
    echo $i . ' ';
  }

  echo $b;

  //switch
  //if文の方が良い 見やすい
  //switchの場合は、==が使われてしまう。
  $data = '1';
  switch($data){
    case $data === 1:
      echo '1です';
      break;
    case 2:
      echo '2です';
      break;
    case 3:
      echo '3です';
      break;
    default:
      echo '1-3ではない';
  } 
?>
</body>
</html>