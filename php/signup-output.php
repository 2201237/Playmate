<?php
      session_start();
      require 'db-connect.php';
      $pdo=new PDO($connect,USER,PASS);

      $lastId=$pdo->lastInsertId();
      $sql=$pdo->prepare('insert into users(user_name, user_pass,'.
                        'user_mail) values(?,?,?)');
      $sql->execute([$_POST['name'], $_POST['password'],$_POST['email']]);
      $user_name = htmlspecialchars($_POST['name']);
      $user_pass = htmlspecialchars($_POST['password']);
      $user_mail = htmlspecialchars($_POST['email']);

      header('Location:login-input.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <title>Document</title>
</head>
<body>
<div class="form-wrapper">
        <h1>Sign Up</h1>
        <form action="signup-output.php" method="post">
            <div class="form-item">
                <p>$user_name</p>
            </div>
            <div class="form-item">
                  <p>$user_pass</p>
            </div>
            <div class="form-item">
                  <p>$user_mail</p>
            </div>
            <div class="button-panel">
                <input type="submit" class="button" title="Sign Up" value="Sign UP"></input>
            </div>
        
</body>
</html>