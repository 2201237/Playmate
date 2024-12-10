<?php
session_start();
require 'db-connect.php';

if (!isset($_SESSION['User']['user_id'])) {
    die(json_encode(['error' => 'ログインしていません。']));
}

$user_id = $_SESSION['User']['user_id'];

if (isset($_GET['tournament_id']) && isset($_GET['round'])) {
    $tournament_id = $_GET['tournament_id'];
    $round = $_GET['round'];

    try {
        $pdo = new PDO($connect, USER, PASS);

        // チャットメッセージを取得するSQLを修正
        if ($round != 0) {
            // roundが0以外の場合、ログインユーザーとその対戦相手のメッセージのみ取得
            $stmt = $pdo->prepare('
                SELECT m.*, u.user_name 
                FROM tournament_chat m
                JOIN users u ON m.user_id = u.user_id
                WHERE m.tournament_id = ? AND m.round = ? AND (m.user_id = ? OR m.user_id = ?)
                ORDER BY m.created_at ASC
            ');
            $stmt->execute([$tournament_id, $round, $user_id, $opponent_id]);
        } else {
            // roundが0の場合は全てのチャットメッセージを取得
            $stmt = $pdo->prepare('
                SELECT m.*, u.user_name 
                FROM tournament_chat m
                JOIN users u ON m.user_id = u.user_id
                WHERE m.tournament_id = ? AND m.round = ?
                ORDER BY m.created_at ASC
            ');
            $stmt->execute([$tournament_id, $round]);
        }

        $messages = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode($messages);
    } catch (PDOException $e) {
        echo json_encode(['error' => $e->getMessage()]);
    }
} else {
    echo json_encode(['error' => '大会IDまたは回戦が指定されていません。']);
}
?>
