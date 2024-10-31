<?php require 'db-connect.php'?>
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
    <div href="tournament.php" class="back">戻る</div>
    <div href="admintop.php" class="create">作成</div>
</div>
    <div class="element_wrap">
        <label>大会名</label>
        <textarea name="contact"></textarea>
    </div>
    <form method="post" action="">
    <select name="item_id">
        <option value="">ゲームタイトル</option>
        <?php foreach ($items as $item): ?>
            <option value="<?php echo htmlspecialchars($item['id']); ?>">
                <?php echo htmlspecialchars($item['name']); ?>
            </option>
        <?php endforeach; ?>
    </select>
    <?php
    echo '<tr>';
    echo '<form action="title_change.php?id=', $row['title'], '" method="post">';
    echo '<td><input type="text" name="title" value="', $row['title'], '"></td>';
    $sql2 = $pdo->prepare('SELECT * FROM game where game_id=?');
    $sql2->execute([$row['game_id']]);
    foreach($sql2 as $row2){
        echo '<td>', $row2['game_name'], '</td>';
    }
    echo '<td><button type ="submit">更新</button></td>';
    echo '</form>';
    echo '</tr>';
    ?>
    <textarea name="contact"></textarea>
    
</form>
</body>
</html>