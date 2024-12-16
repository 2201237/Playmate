<?php
session_start(); 
require 'db-connect.php';

$pdo = new PDO($connect, USER, PASS);

$UserId = $_SESSION['User']['user_id'];

// 検索されたユーザー名を取得
if (isset($_GET['username'])) {
    $searchName = $_GET['username'];

    // ユーザー名を検索（部分一致）
    $stmt = $pdo->prepare('SELECT user_id, user_name, user_mail, profile, icon FROM users WHERE user_name LIKE ? and user_id not in (?)');
    $stmt->execute(['%' . $searchName . '%', $UserId]);
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
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

    <link rel="stylesheet" type="text/css" href="../css/search.css">
    <link rel="stylesheet" type="text/css" href="../css/style.css">


    <title>ユーザー検索結果</title>
</head>

<body>
<?php require 'header.php';?>

    <div class = "headline" >ユーザー検索結果</div>
    <div class="search-container">
        <?php 
        if (isset($message)){
            echo "<p>" .  $message . "</p>";
        }else{
            foreach ($users as $user){
                echo "<div class='user-info'>";
                echo "<a href='profile-partner.php?user_id=". $user['user_id']. "' class = 'search-id'></a>";

                $iconPath = $user['icon'];
                echo "<input type = 'hidden' name = '" . $iconPath . "' value = '" . $iconPath . "'></input>";
                echo "<img src='".$iconPath."' class='icon_user' width='50' height='50'>";

                echo "<div class = 'follow-name'>" . $user['user_name'] . "</div><br>";
                echo "<div class = 'follow-mail'>" . $user['user_mail'] . "</div>";
        
                echo "</div>";
                }
            }

            ?>
    </div>
    <script src="../js/header.js"></script>

</body>

</html>
