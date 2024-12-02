<?php
session_start();
require 'db-connect.php';
$userIcon = isset($_SESSION['User']['icon']) ?'https://aso2201222.kill.jp/'. $_SESSION['User']['icon'] : 'https://aso2201222.kill.jp/Playmate/user/img/icon_user.png';

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
    $current_user_id = $_SESSION['User']['user_id'];

    // ゲームタイトル一覧を取得
    $stmt = $pdo->prepare("SELECT game_id, title FROM game ORDER BY title ASC");
    $stmt->execute();
    $games = $stmt->fetchAll();

    // URLパラメータからboard_title_idを取得
    $board_title_id = isset($_GET['board_title_id']) ? (int)$_GET['board_title_id'] : null;

    // board_title_idが指定されていない場合はエラーメッセージを表示して終了
    if ($board_title_id === null) {
        echo "掲示板IDが指定されていません。<br>";
        echo "現在のURL: " . htmlspecialchars($_SERVER['REQUEST_URI']) . "<br>";
        var_dump($_GET);
        exit;
    }

    // POSTされたチャットメッセージを処理
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['chat'])) {
        $chat_message = trim($_POST['chat']);

        // チャットメッセージをデータベースに挿入
        $stmt = $pdo->prepare("
            INSERT INTO board_chat (board_title_id, chat, user_id, created_at)
            VALUES (:board_title_id, :chat, :user_id, NOW())
        ");
        $stmt->execute([
            ':board_title_id' => $board_title_id,
            ':chat' => $chat_message,
            ':user_id' => $current_user_id, // セッションから取得した現在のユーザーID
        ]);

        // 再読み込みしてチャットを更新
        header("Location: " . $_SERVER['REQUEST_URI']);
        exit;
    }

    // board_chatテーブルから特定のboard_title_idに関連するチャットデータを取得
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
    echo "データベースエラー: " . htmlspecialchars($e->getMessage());
    exit;
} catch (Exception $e) {
    echo "エラー: " . htmlspecialchars($e->getMessage());
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
document.querySelector('.up-button').addEventListener('click', function() {
    const chatContainer = document.querySelector('.chat-container');
    chatContainer.scrollTop = chatContainer.scrollHeight - chatContainer.clientHeight;
});
</script>
<body>
    <?php require 'header.php'; ?>

    <div class="game-title-list">
        <div class="headline">ゲームタイトル</div>
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
                        <img src="<?= htmlspecialchars($iconBaseUrl . $chat['icon'] ?? 'icon_user.png') ?>" class="icon_user" width="50" height="50">
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