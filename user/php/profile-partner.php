<?php

require 'header_profile.php';
$pdo = new PDO($connect, USER, PASS);

// 現在のログインユーザーのID
$UserId = (int)$_SESSION['User']['user_id'];

// フォロー解除のリクエストがあるか確認
// if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['unfollow_user_id'])) {
//     $unfollowUserId = (int)$_POST['unfollow_user_id']; // キャストで安全に処理

//     // フォロー解除のSQL
//     $deleteStmt = $pdo->prepare('delete from follows where follow_id=? and follower_id=?');
//     $deleteStmt->execute([$unfollowUserId, $UserId]);
//     // プレースホルダーに値をバインド
//     $deleteStmt->bindParam(':current_user_id', $UserId, PDO::PARAM_INT);
//     $deleteStmt->bindParam(':unfollow_user_id', $unfollowUserId, PDO::PARAM_INT);

//     // SQL実行
//     echo "<script>alert('フォローを解除しました！');</script>";
// }
// クエリパラメータから相手のユーザーIDを取得
if (isset($_GET['user_id'])) {
    $targetUserId = (int)$_GET['user_id'];

    // 相手のプロフィール情報を取得
    $stmt = $pdo->prepare('SELECT user_id, user_name, user_mail, profile, icon FROM users WHERE user_id = ?');
    $stmt->execute([$targetUserId]);
    $targetUser = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$targetUser) {
        echo "指定されたユーザーは存在しません。";
        exit();
    }

    // プロフィール情報を変数に格納
    $userId = $targetUser['user_id'];
    $userIcon = $targetUser['icon'] ? 'https://aso2201222.kill.jp/'.$targetUser['icon'] : '../img/icon_user.png';
    $userName = $targetUser['user_name'];
    $userProfile = $targetUser['profile'] ? $targetUser['profile'] : 'プロフィールは未設定です。';
    $userMail = $targetUser['user_mail'];

    // フォロー済みかチェック
    $followStmt = $pdo->prepare('SELECT COUNT(*) FROM follows WHERE follow_id = ? AND follower_id = ?');
    $followStmt->execute([$targetUserId,$UserId]);
    $isFollowing = $followStmt->fetchColumn() > 0;

    // フォローリクエスト処理
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['follow_user_id']) && !$isFollowing) {
            // フォロー処理
            $insertStmt = $pdo->prepare('INSERT INTO follows (follow_id,follower_id) VALUES (?, ?)');
            $insertStmt->execute([$targetUserId,$UserId]);

            $isFollowing = true;


        }

        if (isset($_POST['unfollow_user_id']) && $isFollowing) {
            // フォロー解除処理
            $deleteStmt = $pdo->prepare('DELETE FROM follows WHERE follow_id=? and follower_id=?');
            $deleteStmt->execute([$targetUserId,$UserId]);


            $isFollowing = false;


        }

        // フォロー状態を再確認
        $followStmt->execute([ $targetUserId,$UserId]);
        $isFollowing = $followStmt->fetchColumn() > 0;
    }
} else {
    echo "ユーザーIDが指定されていません。";
    exit();
}

?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="../css/profile-input.css">
    <title>相手のプロフィール</title>
</head>

<body>
    <div class="profile-container">
        <a href="#" onclick="window.history.back(); return false;">←</a><br>
        <div class="profile-content">
            <p class="profile-icon">
                <img src="<?php echo $userIcon; ?>" class="icon_user" width="50" height="50" alt="Profile Icon">
            </p>
            <p class="user-name"><?php echo $userName; ?></p>
            <p class="user-mail"><?php echo $userMail; ?></p>
            <p class="profile-p">自己紹介</p>
            <textarea rows="4" cols="50" class="profile-area" readonly><?php echo $userProfile; ?></textarea>
        </div>

        <div class="profile-actions">
            <!-- チャットリンク -->
            <a href="chat.php?user_id=<?php echo $userId; ?>" class="chat-button">✉</a>

            <!-- フォローボタンおよびフォロー解除ボタン -->
            <?php 
            if ($isFollowing) {
                echo '<form method="post" action="">';
                    echo '<input type="hidden" name="unfollow_user_id" value="' . $userId . '">';
                    echo '<button type="submit" class="follow-button">フォロー解除</button>';
                echo '</form>';
            } else {
                echo '<form method="post" action="">';
                    echo '<input type="hidden" name="follow_user_id" value="' . $userId . '">';
                    echo '<button type="submit" class="follow-button">フォローする</button>';
                echo '</form>';
            }
            ?>
        </div>
    </div>
</body>
</html>
