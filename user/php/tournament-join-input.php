<?php
session_start();
require 'db-connect.php';

$pdo = new PDO($connect, USER, PASS);

// POSTでtournament_idの存在確認
if (!isset($_POST['tournament_id'])) {
    die("エラー: tournament_idが指定されていません。");
}

if (!isset($_SESSION['User']['user_id'])) {
    die("エラー: ユーザーがログインしていません。");
}

$tournament_id = $_POST['tournament_id'];
$user_id = $_SESSION['User']['user_id'];

try {
    // すでに参加しているか確認するクエリ
    $check_sql = $pdo->prepare("SELECT COUNT(*) FROM tournament_member WHERE tournament_id = ? AND user_id = ?");
    $check_sql->execute([$tournament_id, $user_id]);
    $is_already_joined = $check_sql->fetchColumn();

    if ($is_already_joined > 0) {
        die("エラー: すでにこの大会に参加しています。");
    }

    // 参加していなければ、新規参加を登録
    $sql = $pdo->prepare("INSERT INTO tournament_member (tournament_id, user_id, loser) VALUES (?, ?, 0)");
    $sql->execute([$tournament_id, $user_id]);

    header("Location: tournament-join.php?tournament_id=$tournament_id");
    exit();
} catch (PDOException $e) {
    echo "エラー: " . $e->getMessage();
}
?>
