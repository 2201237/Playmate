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
            ':user_id1' => $UserId, // セッションから取得した現在のユーザーID
            ':user_id2' => $_GET['user_id'],
        ]);

        // チャットを更新
        header("Location: " . $_SERVER['REQUEST_URI']);
        exit;
    }

    // チャットメッセージを取得するコード
    $user_id1 = $UserId;
    $user_id2 = $_GET['user_id'];
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
    $stmt->bindParam(':user_id1', $user_id1, PDO::PARAM_INT);
    $stmt->bindParam(':user_id2', $user_id2, PDO::PARAM_INT);
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
    <link rel="stylesheet" href="../css/user_chat.css">
    <title>PlayMate - チャットボード</title>
</head>
<body>
    <div class="chat-container">
        <?php foreach ($chats as $chat): ?>
            <div class="chat-message">
                <div class="user-info">
                    <?php
                    $iconPath = isset($chat['icon']) ? 'https://aso2201222.kill.jp/'.$chat['icon'] : '';
                    
                    if ($chat['user_id'] == $user_id1) {
                        // 自身のメッセージ
                        echo "<div class = 'my_chat'>";
                        echo "<span>あなた: </span>";

                            echo "<img src='" . $iconPath . "' class='icon_user' width='50' height='50'>";
                            
                        echo '<div class="chat-box">';
                        echo '<p>' . $chat["chat"]. ' </p>';
                        echo '<div class="chat-time">' . $chat["created_at"] .'</div>';
                        echo '</div>';
    
                        echo "</div>";
                    } else {
                        // 相手のメッセージ
                        echo "<div class = 'you_chat'>";
                        echo "<a href='profile-partner.php?user_id=" . $chat['user_id'] . "'>";
                        if ($iconPath !== '') {
                            echo "<img src='" . $iconPath . "' class='icon_user' width='50' height='50'>";
                        } else {
                            echo "<img src='../img/icon_user.png' class='icon_user' width='50' height='50'>";
                        }
                        echo "</a>";
                        echo "<span>" . $chat['user_name'] . ": </span>";
                        echo '<div class="chat-box">';
                        echo '<p>' . $chat["chat"]. ' </p>';
                        echo '<div class="chat-time">' . $chat["created_at"] .'</div>';
                        echo '</div>';
    
                        echo "</div>";
                    }
                    ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <form method="POST" action="">
        <label for="chat">新しいメッセージ</label>
        <textarea name="chat" id="chat" rows="5" required></textarea>
        <br><br>
        <button type="submit">送信</button>
    </form>
    <form action = "home.php" method = "POST">
        <button type = "submit">ホームに戻る</button>
    </form>
</body>
</html>
