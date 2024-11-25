<?php
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);
ob_start();  // 出力バッファリング開始

require 'db-connect.php';
require 'header.php';

try {
    // データベース接続
    $pdo = new PDO($connect, USER, PASS, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    ]);

    // ゲームデータ取得
    $stmt = $pdo->prepare("SELECT game_id, title FROM game");
    $stmt->execute();
    $games = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // ログインチェック
    if (!isset($_SESSION['User']['user_id'])) {
        header('Location: login-input.php');
        exit;
    }

    // フォームが送信された場合の処理
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // フォームデータを取得
        $game_id = $_POST['game_id'];
        $title = $_POST['title'];
        $chat = $_POST['chat'];
        $user_id = $_SESSION['User']['user_id'];

        // board_titleテーブルにタイトルを挿入
        $stmt = $pdo->prepare("INSERT INTO board_title (game_id, title, user_id) VALUES (:game_id, :title, :user_id)");
        $stmt->bindParam(':game_id', $game_id, PDO::PARAM_INT);
        $stmt->bindParam(':title', $title, PDO::PARAM_STR);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->execute();
        $board_title_id = $pdo->lastInsertId();

        // board_chatテーブルに投稿内容を挿入
        $stmt = $pdo->prepare("INSERT INTO board_chat (board_title_id, chat) VALUES (:board_title_id, :chat)");
        $stmt->bindParam(':board_title_id', $board_title_id, PDO::PARAM_INT);
        $stmt->bindParam(':chat', $chat, PDO::PARAM_STR);
        $stmt->execute();

        // リダイレクト
        header("Location: chatboard.php?board_title_id=" . $board_title_id);
        exit;
    }

} catch (PDOException $e) {
    echo "データベースエラー: " . htmlspecialchars($e->getMessage());
} catch (Exception $e) {
    echo "エラー: " . htmlspecialchars($e->getMessage());
}

ob_end_flush();  // 出力バッファをフラッシュ
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/header.css">
    <link rel="stylesheet" href="../css/chatboard.css">


    <title>PlayMate - 掲示板作成</title>
</head>
<body>
    <h3>掲示板作成</h3>
    <form method="POST" class = "create" action="">
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
        <input type="text" id="title" class = "ctext" name="title" required>
        <br><br>

        <label for="chat">投稿内容</label>
        <textarea id="chat" name="chat" rows="5" cols="40" required></textarea>
        <br><br>

        <button type="submit">作成</button>
        <button type="button" class = "cbut" onclick="location.href='index.php'">戻る</button>
    </form>
</body>
</html>