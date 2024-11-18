<?php
session_start();
require 'db-connect.php';
require '../header.html';

// データベース接続
$pdo = new PDO($connect, USER, PASS);

// gameテーブルからデータを取得し、セレクトボックス用に格納
$stmt = $pdo->prepare("SELECT game_id, title FROM game");
$stmt->execute();
$games = $stmt->fetchAll(PDO::FETCH_ASSOC);

// ログインチェック
if (!isset($_SESSION['User']['user_id'])) {
    // ユーザーがログインしていない場合はログインページにリダイレクト
    header('Location: login-input.php');
    exit;
}

// フォームが送信された場合の処理
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // フォームの入力内容を取得
    $game_id = $_POST['game_id'];
    $title = $_POST['title'];
    $chat = $_POST['chat'];
    $user_id = $_SESSION['User']['user_id']; // ログイン中のユーザーIDを取得

    // board_titleテーブルにタイトルを挿入
    $stmt = $pdo->prepare("INSERT INTO board_title (game_id, title, user_id) VALUES (:game_id, :title, :user_id)");
    $stmt->bindParam(':game_id', $game_id, PDO::PARAM_INT);
    $stmt->bindParam(':title', $title, PDO::PARAM_STR);
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->execute();
    $board_title_id = $pdo->lastInsertId(); // 挿入したレコードのIDを取得

    // board_chatテーブルに投稿内容を挿入
    $stmt = $pdo->prepare("INSERT INTO board_chat (board_title_id, chat) VALUES (:board_title_id, :chat)");
    $stmt->bindParam(':board_title_id', $board_title_id, PDO::PARAM_INT);
    $stmt->bindParam(':chat', $chat, PDO::PARAM_STR);
    $stmt->execute();

    // 完了メッセージを表示するためにリダイレクト
    header('Location: success.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/chatboard.css">
    <title>PlayMate - 掲示板作成</title>
</head>
<body>
    <h3>掲示板作成</h3>
    <form method="POST" action="">
        <label for="game_id">ゲームタイトル</label>
        <select name="game_id" id="game_id" required>
            <?php foreach ($games as $game): ?>
                <option value="<?= htmlspecialchars($game['game_id']) ?>">
                    <?= htmlspecialchars($game['title']) ?>
                </option>
            <?php endforeach; ?>
        </select>
        <br><br>

        <label for="title">タイトル</label>
        <input type="text" id="title" name="title" required>
        <br><br>

        <label for="chat">投稿内容</label>
        <textarea id="chat" name="chat" rows="5" cols="40" required></textarea>
        <br><br>

        <button type="submit">作成</button>
        <button type="button" onclick="location.href='index.php'">戻る</button>
    </form>
</body>
</html>