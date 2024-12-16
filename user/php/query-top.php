<?php session_start(); 
require 'db-connect.php';
require 'header.php';
$pdo=new PDO($connect,USER,PASS);

$sql=$pdo->prepare('select * FROM contacts_ge');
$sql->execute();

?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>お問い合わせメニュー</title>
    <link rel="stylesheet" href="../css/header.css">
    <link rel="stylesheet" href="../css/query-top.css">
</head>
<body>

    <h2 style="text-align:center">お問い合わせメニュー</h2>

    <div>
        <button class="menu-button" onclick="location.href='infomation-input.php'">お問い合わせ送信</button><br><br>
        <button class="menu-button" onclick="location.href='infomation-reception.php'">受信ボックス</button>
    </div>
</body>
</html>