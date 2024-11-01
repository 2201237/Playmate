<?php require 'db-connect.php'?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="css/title_change.css">
    <title>PlayMate Admin</title>                               
</head>
<body>
    <div class="list_field">
    <a href="#" onclick="history.back()" return false; class = "back1">◀戻る</a>
    <h1>ゲームタイトル変更</h1>
    <a href="login.php" class="logout1">ログアウト</a>
    <form action="user_manage.php" method="post" id="submit_form">
    <input type="text" name="keyword" placeholder="キーワードを検索" class = "searchBox">
    <button type="submit" class="kennsaku">検索</button>
    <div class="grouptable">
    <table align="center" border="1">
        <tr>
            <th>ゲームタイトル</th>
            <th></th>
        </tr>
        <?php
$pdo = new PDO($connect, USER, PASS);
$sql = $pdo->query('SELECT * FROM game');
foreach($sql as $row){

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
}

?>
</table>
</div>
</div>
</body>
</html>