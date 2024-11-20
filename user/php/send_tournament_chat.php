<?php
// データベース接続
try {
    $pdo = new PDO("mysql:host=$host;dbname=tournament_chat;charset=utf8", $username, $password); // DB名変更後
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "データベース接続エラー: " . $e->getMessage();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $chat = $_POST['chat'];
    $user_id = 1; // ユーザーIDを指定（仮の値）
    $tournament_id = 1; // 大会IDを指定（仮の値）

    // メッセージの挿入
    $sql = "INSERT INTO chat_tournaments (chat, user_id, tournament_id, created_at) VALUES (:chat, :user_id, :tournament_id, NOW())";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        'chat' => $chat,
        'user_id' => $user_id,
        'tournament_id' => $tournament_id
    ]);

    // チャットルームにリダイレクト
    header("Location: chat_room.php");
    exit();
}
?>