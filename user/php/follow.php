<?php

require 'header_profile.php';
$pdo = new PDO($connect, USER, PASS);

// 自身のユーザーIDの取得
$userId = $_SESSION['User']['user_id'];

// フォロー解除のリクエストがあるか確認
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['unfollow_user_id'])) {
    $unfollowUserId = $_POST['unfollow_user_id'];

    // フォロー解除のSQL
    $deleteSql = "DELETE FROM follows WHERE follow_id = :user_id AND follows_id = :unfollow_user_id";
    $deleteStmt = $pdo->prepare($deleteSql);

    // プレースホルダーに値をバインド
    $deleteStmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
    $deleteStmt->bindParam(':unfollow_user_id', $unfollowUserId, PDO::PARAM_INT);

    // SQL実行
    $deleteStmt->execute();

    echo 'フォローを解除しました。<br>';
}

// フォローしているユーザーの取得
$sql = 'SELECT u.user_id, u.user_name, u.user_mail, u.profile, u.icon
        FROM follows AS f
        JOIN users AS u ON f.follows_id = u.user_id
        WHERE f.follow_id = :user_id';
$stmt = $pdo->prepare($sql);

// プレースホルダーに値をバインド
$stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
$stmt->execute();

$followUsers = $stmt->fetchAll(PDO::FETCH_ASSOC);

<<<<<<< HEAD
if (count($followUsers) > 0) {
    foreach ($followUsers as $user) {
        $iconPath = $user['icon'];
        if (isset($iconPath) && $iconPath !== '') {
            echo "<img src='".$iconPath."' class='icon_user' width='50' height='50'>";
        } else {
            echo "<img src='../img/icon_user.png' class='icon_user' width='50' height='50'>";
=======
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
    if (count($followUsers) > 0) {
        foreach ($followUsers as $user) {
            echo '<div class = user-info>';
                echo '<a href="profile-partner.php?user_id='. $user["user_id"] . '"></a>';
                $iconPath = isset($user['icon']) ? 'https://aso2201222.kill.jp/'.$user['icon'] : '';
                if (isset($iconPath) && $iconPath !== '') {
                    echo "<input type = 'hidden' name = '" . $iconPath . "' value = '" . $iconPath . "'></input>";
    
                    echo "<img src='".$iconPath."' class='icon_user' width='50' height='50'>";
                } else {
                    echo "<img src='../img/icon_user.png' class='icon_user' width='50' height='50'>";
                }
    
    

                echo 'User Name: ' . $user['user_name'];
                echo '<form action="chat.php" method="post">';
                    echo '<button type="submit" class="button">チャット</button>';
                echo '</form>';
                
            
                echo '
                <form method="post" action="">
                    <input type="hidden" name="unfollow_user_id" value="' . $user['user_id'] . '">
                    <button type="submit" class="button">フォロー解除</button>
                </form>';
            echo '</div>';
>>>>>>> ad1cf0bf8e3e11dd237b8ad92cc3d8e2b01cd933
        }

        echo 'User Name: ' . $user['user_name'];
        echo '<a href = "#" method = "post">チャット</a>';
    
        echo '
        <form method="post" action="">
            <input type="hidden" name="unfollow_user_id" value="' . $user['user_id'] . '">
            <button type="submit">フォロー解除</button>
        </form>';
        echo '<hr>';
    }
} else {
    echo 'フォローしている人がいません。';
}

?>
