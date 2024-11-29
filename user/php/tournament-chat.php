<?php
session_start();
require 'db-connect.php';
require 'header.php';

$pdo = new PDO($connect, USER, PASS);

// チャットメッセージを取得する処理
if (isset($_GET['tournament_id']) && isset($_GET['round'])) {
    $tournament_id = $_GET['tournament_id'];
    $round = $_GET['round'];

    // チャットメッセージの取得
    $message_stmt = $pdo->prepare('SELECT m.*, u.user_name FROM tournament_chat m JOIN users u ON m.user_id = u.user_id WHERE m.tournament_id = ? AND m.round = ? ORDER BY m.created_at ASC');
    $message_stmt->execute([$tournament_id, $round]);
    $messages = $message_stmt->fetchAll();
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/header.css">

    <title>大会チャット</title>
</head>
<body>
    <div>
    <h1>
        大会チャット（
        <?php echo $round == 0 ? '全体' : htmlspecialchars($round) . '回戦'; ?>
        ）
    </h1>
        <div id="chat-box">
            <?php foreach ($messages as $message): ?>
                <div class="message">
                    <strong><?php echo htmlspecialchars($message['user_name']); ?>:</strong>
                    <p><?php echo htmlspecialchars($message['chat']); ?></p>
                    <?php if (!empty($message['image_path'])): ?>
                        <img src="<?php echo '../../admin/win-loss-image/' . htmlspecialchars($message['image_path']); ?>" 
                             alt="アップロード画像" style="max-width: 200px;">
                    <?php endif; ?>
                    <span><?php echo htmlspecialchars($message['created_at']); ?></span>
                </div>
            <?php endforeach; ?>
        </div>

        <form action="tournament-chat-post.php" method="post" enctype="multipart/form-data">
            <input type="hidden" name="tournament_id" value="<?php echo htmlspecialchars($tournament_id); ?>">
            <input type="hidden" name="round" value="<?php echo htmlspecialchars($round); ?>">
            <textarea name="chat" required></textarea>
            <input type="file" name="image" accept="image/*">
            <button type="submit">送信</button>
        </form>

        <a href="tournament-list.php">戻る</a>
    </div>
</body>
</html>
