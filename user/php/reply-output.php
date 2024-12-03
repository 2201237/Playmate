<?php 
require 'db-connect.php';

$message = $_POST["message"] ?? ''; // 安全なデフォルト値を設定
$reply_id = $_POST['reply_id'] ?? ''; // 安全なデフォルト値を設定

try {
    // PDO接続を初期化
    $pdo = new PDO($connect, USER, PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // エラーの詳細を表示する設定

    // SQLクエリの準備と実行
    ?>
    <?php
    $stmt = $pdo->prepare('UPDATE contacts SET reply = ?, status = ? WHERE contacts_id = ?');
    $stmt->execute([$message,1, $reply_id]);

    // 処理が完了した後、アラートを表示し、リンク先にリダイレクト
    echo '<script>
    alert("返信が完了しました。");
    window.location.href = "https://aso2201226.tonkotsu.jp/GitHub/Playmate/user/php/infomation-reception.php";
    </script>';
} catch (PDOException $e) {
    // データベース接続やクエリ実行でエラーが発生した場合のエラーハンドリング
    echo '<script>
    alert("エラーが発生しました: ' . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8') . '");
    window.history.back(); // エラー時に前のページに戻る
    </script>';
}
?>

