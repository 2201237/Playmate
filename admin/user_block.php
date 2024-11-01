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

// GETでめんばーIDを持ってくる
if(isset($_GET['id'])){
    $sql=$pdo->prepare('insert into ban(user_id) values (?)');
    $sql->execute([$_GET['id']]);
}
// member情報をテーブルから持ってくる
// 持ってきた情報をbanテーブルに入れる
$sql = $pdo->query('SELECT * FROM ban');
foreach($sql as $row){
    $sql2 = $pdo->prepare('SELECT * FROM users where user_id = ?');
    $sql2->execute([$row['user_id']]);
    foreach($sql2 as $row2){
        echo '<tr>';
        echo '<td>', $row2['user_mail'], '</td>';
        echo '<td>', $row2['user_name'], '</td>';
        echo '<td>', $row2['profile'], '</td>';
        echo '<td>';
        echo '<div class="unlock"><a href="user_manage.php?id=', $row['user_id'],'"><button type="button">解除</button></a></div>';
        echo '</td>';
        echo '</tr>';
    }
}

?>
</table>
</div>
</div>
</body>
</html>