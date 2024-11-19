<?php

require '../header_profile.php';
$pdo = new PDO($connect, USER, PASS);

// 自身のユーザーIDの取得
$userId = $_SESSION['User']['user_id'];

// フォロー解除のリクエストがあるか確認
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['unfollow_user_id'])) {
    $unfollowUserId = $_POST['unfollow_user_id'];

    // フォロー解除のSQL
    // $deleteSql = "DELETE FROM follows WHERE follow_id = ? AND follower_id = ?";
    $deleteStmt = $pdo->prepare('delete from follows where follow_id=? and follower_id=?');
    $deleteStmt->execute([$unfollowUserId, $userId]);

    // var_dump(["user_id"]);
    // var_dump(["unfollow_user_id"]);
    
    // プレースホルダーに値をバインド
    // $deleteStmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
    // $deleteStmt->bindParam(':unfollow_user_id', $unfollowUserId, PDO::PARAM_INT);

    // SQL実行
    // $deleteStmt->execute();

    echo 'フォローを解除しました。<br>';
}

// フォローしているユーザーの取得
$sql = 'SELECT u.user_id, u.user_name, u.user_mail, u.profile, u.icon
        FROM follows AS f
        JOIN users AS u ON f.follow_id = u.user_id
        WHERE f.follower_id = :user_id';
$stmt = $pdo->prepare($sql);

// プレースホルダーに値をバインド
$stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
$stmt->execute();

$followUsers = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (count($followUsers) > 0) {
    foreach ($followUsers as $user) {
        echo '<a href="profile-partner.php?user_id='. $user["user_id"] . '">';
        $iconPath = $user['icon'];
        if (isset($iconPath) && $iconPath !== '') {
            echo "<img src='".$iconPath."' class='icon_user' width='50' height='50'>";
        } else {
            echo "<img src='../img/icon_user.png' class='icon_user' width='50' height='50'>";
        }
        echo '</a>';


        echo 'User Name: ' . $user['user_name'];
        echo '<a href = "chat.php" method = "post">チャット</a>';
    
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
