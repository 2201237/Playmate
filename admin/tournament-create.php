<?php require 'db-connect.php'?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/game-manage.css">
    <title>大会管理</title>

</head>
<body>
    <a href="admintop.php" class="back">戻る</a>
    <a href="admintop.php" class="cteate">作成</a>
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
    <textarea name="contact"></textarea>
    
</form>
</body>
</html>