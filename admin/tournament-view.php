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
    <div class="container">
        <!-- Display tournament names -->
        <h3>登録済み大会名</h3>
        <ul>
            <?php
                $pdo = new PDO($connect, USER, PASS);
                // Fetch and display tournament names
                $tournament_sql = $pdo->query('SELECT tournament_name FROM tournament');
                foreach ($tournament_sql as $tournament) {
                    echo '<li>', htmlspecialchars($tournament['tournament_name']), '</li>';
                }
            ?>
        </ul>
    </div>
</body>
</html>

