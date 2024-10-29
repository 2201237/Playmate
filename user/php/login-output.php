<?php session_start();?>
<?php require 'db-connect.php'; ?>
<?php

  unset($_SESSION['User']);
  $pdo=new PDO($connect, USER, PASS);
  $sql=$pdo->prepare('select * from users where user_mail=?');
  $sql->execute([$_REQUEST['login'],$_REQUEST['password']]);

  if(password_verify($_POST['password'],$row['user_pw'])==true)
  foreach($sql as $row){
    $_SESSION['User']=[
      'user_id'=>$row['user_id'],'user_name'=>$row['user_name'],
      'user_mail'=>$row['user_mail'],'user_pw'=>$row['user_pw'],
      'user_address'=>$row['user_address'],'user_torokubi'=>$row['user_torokubi']
      //,'login'=>$row['login'],'password'=>$row['password']
    ]; 
  }
?>