<?php
require 'db-connect.php';
$pdo = new PDO($connect, USER, PASS);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

ob_start(); // 出力バッファリングを開始

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $contacts_id = intval($_POST["contacts_id"]);
    $status = intval($_POST["status"]);

    try {
        // contactsテーブルのステータスを更新
        $sql = "UPDATE contacts SET status = ? WHERE contacts_id = ?";
        $stmt = $pdo->prepare($sql);
        
        if ($stmt->execute([$status, $contacts_id])) {
            // 成功メッセージを一時的に保存し、リダイレクト先で表示する
            $_SESSION["status_message"] = "ステータスが更新されました。";
        } else {
            $_SESSION["status_message"] = "エラーが発生しました。";
        }
    } catch (PDOException $e) {
        $_SESSION["status_message"] = "エラー: " . $e->getMessage();
    }

    // 問い合わせ一覧ページへリダイレクト
    header("Location: inquiry-list.php");
    exit();
}

ob_end_flush(); // 出力バッファリングを終了して出力する
?>