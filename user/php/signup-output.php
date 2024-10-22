<?php
      session_start();
      require 'db-connect.php';
      $pdo=new PDO($connect,USER,PASS);

      $lastId=$pdo->lastInsertId();
      // $sql=$pdo->prepare('insert into users(user_name, user_pass,'.
      //                   'user_mail) values(?,?,?)');
      // $sql->execute([$_POST['name'], password_hash($_POST['password'], PASSWORD_DEFAULT),$_POST['email']]);
      $user_name = htmlspecialchars($_POST['name']);
      $user_pass = htmlspecialchars($_POST['password']);
      $user_mail = htmlspecialchars($_POST['email']);
      header('Location:login-input.php');
?>
<!DOCTYPE html>
<html lang="ja">
<head>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <title>Document</title>
</head>
<body>
<div class="form-wrapper">
        <h1>Sign Up</h1>
        <form action="signup-con.php" method="post">
            <div class="form-item">
                  <p>ユーザー名 <?= $user_name; ?></p>
            </div>
            <div class="form-item">
                  <p>パスワード <?= $user_pass; ?></p>
            </div>
            <div class="form-item">
                  <p>メールアドレス <?= $user_mail?></p>
            </div>
            <div>
                <button type="submit" class="back" value="戻る" onclick=history.back()></button>
            </div>
            <div class="button-panel">
                <input type="submit" class="button" title="Sign Up" value="Sign UP"></input>
            </div>
        
</body>
</html>