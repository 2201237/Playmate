<?php
session_start();
require 'db-connect.php';
require 'header.php';

$pdo = new PDO($connect, USER, PASS);

// ログインチェック
if (!isset($_SESSION['User'])) {
    echo "ログインしてください。";
    exit;
}

$user_id = $_SESSION['User']['user_id'];

if (isset($_GET['tournament_id']) && isset($_GET['round'])) {
    $tournament_id = $_GET['tournament_id'];
    $round = $_GET['round'];

    // tournament_memberテーブルでユーザーがこのトーナメントに参加しているかチェック
    $member_check_stmt = $pdo->prepare('SELECT * FROM tournament_member WHERE tournament_id = ? AND user_id = ?');
    $member_check_stmt->execute([$tournament_id, $user_id]);
    $member = $member_check_stmt->fetch();

    if (!$member) {
        echo "大会に参加していないため、チャットは表示できません。";
        exit;
    }

    // $round == 0 の場合、対戦相手情報は不要
    if ($round != 0) {
        // tournament_kumiテーブルで自分の対戦相手を取得
        $opponent_stmt = $pdo->prepare('
            SELECT * FROM tournament_kumi
            WHERE tournament_id = ? AND round = ? AND (user_id1 = ? OR user_id2 = ?)
        ');
        $opponent_stmt->execute([$tournament_id, $round, $user_id, $user_id]);
        $opponent = $opponent_stmt->fetch();

        if (!$opponent) {
            echo "対戦相手情報が見つかりませんでした。";
            exit;
        }

        // 対戦相手のuser_idを取得
        $opponent_id = ($opponent['user_id1'] == $user_id) ? $opponent['user_id2'] : $opponent['user_id1'];
    } else {
        // $round == 0 の場合は対戦相手情報を設定しない
        $opponent_id = null;
    }

    // チャットメッセージを取得
    $message_stmt = $pdo->prepare('
        SELECT m.*, u.user_name FROM tournament_chat m
        JOIN users u ON m.user_id = u.user_id
        WHERE m.tournament_id = ? AND m.round = ?
        ORDER BY m.created_at ASC
    ');
    $message_stmt->execute([$tournament_id, $round]);
    $messages = $message_stmt->fetchAll();
} else {
    echo "大会IDまたは回戦が指定されていません。";
    exit;
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
                <!-- $round == 0 の場合は全メッセージを表示、それ以外はログインユーザーまたは対戦相手のメッセージのみ表示 -->
                <?php if ($round == 0 || $message['user_id'] == $user_id || $message['user_id'] == $opponent_id): ?>
                    <div class="message">
                        <strong><?php echo htmlspecialchars($message['user_name']); ?>:</strong>
                        <p><?php echo htmlspecialchars($message['chat']); ?></p>
                        <?php if (!empty($message['image_path'])): ?>
                            <img src="<?php echo '../../admin/win-loss-image/' . htmlspecialchars($message['image_path']); ?>" 
                                 alt="アップロード画像" style="max-width: 200px;">
                        <?php endif; ?>
                        <span><?php echo htmlspecialchars($message['created_at']); ?></span>
                    </div>
                <?php endif; ?>
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
