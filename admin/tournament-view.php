<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/tournament-view.css">
    <title>大会管理</title>
</head>
<body>
    <a href="tournament.php" class="back">←戻る</a>
    <a href="login.php" class="logout">ログアウト</a>

    <h2>大会一覧</h2>
    <?php
require 'db-connect.php';

// データベースからデータを取得するクエリを作成
$sql = "SELECT tournament_name FROM tournament";
$result = $conn->query($sql);

// 結果が1行以上あるかを確認
if ($result->num_rows > 0) {
    // 結果をループして表示
    while ($row = $result->fetch_assoc()) {
        echo $row['tournament_name'] . "<br>";
    }
} else {
    echo "データがありません。";
}

// 接続を閉じる
$conn->close();
?>
</body>
</html>
