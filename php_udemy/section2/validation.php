<?php 
  function validation($data){

    $error = [];

    // 氏名
    if (empty($data['your_name']) || 20 < mb_strlen($data['your_name'])){
      $error[] = '「氏名」は20文字以内で入力してください。';
    }
    // メールアドレス
    if (empty($data['email']) || !filter_var($data['email'], FILTER_VALIDATE_EMAIL)){
      $error[] = '「メールアドレス」は正しい形式で入力してください。';
    }
    //url
    if (!empty($data['url'])){
      if (!filter_var($data['url'], FILTER_VALIDATE_URL)){
        $error[] = '「 ホームページ」は正しい形式で入力してください。';
      }
    }
    // 性別
    if (!isset($data['gender'])){
      $error[] = '「性別」は必ず入力してください。';
    }
    // 年齢
    if (empty($data['age']) || 6 < $data['age']){
      $error[] = '「年齢」は必ず入力してください。';
    }
    // お問い合わせ内容
    if (empty($data['contact']) || 200 < mb_strlen($data['contact'])){
      $error[] = '「お問い合わせ内容」は200文字以内で入力してください。';
    }
    //注意事項
    if ($data['caution'] !== "1"){
      $error[] = '「注意事項」をご確認ください。';
    }


    return $error;
  }
?>