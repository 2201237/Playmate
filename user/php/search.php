<?php
session_start(); 
require 'db-connect.php';

$pdo = new PDO($connect, USER, PASS);


// 検索されたユーザー名を取得
if (isset($_GET['username'])) {
    $searchName = $_GET['username'];

    // ユーザー名を検索（部分一致）
    $stmt = $pdo->prepare('SELECT user_id, user_name, user_mail, profile, icon FROM users WHERE user_name LIKE ?');
    $stmt->execute(['%' . $searchName . '%']);
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (empty($users)) {
        $message = "ユーザーが見つかりません。";
    }
} else {
    $message = "検索条件が指定されていません。";
}

?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/header.css">

    <link rel="stylesheet" type="text/css" href="../css/search.css">
    <title>ユーザー検索結果</title>
</head>

<body>
<?php require 'header.php';?>

    <div class="search-container">
        <a href="#" onclick="window.history.back(); return false;">←</a><br>
        <h1>ユーザー検索結果</h1>
        <?php 
        if (isset($message)){
            echo "<p>" .  $message . "</p>";
        }else{
            foreach ($users as $user){
                echo "<div class='user-info'>";
                echo "<a href='profile-partner.php?user_id=". $user['user_id']. "'></a>";

                if (isset($iconPath) && $iconPath !== '') {
                    echo "<input type = 'hidden' name = '" . $iconPath . "' value = '" . $iconPath . "'></input>";
    
                    echo "<img src='".$iconPath."' class='icon_user' width='50' height='50'>";
                } else {
                    echo "<img src='../img/icon_user.png' class='icon_user' width='50' height='50'>";
                }
                    echo "<strong>". $user['user_name'] . "</strong><br>";
                    echo $user['user_mail'] ;
                echo "</div>";
                }
            }

            ?>
    </div>
</body>

</html>