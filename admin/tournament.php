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
        <button class="menu-button" onclick="location.href='tournament-view.php'">大会一覧</button><br>
        <button class="menu-button" onclick="location.href='tournament-create.php'">大会作成</button><br>
        <button class="menu-button" onclick="location.href='tournament-edit.php'">大会削除・更新</button><br>
        <button class="menu-button" onclick="location.href='tournament-bracket.php'">組み分け表</button><br>
        <button class="menu-button" onclick="location.href='tournament-sorting.php'">組み分け</button><br>
        <button class="menu-button" onclick="location.href='win-loss-image.php'">勝敗画像管理</button><br>
        <button class="menu-button" onclick="location.href='admin_tournament_chat.php'">大会アナウンス</button><br>
    </div>
</body>
</html>