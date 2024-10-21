<?php
      session_start();
      require 'db-connect.php';
      $pdo=new PDO($connect,USER,PASS);

      $lastId=$pdo->lastInsertId();
      $sql=$pdo->prepare('insert into users(user_name, user_pass,'.
                        'user_mail) values(?,?,?)');
      $sql->execute([$_POST['name'], password_hash($_POST['password'], PASSWORD_DEFAULT),$_POST['email']]);

      header('Location:login-input.php');
?>
