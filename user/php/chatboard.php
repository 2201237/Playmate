<?php
session_start();
require 'db-connect.php';

// デバッグ用エラー表示
ini_set('display_errors', 1);
error_reporting(E_ALL);

// セッションにユーザー情報が設定されているか確認
if (!isset($_SESSION['User']['user_id'])) {
    header('Location: login-input.php');
    exit;
}

// データベース接続処理
try {
    $pdo = new PDO($connect, USER, PASS, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
} catch (PDOException $e) {
    echo "データベース接続エラー: " . htmlspecialchars($e->getMessage());
    exit;
}

// 現在のユーザーIDを取得
$current_user_id = $_SESSION['User']['user_id'];
$current_user_icon = $_SESSION['User']['icon'] ?? 'icon_user.png';

// URLパラメータからboard_title_idを取得
$board_title_id = isset($_GET['board_title_id']) ? (int)$_GET['board_title_id'] : null;

// board_title_idが指定されていない場合はエラー表示
if ($board_title_id === null) {
    echo "掲示板IDが指定されていません。<br>";
    echo "現在のURL: " . htmlspecialchars($_SERVER['REQUEST_URI']) . "<br>";
    exit;
}

// ゲームタイトル一覧を取得
try {
    $stmt = $pdo->prepare("SELECT game_id, title FROM game ORDER BY title ASC");
    $stmt->execute();
    $games = $stmt->fetchAll();
} catch (PDOException $e) {
    echo "ゲームタイトル取得エラー: " . htmlspecialchars($e->getMessage());
    exit;
}

// POSTされたチャットメッセージを処理
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['chat'])) {
    $chat_message = trim($_POST['chat']);
    try {
        $stmt = $pdo->prepare("
            INSERT INTO board_chat (board_title_id, chat, user_id, created_at)
            VALUES (:board_title_id, :chat, :user_id, NOW())
        ");
        $stmt->execute([
            ':board_title_id' => $board_title_id,
            ':chat' => $chat_message,
            ':user_id' => $current_user_id,
        ]);
        // 再読み込みしてチャットを更新
        header("Location: " . $_SERVER['REQUEST_URI']);
        exit;
    } catch (PDOException $e) {
        echo "チャットメッセージ挿入エラー: " . htmlspecialchars($e->getMessage());
        exit;
    }
}

// チャット履歴を取得
try {
    $stmt = $pdo->prepare("
        SELECT 
            c.chat, 
            c.created_at, 
            u.user_id, 
            u.user_name, 
            u.icon 
        FROM 
            board_chat AS c
        JOIN 
            users AS u ON c.user_id = u.user_id
        WHERE 
            c.board_title_id = :board_title_id
        ORDER BY 
            c.created_at ASC
    ");
    $stmt->bindParam(':board_title_id', $board_title_id, PDO::PARAM_INT);
    $stmt->execute();
    $chats = $stmt->fetchAll();
} catch (PDOException $e) {
    echo "チャット履歴取得エラー: " . htmlspecialchars($e->getMessage());
    exit;
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/header.css">
    <link rel="stylesheet" href="../css/chatboard.css">
    <title>PlayMate - チャットボード</title>
</head>
<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelector('.up-button').addEventListener('click', function() {
        const chatContainer = document.querySelector('.chat-container');
        chatContainer.scrollTop = chatContainer.scrollHeight - chatContainer.clientHeight;
    });

    document.querySelector('button[onclick="toggleGameList()"]').addEventListener('click', function() {
        const gameList = document.getElementById('game-title-list');
        if (gameList.style.display === 'none' || gameList.style.display === '') {
            gameList.style.display = 'block';
        } else {
            gameList.style.display = 'none';
        }
    });
});
</script>
<body>
    <?php require 'header.php'; ?>

    <!-- ゲームタイトルを表示するボタン -->
    <button onclick="toggleGameList()" style="position: fixed; top: 70px; left: 20px; z-index: 1001;">ゲームタイトル</button>

    <!-- ゲームタイトルリスト -->
    <div id="game-title-list" class="game-title-list" style="display: none;">
        <ul>
            <?php foreach ($games as $game): ?>
                <li><a href="chatboard-title.php?game_id=<?php echo htmlspecialchars($game['game_id'], ENT_QUOTES, 'UTF-8'); ?>">
                    <?php echo htmlspecialchars($game['title'], ENT_QUOTES, 'UTF-8'); ?>
                </a></li>
            <?php endforeach; ?>
        </ul>
    </div>

    <div class="chat-container">
        <?php foreach ($chats as $chat): ?>
            <div class="chat-message <?php echo ($chat['user_id'] == $current_user_id) ? 'self' : 'other'; ?>">
                <div class="user-info">
                    <a href="profile-partner.php?user_id=<?= htmlspecialchars($chat['user_id']) ?>">
                    <img src="<?php echo htmlspecialchars($chat['icon'] ?? 'icon_user.png', ENT_QUOTES, 'UTF-8'); ?>" alt="Profile Icon" style="width: 50px; height: 50px;">
                    </a>
                    <span><?= htmlspecialchars($chat['user_name']) ?></span>
                </div>
                <div class="chat-box">
                    <p><?= htmlspecialchars($chat['chat']) ?></p>
                    <div class="chat-time"><?= htmlspecialchars($chat['created_at']) ?></div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <form method="POST" action="">
        <textarea name="chat" id="chat" rows="3"></textarea>
        <button class="up-button" type="submit">↑</button>
    </form>
</body>
</html>