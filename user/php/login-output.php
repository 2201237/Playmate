<?php session_start();?>
<?php require 'db-connect.php'; ?>
<?php

  //エラーメッセージをセッションに格納する処理
  $pdo=new PDO($connect, USER, PASS);
  $sql=$pdo->prepare('select * from users where user_mail=?');
  $sql->execute([$_POST['email']]);
  if ( empty($sql->fetchAll()) ) {
    $_SESSION['User']['message'] = 'このEメールアドレスを持つアカウントが見つかりません';
    header('Location:login-input.php');
  }

  $sql=$pdo->prepare('select * from users where user_mail=?');
  $sql->execute([$_POST['email']]);
  foreach($sql as $row){
    if(password_verify($_POST['password'],$row['user_pass'])){
      //パスワード一致
      unset($_SESSION['User']);
      $_SESSION['User']=[
        'user_id'=>$row['user_id'],'user_name'=>$row['user_name'],
        'user_mail'=>$row['user_mail'],'user_profile'=>$row['profile'],
        'user_message'=>null
      ]; 
      header('Location:home.php');
    }else{
      //パスワード不一致
      $_SESSION['User']['message'] = 'このEメールアドレスを持つアカウントが見つかりません';
      header('Location:login-input.php');
    }
  }
?>