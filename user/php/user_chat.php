<?php
session_start();
require 'db-connect.php';

// エラーメッセージを表示
ini_set('display_errors', 1);
error_reporting(E_ALL);

// ユーザーIDがセッションに設定されているか確認
if (!isset($_SESSION['User']['user_id'])) {
    header('Location: login-input.php');
    exit;
}

try {
    // データベース接続
    $pdo = new PDO($connect, USER, PASS, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);

    // 現在のユーザーIDを取得
    $UserId = $_SESSION['User']['user_id'];

    // GETリクエストから相手のユーザーIDを取得
    if (!isset($_GET['user_id'])) {
        throw new Exception("相手のユーザーIDが指定されていません。");
    }
    $partnerId = (int)$_GET['user_id'];

    // 相手のユーザー情報を取得
    $stmt = $pdo->prepare('SELECT user_name, icon FROM users WHERE user_id = ?');
    $stmt->execute([$partnerId]);
    $partnerInfo = $stmt->fetch();
    if (!$partnerInfo) {
        throw new Exception("相手のユーザー情報が見つかりません。");
    }

    // POSTされたチャットメッセージを処理
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['chat'])) {
        $chat_message = trim($_POST['chat']);

        // チャットメッセージをデータベースに挿入
        $stmt = $pdo->prepare("
            INSERT INTO users_chat (chat, is_read, user_id1, created_at, user_id2)
            VALUES (:chat, 0, :user_id1, NOW(), :user_id2)
        ");
        $stmt->execute([
            ':chat' => $chat_message,
            ':user_id1' => $UserId,
            ':user_id2' => $partnerId,
        ]);

        // ページをリロードしてチャットを更新
        header("Location: " . $_SERVER['REQUEST_URI']);
        exit;
    }

    // チャットメッセージを取得
    $stmt = $pdo->prepare("
        SELECT 
            c.chat, 
            c.created_at, 
            u.user_id, 
            u.user_name, 
            u.icon 
        FROM 
            users_chat AS c
        JOIN 
            users AS u ON c.user_id1 = u.user_id
        WHERE 
            (c.user_id1 = :user_id1 AND c.user_id2 = :user_id2)
            OR (c.user_id1 = :user_id2 AND c.user_id2 = :user_id1)
        ORDER BY 
            c.created_at ASC
    ");
    $stmt->execute([
        ':user_id1' => $UserId,
        ':user_id2' => $partnerId,
    ]);
    $chats = $stmt->fetchAll();

} catch (PDOException $e) {
    echo "データベースエラー: " . $e->getMessage();
    exit;
} catch (Exception $e) {
    echo "エラー: " . $e->getMessage();
    exit;
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/user_chat.css">
    <title>PlayMate - チャットボード</title>
</head>
<body>
    <!-- 上部に相手の情報を表示 -->
    <div class="user-header">
        <a href="profile-partner.php?user_id=<?= ($partnerId); ?>">
            <img src="<?= ($partnerInfo['icon'] ?: '../img/icon_user.png'); ?>" 
                 class="icon_user" width="50" height="50" alt="Profile Icon">
        </a>
        <span><?= ($partnerInfo['user_name']); ?></span>
    </div>

    <!-- チャットメッセージ表示 -->
    <div class="chat-container">
        <?php foreach ($chats as $chat): ?>
            <div class="chat-message">
                <div class="user-info">
                    <?php if ($chat['user_id'] == $UserId): ?>
                        <!-- 自分のメッセージ -->
                        <div class="my_chat">
                            <span>あなた: </span>
                            <img src="<?= ($chat['icon']); ?>" 
                                 class="icon_user" width="50" height="50" alt="Profile Icon">
                            <div class="chat-box">
                                <p><?= ($chat['chat']); ?></p>
                                <div class="chat-time"><?= ($chat['created_at']); ?></div>
                            </div>
                        </div>
                    <?php else: ?>
                        <!-- 相手のメッセージ -->
                        <div class="you_chat">
                            <a href="profile-partner.php?user_id=<?= ($chat['user_id']); ?>">
                                <img src="<?= ($chat['icon'] ?: '../img/icon_user.png'); ?>" 
                                     class="icon_user" width="50" height="50" alt="Profile Icon">
                            </a>
                            <span><?= ($chat['user_name']); ?>:</span>
                            <div class="chat-box">
                                <p><?= ($chat['chat']); ?></p>
                                <div class="chat-time"><?= ($chat['created_at']); ?></div>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- チャット送信フォーム -->
    <form method="POST" action="">
        <label for="chat">新しいメッセージ</label>
        <textarea name="chat" id="chat" rows="5" required></textarea>
        <br><br>
        <button type="submit">送信</button>
    </form>
    <form action="home.php" method="POST">
        <button type="submit">ホームに戻る</button>
    </form>
</body>
</html>
