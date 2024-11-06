<?php require 'db-connect.php'; ?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/tournament-view.css">
    <title>大会管理</title>

</head>
<body>
    <a href="admintop.php" class="back">←戻る</a>
    <a href="login.php" class="logout">ログアウト</a>

    <h2>大会一覧</h2>
    <?php

try {
    // tournamentテーブルからtournament_nameを取得
    $stmt = $pdo->query('SELECT tournament_name FROM tournament');

    // 結果を表示
    echo "<ul>";
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "<li>" . htmlspecialchars($row['tournament_name']) . "</li>";
    }
    echo "</ul>";

} catch (PDOException $e) {
    echo 'データ取得エラー: ' . $e->getMessage();
}
?>

</body>
</html>