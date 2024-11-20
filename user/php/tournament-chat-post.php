<?php
session_start();
require 'db-connect.php';

// セッションからユーザーIDを取得
if (!isset($_SESSION['User']['user_id'])) {
    die("エラー: ログインしていません。");
}

$user_id = $_SESSION['User']['user_id'];

// POSTでチャット、tournament_id、roundが送信されていることを確認
if (isset($_POST['chat']) && isset($_POST['tournament_id']) && isset($_POST['round'])) {
    $chat = $_POST['chat'];
    $tournament_id = $_POST['tournament_id'];
    $round = $_POST['round'];
    $image_path = null;

    // 画像アップロード処理
    if (!empty($_FILES['image']['name'])) {
        $upload_dir = '../img/'; // ../img フォルダに変更
        $filename = uniqid() . '_' . basename($_FILES['image']['name']);
        $target_path = $upload_dir . $filename;

        // ファイルを指定ディレクトリに移動
        if (move_uploaded_file($_FILES['image']['tmp_name'], $target_path)) {
            $image_path = $target_path;
        } else {
            echo "画像のアップロードに失敗しました。";
            exit;
        }
    }

    try {
        // データベース接続
        $pdo = new PDO($connect, USER, PASS);

        // チャットメッセージをtournament_chatテーブルに挿入
        $sql = $pdo->prepare("INSERT INTO tournament_chat (chat, user_id, is_read, created_at, tournament_id, round, image_path) 
                              VALUES (?, ?, 0, NOW(), ?, ?, ?)");
        $sql->execute([$chat, $user_id, $tournament_id, $round, $image_path]);

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
