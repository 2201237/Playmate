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
<div class="add">
        <form action="Admin_Groupadd.php" method="post">
            <input type="text" name="name" class="r1">
            <select name="genre2_id" class="genre2">
            <?php
                $pdo = new PDO($connect, USER, PASS);
                $sql3 = $pdo->query('select * from Genre2');
                foreach($sql3 as $row3){
                    echo '<option value="', $row3['genre2_id'], '">', $row3['genre2_name'], '</option>';
                }
            ?>
            </select>
            <button type="submit" class="r2">追加</button>
        </form>
    </div>
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

    ?>
                <button type="submit">作成</button>
</select>
        </div>

    </form>
</body>
</html>
