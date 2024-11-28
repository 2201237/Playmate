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
    <a href="user.php" return false; class = "back">←戻る</a>
    <h1>BANユーザー</h1>
    <a href="login.php" class="logout">ログアウト</a>
    <form method="GET" action="user_block.php" class="search-form">
        <input type="text" name="user_name" placeholder="ユーザー検索" 
               value="<?= isset($_GET['user_name']) ? htmlspecialchars($_GET['user_name']) : '' ?>">
        <button type="submit">検索</button>
    </form>
    <button onclick="location.href='user_manage.php'" class="add">ユーザー一覧へ</button>
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

// GETでユーザーIDをBANテーブルに追加
if (isset($_GET['id'])) {
    $sql = $pdo->prepare('INSERT INTO ban(user_id) VALUES (?)');
    $sql->execute([$_GET['id']]);
}

// 検索条件を取得
$searchKeyword = isset($_GET['user_name']) ? '%' . $_GET['user_name'] . '%' : '%';

// BANされたユーザー情報を取得
$sql = $pdo->prepare(
    'SELECT users.user_mail, users.user_name, users.profile, ban.user_id 
     FROM ban 
     JOIN users ON ban.user_id = users.user_id 
     WHERE users.user_name LIKE ?'
);
$sql->execute([$searchKeyword]);

// テーブルにデータを出力
foreach ($sql as $row) {
    echo '<tr>';
    echo '<td>', htmlspecialchars($row['user_mail']), '</td>';
    echo '<td>', htmlspecialchars($row['user_name']), '</td>';
    echo '<td>', htmlspecialchars($row['profile']), '</td>';
    echo '<td>';
    echo '<div class="unlock"><a href="user_manage.php?id=', $row['user_id'], '"><button type="button">解除</button></a></div>';
    echo '</td>';
    echo '</tr>';
}
?>
    </table>
    </div>
    </div>
</body>
</html>
