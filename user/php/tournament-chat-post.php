<?php
session_start();
require 'db-connect.php';

// セッションからユーザーIDを取得
if (!isset($_SESSION['User']['user_id'])) {
    die("エラー: ログインしていません。");
}

$user_id = $_SESSION['User']['user_id'];

// POSTでチャット、tournament_id、roundが送信されていることを確認
if (isset($_POST['chat']) && !empty($_POST['chat']) && isset($_POST['tournament_id']) && isset($_POST['round'])) {
    $chat = $_POST['chat'];  // 変数名を $chat に変更
    $tournament_id = $_POST['tournament_id'];
    $round = $_POST['round'];

    try {
        // データベース接続
        $pdo = new PDO($connect, USER, PASS);

        // チャットメッセージをtournament_chatテーブルに挿入
        $sql = $pdo->prepare("INSERT INTO tournament_chat (chat, user_id, is_read, created_at, tournament_id, round) 
                              VALUES (?, ?, 0, NOW(), ?, ?)");
        $sql->execute([$chat, $user_id, $tournament_id, $round]);  // $chat を使用

        // 投稿後、リダイレクトしてチャットページに戻る
        header("Location: tournament-chat.php?tournament_id=$tournament_id&round=$round");
        exit();

    } catch (PDOException $e) {
        echo "エラー: " . $e->getMessage();
    }
} else {
    echo "エラー: メッセージが送信されていません。";
}
?>
