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

      //BANされているユーザーがいた場合、警告画面に遷移
      $banConfirm=$pdo->prepare('select * from ban where user_id = ?');
      $banConfirm->execute([$row['user_id']]);
      if ( !empty($banConfirm->fetchAll()) ) {
        header('Location:ban.php');
        exit;
      }

      //ログイン処理
      unset($_SESSION['User']);
      $_SESSION['User']=[
        'user_id'=>$row['user_id'],'user_name'=>$row['user_name'],
        'user_mail'=>$row['user_mail'],'user_profile'=>$row['profile'],
        'user_icon'=>$row['icon'],'user_message'=>null
      ]; 
      header('Location:home.php');
      exit;
    }else{
      //パスワード不一致
      $_SESSION['User']['message'] = 'パスワードが間違っています';
      header('Location:login-input.php');
      exit;
    }
  }
?>