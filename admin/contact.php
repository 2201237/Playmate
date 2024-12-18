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
    <title>お問い合わせ管理</title>
    <link rel="stylesheet" href="css/game-manage.css">
</head>
<body>
    <a href="admintop.php" class="back">←戻る</a>
    <a href="logout.php" class="logout">ログアウト</a>

    <h2>お問い合わせ管理</h2>

    <div>
        <button class="menu-button" onclick="location.href='inquiry-list.php'">お問い合わせ一覧</button><br>
        <button class="menu-button" onclick="location.href='resolved-list.php'">お問い合わせ解決</button><br>
        <button class="menu-button" onclick="location.href='on-hold-list.php'">お問い合わせ保留</button><br>
        <button class="menu-button" onclick="location.href='contacts_ge.php'">ジャンル管理</button>
    </div>
</body>
</html>