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
        <!-- 大会名の表示 -->
        <h3>大会名</h3>
        <ul>
            <?php
                // データベース接続情報
                $connect = 'mysql:host=mysql311.phy.lolipop.lan;dbname=LAA1516826-playmate;charset=utf8';
                $USER = 'LAA1516826';
                $PASS = 'joyman';

                try {
                    $pdo = new PDO($connect, $USER, $PASS);
                    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                    // データベースから大会名を取得して表示
                    $tournament_sql = $pdo->query('SELECT tournament_name FROM tournament');
                    foreach ($tournament_sql as $tournament) {
                        echo '<li>', htmlspecialchars($tournament['tournament_name'], ENT_QUOTES, 'UTF-8'), '</li>';
                    }
                } catch (PDOException $e) {
                    echo 'データベース接続に失敗しました: ' . $e->getMessage();
                }
            ?>
        </ul>
    </div>
</body>
</html>
