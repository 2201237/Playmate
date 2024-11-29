<?php

require 'header_profile.php';
$pdo = new PDO($connect, USER, PASS);

// 自身のユーザーIDの取得
$userId = $_SESSION['User']['user_id'];


// フォローしているユーザーの取得
$sql = 'SELECT u.user_id, u.user_name, u.user_mail, u.profile, u.icon
        FROM follows AS f
        JOIN users AS u ON f.follower_id = u.user_id
        WHERE f.follow_id = :user_id';
$stmt = $pdo->prepare($sql);

// プレースホルダーに値をバインド
$stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
$stmt->execute();

$followerUsers = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="../css/follow.css">

    <title>Document</title>
</head>
<body>
    
<?php
    if (count($followerUsers) > 0) {
        foreach ($followerUsers as $user) {
            echo '<div class = user-info>';
                echo '<a href="profile-partner.php?user_id='. $user["user_id"] . '"></a>';
                $iconPath = isset($user['icon']) ? 'https://aso2201222.kill.jp/'.$user['icon'] : 'https://aso2201222.kill.jp/Playmate/user/img/icon_user.png';
                if (isset($iconPath) && $iconPath !== '') {
                    echo "<input type = 'hidden' name = '" . $iconPath . "' value = '" . $iconPath . "'></input>";
    
                    echo "<img src='".$iconPath."' class='icon_user' width='50' height='50'>";
                } else {
                    echo "<img src='https://aso2201222.kill.jp/Playmate/user/img/icon_user.png' class='icon_user' width='50' height='50'>";
                }
    
    

                echo 'User Name: ' . $user['user_name'];
                echo "<form action='user_chat.php?user_id=". $user['user_id']. "' class = 'user_c' method='post'>";
                    echo '<button type="submit" class="button">チャット</button>';
                echo '</form>';
                
            
            echo '</div>';
        }
    } else {
        echo 'フォロワーがいません。';
    }

?>
</body>
</html>
