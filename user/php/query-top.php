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
    <link rel="stylesheet" href="css/game-manage.css">
</head>
<body>
    <a href="admintop.php" class="back">←戻る</a>
    <a href="logout.php" class="logout">ログアウト</a>

    <h2>お問い合わせメニュー</h2>

    <div>
        <button class="menu-button" onclick="location.href='infomation-input.php'">お問い合わせ送信</button><br>
        <button class="menu-button" onclick="location.href='infomation-reception.php'">お問い合わせ受信</button>
    </div>
</body>
</html>