<?php require 'db-connect.php'; ?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/tournament-create.css">
    <title>大会作成</title>
</head>
<body>
    <div class="container">
        <form action="your_action_page.php" method="post">
            <input type="text" name="tournament-name" placeholder="大会名">
            <label for="game_title">ゲームタイトルを選択:</label>
            <select name="game_title" id="game_title">
                <?php
                    $pdo = new PDO($connect, USER, PASS);
                    $sql = $pdo->query('SELECT * FROM game');
                    foreach ($sql as $row) {
                        echo '<option value="', $row['game_id'], '">', $row['title'], '</option>';
                    }
                ?>
            </select>
            <textarea id="story" name="story" placeholder="~ルール~"></textarea>
            <div class="button-group">
                <button type="button" onclick="history.back()">戻る</button>
                <button type="submit">作成</button>
            </div>
        </form>
    </div>
</body>
</html>
