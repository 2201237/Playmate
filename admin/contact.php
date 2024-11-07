<?php
session_start(); // セッション開始

// ログインチェック
if (!isset($_SESSION['user_id'])) { // user_idがセッションにない場合
    header('Location: notlogin.php'); // login.phpにリダイレクト
    exit(); // 以降のコードを実行しない
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
    <a href="login.php" class="logout">ログアウト</a>

    <h2>お問い合わせ管理</h2>

    <div>
        <button class="menu-button" onclick="location.href='inquiry-list.php'">お問い合わせ一覧</button><br>
        <button class="menu-button" onclick="location.href='resolved-list.php'">お問い合わせ解決</button>
        <button class="menu-button" onclick="location.href='contacts_ge.php'">ジャンル管理</button>
    </div>
</body>
</html>