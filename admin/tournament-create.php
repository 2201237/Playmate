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
        <a href="tournament.php" class="back">戻る</a>
    </div>

    <div class="element_wrap">
        <label>大会名</label>
        <textarea name="tournament_name"></textarea>
    </div>

    <form method="post" action="">
        <div class="element_wrap">
            <label>ゲームタイトル</label>
            <select name="game_name">
    <option value="">ゲームタイトルを選択</option>
    <?php
    // SQLでデータベースからゲームタイトルを取得
    $sql = "SELECT game_id, title FROM game"; // game_idとtitleを取得するように修正
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    
    // データをループで表示
    foreach($stmt as $row){
        echo '<option value="' . htmlspecialchars($row['game_id']) . '">'
             . htmlspecialchars($row['title']) . '</option>';
    }
    ?>
</select>
        </div>

        <div class="element_wrap">
            <label>大会詳細</label>
            <textarea name="description"></textarea>
        </div>

        <button type="submit">作成</button>
    </form>
</body>
</html>
