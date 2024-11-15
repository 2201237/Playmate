<?php

require '../header_profile.php';
$pdo = new PDO($connect, USER, PASS);
// 自身のユーザーIDの所得
$userId = $_SESSION['User']['user_id'];

 // フォロー解除のリクエストがあるか確認
 if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['unfollow_user_id'])) {
    $unfollowUserId = $_POST['unfollow_user_id'];

    // フォロー解除のSQL
    $deleteSql = "DELETE FROM follows WHERE follow_id, follows_id" ;
    $deleteStmt = $pdo->prepare($deleteSql);
    $deleteStmt->execute();

    echo 'フォローを解除しました。<br>';
}


//フォローしているユーザーのDBからの所得
$sql = 'SELECT u.user_id, u.user_name, u.user_mail, u.profile,u.icon
        FROM follows AS f
        JOIN users AS u ON f.follows_id = u.user_id
        WHERE f.follow_id = :user_id';
$stmt = $pdo->prepare($sql);
$stmt->execute();

$iconPath = $user['icon'];

$followUsers = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (count($followedUsers) > 0) {
    foreach ($followedUsers as $user) {
        if (isset($iconPath) && $iconPath !== '') {
            echo "<input type = 'hidden' name = '" . $iconPath . "' value = '" . $iconPath . "'></input>";

            echo "<img src='".$iconPath."' class='icon_user' width='50' height='50'>";
        } else {
            echo "<img src='../img/icon_user.png' class='icon_user' width='50' height='50'>";
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