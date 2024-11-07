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
        <form action="your_action_page.php" method="post">
                <?php
                    $pdo = new PDO($connect, USER, PASS);
                    $sql = $pdo->query('SELECT * FROM tournament');
                    foreach ($sql as $row) {
                        echo '<option value="', $row['tournament_name'], '">', '</option>';
                    }
                ?>
            </select>
</body>
</html>
