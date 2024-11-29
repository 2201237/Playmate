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
    <a href="user.php" return false; class = "back">←戻る</a>
    <h1>ユーザー一覧</h1>
    <a href="login.php" class="logout">ログアウト</a>
    <form method="GET" action="user_manage.php" class="search-form">
    <input type="text" name="user_name" placeholder="キーワード検索">
    <button type="submit">検索</button>
    </form>
    <button onclick="location.href='user_block.php'" class="add">BANユーザーへ</button>
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

if(isset($_GET['id'])){
    $sql=$pdo->prepare('DELETE FROM ban WHERE user_id = ?');
    $sql->execute([$_GET['id']]);
}

if(!empty($_GET['user_name'])){
    $sql = $pdo->prepare('SELECT * FROM users where (user_mail=? || user_name=? || profile=?) and user_id not in (select user_id from ban)');
    $sql->execute([$_GET['user_name'], $_GET['user_name'], $_GET['user_name']]);
    foreach($sql as $row){
        echo '<tr>';
        echo '<td>', $row['user_mail'], '</td>';
        echo '<td>', $row['user_name'], '</td>';
        echo '<td>', $row['profile'], '</td>';
        echo '<td>';
        echo '<div class="BAN"><a href="user_block.php?id=', $row['user_id'],'"><button type="button">BAN</button></a></div>';
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
        echo '<div class="BAN"><a href="user_block.php?id=', $row['user_id'],'"><button type="button">BAN</button></a></div>';
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