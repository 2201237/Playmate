<?php require 'db-connect.php'?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="css/user_block.css">
    <title>PlayMate Admin</title>                               
</head>
<body>
    <div class="list_field">
    <a href="#" onclick="history.back()" return false; class = "back1">◀戻る</a>
    <h1>BANユーザー</h1>
    <a href="login.php" class="logout1">ログアウト</a>
    <form action="user_manage.php" method="post" id="submit_form">
    <input type="text" name="keyword" placeholder="キーワードを検索" class = "searchBox">
    <button type="submit" class="kennsaku">検索</button>
    <div class="grouptable">
    <table align="center" border="1">
        <tr>
            <th>メールアドレス</th>
            <th>ユーザー名</th>
            <th>自己紹介</th>
            <th></th>
        </tr>
<?php
$pdo = new PDO($connect, USER, PASS);
$sql = $pdo->query('SELECT * FROM users');
foreach($sql as $row){

    echo '<tr>';
    echo '<td>', $row['user_mail'], '</td>';
    echo '<td>', $row['user_name'], '</td>';
    echo '<td>', $row['profile'], '</td>';
    echo '</tr>';
}

?>
</table>
</div>
</div>
</body>
</html>