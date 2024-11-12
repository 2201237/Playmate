<?php
session_start();

// ログインチェック
if (!isset($_SESSION['admins']['admin_id'])) {
    header('Location: notlogin.php'); 
    exit();
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PlayMate Admin</title>
    <link rel="stylesheet" href="css/admintop.css">
</head>
<body>
    <h1>PlayMate Admin</h1>
 
   <a href="logout.php" class="logout">ログアウト</a>
    <div>
        <button class="menu-button" onclick="location.href='user.php'">ユーザー管理</button><br>
        <button class="menu-button" onclick="location.href='game-manage.php'">ゲーム管理</button><br>
        <button class="menu-button" onclick="location.href='tournament.php'">大会管理</button><br>
        <button class="menu-button" onclick="location.href='contact.php'">お問い合わせ</button>
    </div>
</body>
</html>