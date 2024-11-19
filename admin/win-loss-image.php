<?php require 'db-connect.php'?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="css/WL_image.css">
    <title>PlayMate Admin</title>                               
</head>
<body>
    <div class="list_field">
    <a href="#" onclick="history.back()" return false; class = "back1">◀戻る</a>
    <h1>勝敗画像</h1>
    <a href="login.php" class="logout1">ログアウト</a>
    <form action="user_manage.php" method="post" id="submit_form">
    <input type="text" name="keyword" placeholder="キーワードを検索" class = "searchBox">
    <button type="submit" class="kennsaku">検索</button>
    <div class="grouptable">
    <table align="center" border="1">
        <tr>
            <th>ユーザー名</th>
            <th>ゲーム名</th>
            <th>勝敗画像</th>
        </tr>
<?php
$pdo = new PDO($connect, USER, PASS);
if(isset($_POST['keyword'])){
    $sql = $pdo->prepare('SELECT * FROM users where (user_mail=? || user_name=? || profile=?)');
    $sql->execute([$_POST['keyword'], $_POST['keyword'], $_POST['keyword']]);
    foreach($sql as $row){
        echo '<tr>';
        echo '<td>', $row['user_mail'], '</td>';
        echo '<td>', $row['user_name'], '</td>';
        echo '<td>', $row['profile'], '</td>';
        echo '</tr>';
    }
}else{
    $sql = $pdo->query('SELECT * FROM users where user_id');
    foreach($sql as $row){

    echo '<tr>';
    echo '<td>', $row['user_mail'], '</td>';
    echo '<td>', $row['user_name'], '</td>';
    echo '<td>', $row['profile'], '</td>';
    echo '</tr>';
    }
}

?>
</table>
</div>
</div>
</body>
</html>