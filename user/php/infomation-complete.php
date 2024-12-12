<?php
session_start();
require 'db-connect.php';
require 'header.php';
$pdo=new PDO($connect,USER,PASS);
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/header.css">
    <link rel="stylesheet" href="../css/infomation-complete.css">

    <title>Document</title>
</head>
<body>
    <?php
    $lastId=$pdo->lastInsertId();

    $sql=$pdo->prepare('insert into contacts(contacts, user_id, contacts_ge_id) values(?,?,?)');
    $sql->execute([$_POST['infomation'],$_SESSION['User']['user_id'],$_POST['conge_id']]);

    echo '<h1>お問い合わせ内容が送信されました。</h1>';
    echo '<p>返信内容が返却されるまでしばらくお待ちください</p>';
    ?>
    <button class="menu-button" onclick="location.href='infomation-input.php'">戻る</button>
</body>
</html>