<?php
session_start();
require 'db-connect.php';
$pdo=new PDO($connect,USER,PASS);

$lastId=$pdo->lastInsertId();

$sql=$pdo->prepare('insert into users(user_name,profile, user_pass,'.
'user_mail) values(?,?,?,?)');
$sql->execute([$_POST['name'],$_POST['profile'] ,password_hash($_POST['password'], PASSWORD_DEFAULT),$_POST['email']]);


        echo "<h1>登録が完了しました</h1>";
        // 3秒後にログインページに自動的に遷移
        echo '<script type="text/javascript">
                setTimeout(function() {
                    window.location.href = "login-input.php";
                }, 3000);
              </script>';
        echo "<p>3秒後にホームページに自動的に遷移します...</p>";
?>

