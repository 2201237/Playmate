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
<div class="game-title-dropdown">
    <form action="your_action_page.php" method="post">
    <input type="text" name="tournament-name" value="">
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
            <button type="submit">作成</button>
        </form>
    </div>
    <div class="container">
        <a href="tournament.php" class="back">戻る</a>
    </div>

    <div class="element_wrap">
        <label>大会名</label>
        <textarea name="tournament_name"></textarea>
    </div>
</body>
</html>