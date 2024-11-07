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
    <link rel="stylesheet" href="css/game-manage.css">
    <title>ゲーム管理</title>
</head>
<body>
    <a href="admintop.php" class="back">←戻る</a>
    <a href="login.php" class="logout">ログアウト</a>

    <h2>ゲーム管理</h2>

    <div>
        <button class="menu-button" onclick="location.href='game-title.html'">ゲームタイトル追加</button><br>
        <button class="menu-button" onclick="location.href='game-title.html'">ゲームタイトル変更</button><br>
    </div>
</body>
</html>