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
    <div class="back">戻る</div>
    <div class="create">作成</div>
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
    <textarea name="contact"></textarea>
    
</form>
</body>
</html>