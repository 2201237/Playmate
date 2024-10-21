<?php
      session_start();
      require 'db-connect.php';
      $pdo=new PDO($connect,USER,PASS);

      $lastId=$pdo->lastInsertId();
      $sql=$pdo->prepare('insert into users(user_name, user_pass,'.
                        'user_mail) values(?,?,?)');
      $sql->execute([$_POST['name'], $_POST['password'],$_POST['email']]);

      header('Location:login-input.php');
?>
