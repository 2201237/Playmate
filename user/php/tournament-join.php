<?php
session_start();
require 'db-connect.php';

$pdo = new PDO($connect, USER, PASS);

// tournament_idの存在確認（POSTまたはGETパラメータで取得可能にする）
$tournament_id = $_POST['tournament_id'] ?? $_GET['tournament_id'] ?? null;
if (!$tournament_id) {
    echo "<script>alert('エラー: tournament_idが指定されていません。'); history.back();</script>";
    exit();
}

// ユーザーのログイン確認
if (!isset($_SESSION['User']['user_id'])) {
    echo "<script>alert('エラー: ユーザーがログインしていません。'); history.back();</script>";
    exit();
}

$user_id = $_SESSION['User']['user_id'];

try {
    // 締切時間の取得
    $deadline_sql = $pdo->prepare("SELECT tournament_deadline FROM tournament WHERE tournament_id = ?");
    $deadline_sql->execute([$tournament_id]);
    $tournament = $deadline_sql->fetch();

    if (!$tournament) {
        echo "<script>alert('エラー: 大会情報が見つかりません。'); history.back();</script>";
        exit();
    }

    // 締切時間を現在時刻と比較
    $current_time = new DateTime();
    $deadline_time = new DateTime($tournament['tournament_deadline']);
    if ($current_time > $deadline_time) {
        echo "<script>alert('エラー: 締切時間を過ぎているため、大会に参加できません。'); history.back();</script>";
        exit();
    }

    // すでに参加しているか確認するクエリ
    $check_sql = $pdo->prepare("SELECT COUNT(*) FROM tournament_member WHERE tournament_id = ? AND user_id = ?");
    $check_sql->execute([$tournament_id, $user_id]);
    $is_already_joined = $check_sql->fetchColumn();

    if ($is_already_joined > 0) {
        echo "<script>alert('エラー: すでにこの大会に参加しています。'); history.back();</script>";
        exit();
    }

    // 新規参加登録
    $sql = $pdo->prepare("INSERT INTO tournament_member (tournament_id, user_id, loser) VALUES (?, ?, 0)");
    $sql->execute([$tournament_id, $user_id]);

    // 参加後、詳細ページにリダイレクト
    header("Location: tournament-detail.php?tournament_id=$tournament_id");
    exit();
} catch (PDOException $e) {
    // PDO例外エラーをアラートで表示
    $errorMessage = htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8');
    echo "<script>alert('エラー: $errorMessage'); history.back();</script>";
    exit();
}
?>
