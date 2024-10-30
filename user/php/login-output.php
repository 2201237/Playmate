<?php session_start();?>
<?php require 'db-connect.php'; ?>
<?php

  unset($_SESSION['User']);
  $pdo=new PDO($connect, USER, PASS);
  $sql=$pdo->prepare('select * from users where user_mail=?');
  $sql->execute([$_POST['email']]);

  foreach($sql as $row){
    if(password_verify($_POST['password'],$row['user_pass'])==true){
      $_SESSION['User']=[
        'user_id'=>$row['user_id'],'user_name'=>$row['user_name'],
        'user_mail'=>$row['user_mail']
      ]; 
    }
  }

  header('Location:home.php');
?>