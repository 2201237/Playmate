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
    
    <h2>大会参加者一覧</h2>
    <div class="container">
        <h1>大会名</h1>
        <ul>
            <?php
                // データベース接続情報
                $connect = 'mysql:host=mysql311.phy.lolipop.lan;dbname=LAA1516826-playmate;charset=utf8';
                $USER = 'LAA1516826';
                $PASS = 'joyman';
                
                try {
                    $pdo = new PDO($connect, $USER, $PASS);
                    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                    
                    // 大会名と参加人数を取得するSQL
                    $sql = "SELECT t.tournament_name, COUNT(DISTINCT tm.user_id) as participant_count 
                           FROM tournament t 
                           LEFT JOIN tournament_member tm ON t.tournament_id = tm.tournament_id 
                           GROUP BY t.tournament_id, t.tournament_name";
                    
                    $stmt = $pdo->query($sql);
                    
                    foreach ($stmt as $row) {
                        echo '<li>';
                        echo htmlspecialchars($row['tournament_name'], ENT_QUOTES, 'UTF-8');
                        echo ' (参加人数: ' . $row['participant_count'] . '人)';//参加人数がまだ反映されていません
                        echo '</li>';
                    }
                } catch (PDOException $e) {
                    echo 'データベース接続に失敗しました: ' . $e->getMessage();
                }
            ?>
        </ul>
    </div>
</body>
</html>