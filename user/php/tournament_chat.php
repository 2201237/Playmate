<?php
session_start();
// データベース接続情報
require('db-connect.php');

try {
    // データベース接続
    try {
        $pdo=new PDO($connect,USER,PASS);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        echo "データベース接続エラー: " . $e->getMessage();
    }
    
    // フォームデータ取得
    // $user_id = $_SESSION['User']['user_id'];
    // $chat = $_POST['chat'];

    // SQLクエリ作成
    $sql = "INSERT INTO tournament_chat (user_id, tournament_id, round, chat) VALUES ('1', '1', '1', '')";
    $stmt = $pdo->prepare($sql);

    // パラメータバインド
    // $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    // $stmt->bindParam(':tournament_id', $tournament_id, PDO::PARAM_INT);
    // $stmt->bindParam(':round', $round, PDO::PARAM_INT);
    // $stmt->bindParam(':chat', $chat, PDO::PARAM_STR);

    // クエリ実行
    $stmt->execute();

    echo "チャットが投稿されました。";
} catch (PDOException $e) {
    echo "エラー: " . $e->getMessage();
}
?>
