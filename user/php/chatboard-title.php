<?php
require '../header.php';

$pdo = new PDO($connect, USER, PASS);

// ゲーム情報を取得
$gamesql = $pdo->prepare('SELECT * FROM game');
$gamesql->execute();

// 掲示板タイトルとゲームタイトルを取得
$boardsql = $pdo->prepare('SELECT bt.board_title_id AS board_id, bt.title AS board_title, g.title AS game_title
    FROM board_title AS bt
    JOIN game AS g ON bt.game_id = g.game_id');
$boardsql->execute();
$boards = $boardsql->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/chatboard.css">
    <title>PlayMate</title>
    <!-- <style>
        body { font-family: Arial, sans-serif; background-color: #f9f9f9; }
        .container { width: 80%; margin: 0 auto; padding: 20px; background-color: #fff; }
        .board-list { margin-top: 20px; }
        .board-item { padding: 10px; border-bottom: 1px solid #ddd; }
        .board-title { font-weight: bold; color: #333; }
        .board-details { font-size: 0.9em; color: #666; }
    </style> -->
</head>
<body>
    <div class="container">
        <h1>掲示板一覧</h1>
        
        <!-- ゲームタイトルセクション -->
        <div class="game-title-list">
            <h2>ゲームタイトル</h2>
            <ul>
                <?php foreach ($gamesql as $game) : ?>
                    <li><?php echo htmlspecialchars($game['title'], ENT_QUOTES, 'UTF-8'); ?></li>
                <?php endforeach; ?>
            </ul>
        </div>

        <!-- 掲示板一覧セクション -->
        <div class="board-list">
            <?php foreach ($boards as $board) : ?>
                <?php
                    // ユーザー名を取得
                    $user_sql = $pdo->prepare('SELECT u.user_name FROM users AS u WHERE u.user_id = (SELECT user_id FROM board_title WHERE board_title_id = ?)');
                    $user_sql->execute([$board['user_name']]);
                    $user = $user_sql->fetch(PDO::FETCH_ASSOC);

                    // 書き込み数を取得
                    $post_count_sql = $pdo->prepare('SELECT COUNT(*) AS post_count FROM post WHERE board_id = ?');
                    $post_count_sql->execute([$board['board_id']]);
                    $post_count = $post_count_sql->fetch(PDO::FETCH_ASSOC);
                ?>

                <div class="board-item">
                    <p class="board-title"><?php echo htmlspecialchars($board['board_title'], ENT_QUOTES, 'UTF-8'); ?></p>
                    <p class="board-details">
                        投稿者: <?php echo htmlspecialchars($user['username'], ENT_QUOTES, 'UTF-8'); ?> | 
                        ゲーム: <?php echo htmlspecialchars($board['game_title'], ENT_QUOTES, 'UTF-8'); ?> | 
                        書き込み数: <?php echo htmlspecialchars($post_count['post_count'], ENT_QUOTES, 'UTF-8'); ?>
                    </p>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</body>
</html>
