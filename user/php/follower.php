<?php
    session_start();
    require 'db-connect.php';


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
    <link rel="stylesheet" href="../css/header.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

    <link rel="stylesheet" type="text/css" href="../css/follow.css">
    <link rel="stylesheet" type="text/css" href="../css/style.css">

    <title>Document</title>
</head>
<body>
<?php require 'header.php'; ?>

    <div class = "headline" >フォロワー一覧</div>
    <div class = "follow-container">


<?php
    if (count($followerUsers) > 0) {
        foreach ($followerUsers as $user) {
            echo '<div class = user-info>';
                echo '<a href="profile-partner.php?user_id='. $user["user_id"] . '" class = "follow_id"></a>';
                $iconPath = $user['icon'];
                echo "<input type = 'hidden' name = '" . $iconPath . "' value = '" . $iconPath . "'></input>";

                echo "<img src='".$iconPath."' class='follow-icon' width='50' height='50'>";

    

                echo "<div class = 'follow-name'>" . $user['user_name'] . "</div>";
                echo "<div class = 'follow-form'>";
                    echo "<form action='user_chat.php?user_id=". $user['user_id']. "' class = 'user_c' method='post'>";
                        echo '<button type="submit" class="follow-button">チャット</button>';
                    echo '</form>';
                echo "</div>";
                
            
            echo '</div>';
        }
    } else {
        echo 'フォロワーがいません。';
    }
?>
    </div>
    <script src="../js/header.js"></script>

</body>
</html>
