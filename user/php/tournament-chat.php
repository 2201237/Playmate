<?php
session_start();
require 'db-connect.php';
require '../header.html';

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
        $opponent_id = null;
    }
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
            <!-- チャットメッセージはJavaScriptで動的に表示 -->
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

    <script>
        // チャット更新関数
        async function fetchChat() {
            const tournamentId = "<?php echo htmlspecialchars($tournament_id); ?>";
            const round = "<?php echo htmlspecialchars($round); ?>";

            try {
                const response = await fetch(`fetch-tournament-chat.php?tournament_id=${tournamentId}&round=${round}`);
                const messages = await response.json();

                if (Array.isArray(messages)) {
                    const chatBox = document.getElementById('chat-box');
                    chatBox.innerHTML = ''; // 現在の内容をクリア

                    messages.forEach(message => {
                        const messageDiv = document.createElement('div');
                        messageDiv.classList.add('message');

                        messageDiv.innerHTML = `
                            <strong>${message.user_name}:</strong>
                            <p>${message.chat}</p>
                            ${message.image_path ? `<img src="../../admin/win-loss-image/${message.image_path}" style="max-width: 200px;">` : ''}
                            <span>${message.created_at}</span>
                        `;
                        chatBox.appendChild(messageDiv);
                    });
                } else {
                    console.error('エラー:', messages.error || '不明なエラー');
                }
            } catch (error) {
                console.error('通信エラー:', error);
            }
        }

        // 定期的にチャットを更新（2秒ごと）
        setInterval(fetchChat, 2000);

        // 初回のデータを取得
        fetchChat();
    </script>
</body>
</html>
