<?php 
session_start(); 
require 'db-connect.php'; 

// ログインチェック（管理者） 
if (!isset($_SESSION['admins'])) { 
    echo "管理者ログインが必要です。"; 
    exit; 
} 

$admin_id = $_SESSION['admins']['admin_id']; 

// 投稿内容の確認
if (isset($_POST['chat']) && isset($_POST['tournament_id']) && isset($_POST['round'])) {
    $chat = $_POST['chat'];
    $tournament_id = $_POST['tournament_id'];
    $round = $_POST['round'];
    $image_path = null;

    // 画像アップロード
    if (!empty($_FILES['image']['name'])) {
        $upload_dir = 'win-loss-image/';
        $filename = uniqid() . '_' . basename($_FILES['image']['name']);
        $target_path = $upload_dir . $filename;

        if (move_uploaded_file($_FILES['image']['tmp_name'], $target_path)) {
            $image_path = $filename;
        } else {
            echo "画像アップロードに失敗しました。";
            exit;
        }
    }

    try {
        // データベース接続
        $pdo = new PDO($connect, USER, PASS);

        // アナウンスメッセージを保存
        $sql = $pdo->prepare("INSERT INTO admin_tournament_chat (chat, admin_id, created_at, tournament_id, round, image_path)  
                              VALUES (?, ?, NOW(), ?, ?, ?)");
        $sql->execute([$chat, $admin_id, $tournament_id, $round, $image_path]);

        // リダイレクト
        header("Location: admin_tournament_chat.php?tournament_id=$tournament_id&round=$round");
        exit();

    } catch (PDOException $e) {
        echo "エラー: " . $e->getMessage();
    }
} else {
    echo "エラー: メッセージが送信されていません。";
}
?>
