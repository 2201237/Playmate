<?php require 'db-connect.php'?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="css/user_manage.css">
    <title>PlayMate Admin</title>                               
</head>
<body>
    <div class="list_field">
    <a href="#" onclick="history.back()" return false; class = "back1">◀戻る</a>
    <h1>ユーザー一覧</h1>
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
if(isset($_POST['keyword'])){
    $sql = $pdo->prepare('SELECT * FROM users where (user_mail=? || user_name=? || profile=?) and user_id not in (select user_id from ban)');
    $sql->execute([$_POST['keyword'], $_POST['keyword'], $_POST['keyword']]);
    foreach($sql as $row){
        echo '<tr>';
        echo '<td>', $row['user_mail'], '</td>';
        echo '<td>', $row['user_name'], '</td>';
        echo '<td>', $row['profile'], '</td>';
        echo '<td>';
        echo '<div class="ban"><a href="user_block.php?id=', $row['user_id'],'"><button type="button">BAN</button></a></div>';
        echo '</td>';
        echo '</tr>';
    }
}else{
    $sql = $pdo->query('SELECT * FROM users where user_id not in (select user_id from ban)');
    foreach($sql as $row){

    echo '<tr>';
    echo '<td>', $row['user_mail'], '</td>';
    echo '<td>', $row['user_name'], '</td>';
    echo '<td>', $row['profile'], '</td>';
    echo '<td>';
        echo '<div class="ban"><a href="user_block.php?id=', $row['user_id'],'"><button type="button">BAN</button></a></div>';
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