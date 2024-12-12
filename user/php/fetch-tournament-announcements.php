<?php
session_start();
require 'db-connect.php';

// ログインチェック
if (!isset($_SESSION['User']['user_id'])) {
    header('Content-Type: application/json');
    echo json_encode(['error' => 'ログインしていません。']);
    exit;
}

$user_id = $_SESSION['User']['user_id'];

// GETパラメータ取得
if (isset($_GET['tournament_id']) && isset($_GET['round'])) {
    $tournament_id = $_GET['tournament_id'];
    $round = $_GET['round'];

    try {
        $pdo = new PDO($connect, USER, PASS);

        // アナウンスを取得（user_nameをadmin_nameに修正）
        $stmt = $pdo->prepare('
            SELECT a.*, u.admin_name 
            FROM admin_tournament_chat a
            JOIN admins u ON a.admin_id = u.admin_id
            WHERE a.tournament_id = ? AND a.round = ?
            ORDER BY a.created_at ASC
        ');
        $stmt->execute([$tournament_id, $round]);
        $announcements = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // JSON形式で出力
        header('Content-Type: application/json');
        echo json_encode($announcements);
    } catch (PDOException $e) {
        header('Content-Type: application/json');
        echo json_encode(['error' => $e->getMessage()]);
    }
} else {
    header('Content-Type: application/json');
    echo json_encode(['error' => '大会IDまたは回戦が指定されていません。']);
}
?>
