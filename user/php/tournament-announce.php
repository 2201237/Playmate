<?php 
session_start(); 
require 'db-connect.php'; 

// ログインチェック 
if (!isset($_SESSION['User'])) { 
    echo "ログインしてください。"; 
    exit; 
} 

$user_id = $_SESSION['User']['user_id']; 

// GETパラメータ取得
if (isset($_GET['tournament_id']) && isset($_GET['round'])) {
    $tournament_id = $_GET['tournament_id'];
    $round = $_GET['round'];

    // 大会情報を取得
    $pdo = new PDO($connect, USER, PASS);
    $stmt = $pdo->prepare('SELECT * FROM admin_tournament_chat WHERE tournament_id = ? AND round = ? ORDER BY created_at ASC');
    $stmt->execute([$tournament_id, $round]);
    $announcements = $stmt->fetchAll();
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
    <title>大会アナウンス</title>
</head>
<body>
    <h1>大会アナウンス（<?php echo htmlspecialchars($round == 0 ? '全体' : $round . '回戦'); ?>）</h1>

    <div id="announcement-box">
        <?php foreach ($announcements as $announcement): ?>
            <div class="announcement">
                <strong>管理者: <?php echo htmlspecialchars($announcement['admin_id']); ?></strong>
                <p><?php echo nl2br(htmlspecialchars($announcement['chat'])); ?></p>
                <?php if ($announcement['image_path']): ?>
                    <img src="../../admin/win-loss-image/<?php echo htmlspecialchars($announcement['image_path']); ?>" style="max-width: 200px;">
                <?php endif; ?>
                <span><?php echo $announcement['created_at']; ?></span>
            </div>
        <?php endforeach; ?>
    </div>

    <a href="tournament-list.php">大会一覧へ戻る</a>
    <script>
    function fetchAnnouncements() {
        var xhr = new XMLHttpRequest();
        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4) {
                console.log("Response Text:", xhr.responseText); // レスポンス内容を確認
                if (xhr.status === 200) {
                    try {
                        var response = JSON.parse(xhr.responseText);
                        if (Array.isArray(response)) {
                            updateAnnouncementBox(response);
                        } else if (response.error) {
                            console.error("Server Error:", response.error);
                        } else {
                            console.error("Unexpected Response:", response);
                        }
                    } catch (error) {
                        console.error("JSON Parse Error:", error.message, xhr.responseText);
                    }
                } else {
                    console.error("Request Failed:", xhr.status, xhr.statusText);
                }
            }
        };
        xhr.open('GET', 'fetch-tournament-announcements.php?tournament_id=<?php echo $tournament_id; ?>&round=<?php echo $round; ?>', true);
        xhr.send();
    }

    function updateAnnouncementBox(announcements) {
        var announcementBox = document.getElementById('announcement-box');
        announcementBox.innerHTML = '';
        announcements.forEach(function(announcement) {
            var announcementHtml = '<div class="announcement">' +
                '<strong>管理者: ' + announcement.admin_name + '</strong>' + // admin_nameに変更
                '<p>' + announcement.chat.replace(/\n/g, '<br>') + '</p>';
            if (announcement.image_path) {
                announcementHtml += '<img src="../../admin/win-loss-image/' + announcement.image_path + '" style="max-width: 200px;">';
            }
            announcementHtml += '<span>' + announcement.created_at + '</span></div>';
            announcementBox.innerHTML += announcementHtml;
        });
    }

    // 5秒ごとに更新
    setInterval(fetchAnnouncements, 5000);

    // 初回読み込み
    fetchAnnouncements();
    </script>

</body>
</html>
