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
            <select name="tournament">
                <?php
                    // PDOの接続
                    try {
                        $pdo = new PDO($connect, USER, PASS);
                        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                        
                        // SQLクエリの実行
                        $sql = $pdo->query('SELECT * FROM tournament');
                        foreach ($sql as $row) {
                            echo '<option value="', htmlspecialchars($row['tournament']), '">',
                                 htmlspecialchars($row['tournament_name']),
                                 '</option>';
                        }
                    } catch (PDOException $e) {
                        echo '接続に失敗しました: ' . $e->getMessage();
                    }
                ?>
            </select>
        </form>
    </div>
</body>
</html>

