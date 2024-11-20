<?php
session_start();
require 'db-connect.php';
require 'header.php';
$pdo = new PDO($connect, USER, PASS);

// tournament_id が URL パラメータとして渡されていることを確認
if (isset($_GET['tournament_id'])) {
    $tournament_id = $_GET['tournament_id'];
    
    // 大会の情報を取得
    $stmt = $pdo->prepare('SELECT * FROM tournament WHERE tournament_id = ?');
    $stmt->execute([$tournament_id]);
    $tournament = $stmt->fetch();

    // ゲームの情報を取得
    $game_stmt = $pdo->prepare('SELECT * FROM game WHERE game_id = ?');
    $game_stmt->execute([$tournament['game_id']]);
    $game = $game_stmt->fetch();

    // ゲームアイコンのパス
    $image_path = "../img/" . $game['game_id'] . ".jpg";

    // ユーザーIDを取得（セッションから）
    $user_id = $_SESSION['User']['user_id'];

    // ユーザーがこの大会に参加しているか確認
    $check_participation = $pdo->prepare('SELECT COUNT(*) FROM tournament_member WHERE tournament_id = ? AND user_id = ?');
    $check_participation->execute([$tournament_id, $user_id]);
    $is_participating = $check_participation->fetchColumn();
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>大会詳細</title>
</head>
<body>
    <div>
        <h1><?php echo htmlspecialchars($tournament['tournament_name']); ?></h1>
        
        <!-- ゲームアイコンの表示 -->
        <?php if (file_exists($image_path)): ?>
            <img src="<?php echo $image_path; ?>" width="180" height="">
        <?php else: ?>
            <p>ゲームアイコンが見つかりません</p>
        <?php endif; ?>
        
        <p>ゲーム名: <?php echo htmlspecialchars($game['title']); ?></p>
        <p>ルール: <?php echo htmlspecialchars($tournament['rule']); ?></p>

        <!-- すでに参加している場合は参加ボタンを表示しない -->
        <?php if ($is_participating == 0): ?>
            <form action="tournament-join.php?tournament_id=<?php echo $tournament_id; ?>" method="post">
                <input type="submit" value="参加">
            </form>
        <?php else: ?>
            <p>すでにこの大会に参加しています。</p>
        <?php endif; ?>

        <a href="tournament-list.php">戻る</a>
        <a href="tournament-chat.php?tournament_id=<?php echo $tournament_id; ?>&round=0">大会チャット（全体）</a>
        <a href="tournament-chat.php?tournament_id=<?php echo $tournament_id; ?>&round=1">大会チャット（1回戦）</a>
        <!-- 必要に応じて追加のラウンドのリンクを追加 -->
    </div>
</body>
</html>
