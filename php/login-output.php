<?php session_start();?>
<?php require 'db-connect.php'; ?>
<?php

  unset($_SESSION['User']);
  $pdo=new PDO($connect, USER, PASS);
  $sql=$pdo->prepare('select * from User where user_mail=? and user_pw=?');
  $sql->execute([$_REQUEST['login'],$_REQUEST['password']]);

  // if(password_verify($_POST['password'],$row['user_pw'])==true){ }//ハッシュ化
  foreach($sql as $row){
    $_SESSION['User']=[
      'user_id'=>$row['user_id'],'user_name'=>$row['user_name'],
      'user_mail'=>$row['user_mail'],'user_pw'=>$row['user_pw'],
      'user_address'=>$row['user_address'],'user_torokubi'=>$row['user_torokubi']
      //,'login'=>$row['login'],'password'=>$row['password']
    ]; 
  }

  // 変数の初期化
  $email = '';
  $password = '';
  $err_msg = array();

  // POST送信があるかないか判定
  if (!empty($_POST)) {
      // 各データを変数に格納
      $email = $_POST['login'];
      $password = $_POST['password'];
  }
      // eメールアドレスバリデーションチェック
      // 空白チェック
      if ($email === '') {
        $err_msg['user_mail'] = '入力必須です';
      }

  if (!empty($_POST)) { // パスワードバリデーションチェック
      // 空白チェック
      if ($password === '') {
        $err_msg['user_pw'] = '入力してください';
      }
    }
    
    if(isset($_SESSION['User'])){
      header('Location:../index.html');
      exit;
    }else{
      echo '<p><img class="rogo" src="../img/logo.jpg" alt="写真" width="129" height="110"></p>';
      echo '<div id="center"><h1>';
      echo $err_msg['user_mail'] = 'eメールアドレスまたはパスワードが違います','<br>';
      echo '<a href="login-input.php">ログイン画面へ戻る</a>';
      echo '</h1></div>';
    }
?>
<!DOCTYPE html>
<html lang="ja">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/login.css">
    <link rel="stylesheet" href="css/reset.css" />
    <title>ログイン</title>
  </head>
  <body>
    <div id="center"></div>  
  </body>
</html>