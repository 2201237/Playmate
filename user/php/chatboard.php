<?php
session_start();
require 'db-connect.php';

// デバッグ用エラー表示
ini_set('display_errors', 1);
error_reporting(E_ALL);

// セッション確認
if (!isset($_SESSION['User']['user_id'])) {
    header('Location: login-input.php');
    exit;
}

// データベース接続
try {
    $pdo = new PDO($connect, USER, PASS, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
} catch (PDOException $e) {
    echo "データベース接続エラー: " . htmlspecialchars($e->getMessage());
    exit;
}

// ユーザー情報
$current_user_id = $_SESSION['User']['user_id'];
$current_user_icon = $_SESSION['User']['icon'] ?? 'icon_user.png';

// URLパラメータ取得
$board_title_id = isset($_GET['board_title_id']) ? (int)$_GET['board_title_id'] : null;

// board_title_id チェック
if ($board_title_id === null) {
    echo "掲示板IDが指定されていません。";
    exit;
}

// ゲームタイトル一覧取得
try {
    $stmt = $pdo->prepare("SELECT game_id, title FROM game ORDER BY title ASC");
    $stmt->execute();
    $games = $stmt->fetchAll();
} catch (PDOException $e) {
    echo "ゲームタイトル取得エラー: " . htmlspecialchars($e->getMessage());
    exit;
}

// チャットメッセージ削除処理
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_chat_id'])) {
    $delete_chat_id = (int)$_POST['delete_chat_id'];
    try {
        $stmt = $pdo->prepare("
            DELETE FROM board_chat 
            WHERE board_chat_id = :chat_id AND user_id = :user_id
        ");
        $stmt->execute([
            ':chat_id' => $delete_chat_id,
            ':user_id' => $current_user_id,
        ]);
    } catch (PDOException $e) {
        echo "チャット削除エラー: " . htmlspecialchars($e->getMessage());
        exit;
    }
    header("Location: " . $_SERVER['REQUEST_URI']);
    exit;
}

// チャットメッセージ投稿処理
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
        header("Location: " . $_SERVER['REQUEST_URI']);
        exit;
    } catch (PDOException $e) {
        echo "チャット投稿エラー: " . htmlspecialchars($e->getMessage());
        exit;
    }
}

// チャット履歴取得
try {
    $stmt = $pdo->prepare("
        SELECT
            c.board_chat_id,
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
<body>
<?php require 'header.php'; ?>

<!-- ゲームタイトルリストボタン -->
<button onclick="toggleGameList()" style="position: fixed; top: 55px; left: 165px; z-index: 1001;">タイトル表示</button>

<!-- ゲームタイトル一覧 -->
<div id="game-title-list" class="game-title-list" style="display: none;">
    <ul>
        <?php foreach ($games as $game): ?>
            <li>
                <a href="chatboard-title.php?game_id=<?= htmlspecialchars($game['game_id']) ?>">
                    <?= htmlspecialchars($game['title']) ?>
                </a>
            </li>
        <?php endforeach; ?>
    </ul>
</div>

<!-- チャット表示部分 -->
<div class="chat-container">
    <?php foreach ($chats as $chat): ?>
        <div class="chat-message <?= ($chat['user_id'] == $current_user_id) ? 'self' : 'other'; ?>">
            <div class="user-info">
                <a href="profile-partner.php?user_id=<?= htmlspecialchars($chat['user_id']) ?>">
                    <img src="<?= htmlspecialchars($chat['icon'] ?? 'icon_user.png') ?>" alt="Profile Icon" style="width: 50px; height: 50px;">
                </a>
                <span><?= htmlspecialchars($chat['user_name']) ?></span>
            </div>
            <div class="chat-box">
                <p><?= htmlspecialchars($chat['chat']) ?></p>
                <div class="chat-time"><?= htmlspecialchars($chat['created_at']) ?></div>

                <!-- 自分のメッセージのみ削除ボタン表示 -->
                <?php if ($chat['user_id'] == $current_user_id): ?>
                    <form method="POST" action="" style="display:inline;">
                        <input type="hidden" name="delete_chat_id" value="<?= $chat['board_chat_id'] ?>">
                        <button type="submit" style="background-color: red; color: white; border: none; cursor: pointer;">
                            削除
                        </button>
                    </form>
                <?php endif; ?>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<!-- チャット投稿フォーム -->
<form method="POST" action="" style="margin-top: 20px;">
    <textarea name="chat" rows="3" placeholder="メッセージを入力..."></textarea>
    <button class="up-button" type="submit">↑</button>
</form>

<script>
function toggleGameList() {
    const gameList = document.getElementById('game-title-list');
    gameList.style.display = (gameList.style.display === 'block') ? 'none' : 'block';
}
</script>
</body>
</html>