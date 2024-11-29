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

// tournament_id が URL パラメータとして渡されていることを確認
if (isset($_GET['tournament_id'])) {
    $tournament_id = $_GET['tournament_id'];
    
    // 大会の情報を取得
    $stmt = $pdo->prepare('SELECT * FROM tournament WHERE tournament_id = ?');
    $stmt->execute([$tournament_id]);
    $tournament = $stmt->fetch();

    if (!$tournament) {
        echo "大会情報が見つかりませんでした。";
        exit;
    }

    // ゲームの情報を取得
    $game_stmt = $pdo->prepare('SELECT * FROM game WHERE game_id = ?');
    $game_stmt->execute([$tournament['game_id']]);
    $game = $game_stmt->fetch();

    if (!$game) {
        echo "ゲーム情報が見つかりませんでした。";
        exit;
    }

    // ゲームアイコンのパス
    $image_path = "../img/" . $game['game_id'] . ".jpg";

    // ユーザーIDを取得（セッションから）
    $user_id = $_SESSION['User']['user_id'];

    // ユーザーがこの大会に参加しているか確認
    $check_participation = $pdo->prepare('SELECT COUNT(*) FROM tournament_member WHERE tournament_id = ? AND user_id = ?');
    $check_participation->execute([$tournament_id, $user_id]);
    $is_participating = $check_participation->fetchColumn();

    // ログインユーザーに関連するラウンドを取得
    $round_stmt = $pdo->prepare('SELECT DISTINCT round FROM tournament_kumi WHERE tournament_id = ? AND (user_id1 = ? OR user_id2 = ?)');
    $round_stmt->execute([$tournament_id, $user_id, $user_id]);
    $rounds = $round_stmt->fetchAll(PDO::FETCH_COLUMN);
} else {
    echo "大会IDが指定されていません。";
    exit;
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/header.css">

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

        <!-- チャットリンク -->
        <div>
            <h2>大会チャット</h2>
            <a href="tournament-chat.php?tournament_id=<?php echo $tournament_id; ?>&round=0">全体チャット</a>
            <?php foreach ($rounds as $round): ?>
                <a href="tournament-chat.php?tournament_id=<?php echo $tournament_id; ?>&round=<?php echo $round; ?>">
                    <?php echo htmlspecialchars($round); ?>回戦チャット
                </a>
            <?php endforeach; ?>
        </div>
    </div>
</body>
</html>
