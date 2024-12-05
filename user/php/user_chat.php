<?php
session_start();
require 'db-connect.php';
$userIcon = isset($_SESSION['User']['icon']) ?'https://aso2201222.kill.jp/'. $_SESSION['User']['icon'] : '../img/icon_user.png';

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


    // POSTされたチャットメッセージを処理
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['chat'])) {
        $chat_message = trim($_POST['chat']);

        // チャットメッセージをデータベースに挿入
        $stmt = $pdo->prepare("
            INSERT INTO users_chat (users_chat_id, chat,is_read, user_id1, created_at,user_id2)
            VALUES (?, :chat, :is_read, :user_id1, NOW(), :user_id2)
        ");
        $stmt->execute([
            ':chat' => $chat_message,
            ':user_id1' => $UserId, // セッションから取得した現在のユーザーID
            ':user_id2' => $_GET['user_id'],
        ]);

        // 再読み込みしてチャットを更新
        header("Location: " . $_SERVER['REQUEST_URI']);
        exit;
    }

    // board_chatテーブルから特定のusers_chat_idに関連するチャットデータを取得
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
            c.users_chat_id = :users_chat_id
        ORDER BY 
            c.created_at ASC
    ");
    $stmt->bindParam(':users_chat_id', $users_chat_id, PDO::PARAM_INT);
    $stmt->execute();
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
<<<<<<< HEAD
    <link rel="stylesheet" href="../css/chatboard.css">
    <title>PlayMate - チャットボード</title>
</head>
<body>
    <h3><?php $chat['user_name'] ?></h3>
        <?php
        if (isset($iconPath) && $iconPath !== '') {
            echo "<input type = 'hidden' name = '" . $iconPath . "' value = '" . $iconPath . "'></input>";

            echo "<img src='".$iconPath."' class='icon_user' width='50' height='50'>";
        } else {
            echo "<img src='../img/icon_user.png' class='icon_user' width='50' height='50'>";
        }
    ?>
    

    <div class="chat-container">
=======
    <link rel="stylesheet" href="../css/user_chat.css">
    <link rel="stylesheet" href="../css/style.css">

    <title>PlayMate - チャットボード</title>
</head>
<body>
        
    <!-- 上部に相手の情報を表示 -->
    <div class="user-header">
        <form action="#" class = "home-header" method="POST">
            <a href="profile-partner.php?user_id=<?= ($partnerId); ?>" class="link">
                <img src = "../img/back.png"  class="icon" width="50" height="50">
                <img src="<?= ($partnerInfo['icon']); ?>" class="icon" width="50" height="50" alt="Profile Icon">
            </a>
            <span class = "you-name"><?= ($partnerInfo['user_name']); ?></span>
        </form>
    </div>

    <!-- チャットメッセージ表示 -->
    <div class="chat-container" id="chatContainer">
>>>>>>> cd0bb936e7dcc76c789ffb2bce7adc380da5d1ff
        <?php foreach ($chats as $chat): ?>
            <div class="chat-message <?php echo ($chat['user_id'] == $UserId) ? 'self' : 'other'; ?>">
                <div class="user-info">
<<<<<<< HEAD
                    <!-- アイコンの表示 -->
                    <a href="profile-partner.php?user_id=<?= $chat['user_id'] ?>">
                        <!-- 各ユーザーのアイコンを個別に表示 -->
                    <?php
                        if (isset($iconPath) && $iconPath !== '') {
                            echo "<input type = 'hidden' name = '" . $iconPath . "' value = '" . $iconPath . "'></input>";
            
                            echo "<img src='".$iconPath."' class='icon_user' width='50' height='50'>";
                        } else {
                            echo "<img src='../img/icon_user.png' class='icon_user' width='50' height='50'>";
                        }
                    ?>
                    </a>
                    <span><?= $chat['user_name'] ?></span>
                </div>
                <div class="chat-box">
                    <!-- チャットメッセージの表示 -->
                    <p><?= $chat['chat'] ?></p>
                    <div class="chat-time"><?= $chat['created_at'] ?></div>
=======
                    <?php if ($chat['user_id'] == $UserId): ?>
                        <!-- 自分のメッセージ -->
                        <div class="my-chat">
                            <img src="<?= ($chat['icon']); ?>" class="my-icon" width="50" height="50" alt="Profile Icon">
                        </div>
                        <div class="my-chat-box">
                            <p><?= ($chat['chat']); ?></p>
                            <div class="chat-time"><?= ($chat['created_at']); ?></div>
                        </div>
                    <?php else: ?>
                        <!-- 相手のメッセージ -->
                        <div class="you-chat">
                            <a href="profile-partner.php?user_id=<?= ($chat['user_id']); ?>">
                                <img src="<?= ($chat['icon']); ?>" 
                                     class="you-icon" width="50" height="50" alt="Profile Icon">
                            </a>
                            <span class="you-name"><?= ($chat['user_name']); ?></span>
                        </div>
                        <div class="you-chat-box">
                            <p><?= ($chat['chat']); ?></p>
                            <div class="chat-time"><?= ($chat['created_at']); ?></div>
                        </div>
                    <?php endif; ?>
>>>>>>> cd0bb936e7dcc76c789ffb2bce7adc380da5d1ff
                </div>
            </div>
        <?php endforeach; ?>
    </div>

<<<<<<< HEAD
    <form method="POST" action="">
        <label for="chat">新しいメッセージ</label>
        <textarea name="chat" id="chat" rows="5" required></textarea>
        <br><br>
        <button type="submit">送信</button>
        <button type="button" onclick="location.href='chatboard-title.php'">戻る</button>
    </form>
=======
    <!-- チャット送信フォーム -->
    <div class="form-chat">
        <form method="POST" action="">
            <textarea name="chat" id="chat" class="chat" width="200" rows="6" placeholder="メッセージ" wrap="hard" required></textarea>
            <label for="image-upload" class="image-upload-label"> 📷 
                <input type="file" id="image-upload" name="image" accept="image/*" style="display: none;"> 
            </label>
            <button type="submit">送信</button>
            <a href="#jump" class="jump">↓</a>
        </form>
        <div id="jump"></div>
    </div>

    <script>
    window.onload = function() {
        jump(); // ページロード時に最下部にスクロール
    };

    function jump() {
        var chatContainer = document.getElementById('chatContainer');
        chatContainer.scrollTop = chatContainer.scrollHeight; // 必ず最下部にスクロール
    }

        if (chatContainer.scrollHeight - chatContainer.scrollTop > chatContainer.clientHeight + 10) {
            scrollButton.style.display = 'block'; // ボタンを表示
        } else {
            scrollButton.style.display = 'none'; // ボタンを非表示
        };

    document.querySelector('.jump').addEventListener('click', function() {
        jump(); // 最下部にスクロール
    });

    document.querySelector('form').addEventListener('submit', function() {
        setTimeout(jump, 100); // 少し遅延させて最下部にスクロール
    });
    </script>
>>>>>>> cd0bb936e7dcc76c789ffb2bce7adc380da5d1ff
</body>
</html>