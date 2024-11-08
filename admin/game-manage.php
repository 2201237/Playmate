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
    <link rel="stylesheet" href="css/game-manage.css">
    <title>ゲーム管理</title>
</head>
<body>
    <a href="admintop.php" class="back">←戻る</a>
    <a href="logout.php" class="logout">ログアウト</a>

    <h2>ゲーム管理</h2>

    <div>
        <button class="menu-button" onclick="location.href='game-title.html'">ゲームタイトル追加</button><br>
        <button class="menu-button" onclick="location.href='game-title.html'">ゲームタイトル変更</button><br>
    </div>
</body>
</html>