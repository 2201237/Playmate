<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/game-manage.css">
    <title>大会管理</title>

</head>
<body>
    <a href="admintop.php" class="back">←戻る</a>
    <a href="login.php" class="logout">ログアウト</a>

    <h2>大会管理</h2>

    <div>
    <button class="menu-button" onclick="location.href='tournament-results.php'">大会戦績管理</button><br>
        <button class="menu-button" onclick="location.href='win-loss-images.php'">勝敗画像管理</button><br>
        <button class="menu-button" onclick="location.href='tournament-create.php'">大会作成</button><br>
        <button class="menu-button" onclick="location.href='tournament-edit.php'">大会削除・更新</button><br>
        <button class="menu-button" onclick="location.href='tournament-view.php'">大会一覧</button>
    </div>
</body>
</html>