<?php
require '../header.php';

$pdo = new PDO($connect, USER, PASS);

// ゲーム情報を取得
$gamezen = $pdo->prepare('SELECT * FROM game');
$gamezen->execute();

$gamesql = $pdo->prepare('SELECT * FROM game');
$gamesql->execute();
$games = $gamesql->fetchAll(PDO::FETCH_ASSOC);

// URLパラメータでgame_idが指定されている場合、そのゲームIDに対応する掲示板タイトルのみを取得
$game_id = $_GET['game_id'] ?? null;
if ($game_id) {
    // 特定のゲームIDの掲示板タイトルを取得
    $boardsql = $pdo->prepare('SELECT bt.board_title_id AS board_id, bt.title AS board_title, g.title AS game_title, bt.user_id
        FROM board_title AS bt
        JOIN game AS g ON bt.game_id = g.game_id
        WHERE bt.game_id = ?');
    $boardsql->execute([$game_id]);
} else {
    // 全ての掲示板タイトルを取得
    $boardsql = $pdo->prepare('SELECT bt.board_title_id AS board_id, bt.title AS board_title, g.title AS game_title, bt.user_id
        FROM board_title AS bt
        JOIN game AS g ON bt.game_id = g.game_id');
    $boardsql->execute();
}
$boards = $boardsql->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <!-- <link rel="stylesheet" href="../css/chatboard.css"> -->
    <title>PlayMate</title>
</head>
<body>
    
    <div class="container">
    
        
        <!-- ゲームタイトルセクション -->
        <div class="game-title-list">
            <h2>ゲームタイトル</h2>
            <ul>
            <a href=chatboard-title.php>絞り込みをやめる</a>
                <?php foreach ($games as $game) : ?>
                    <li>
                        <a href="?game_id=<?php echo htmlspecialchars($game['game_id'], ENT_QUOTES, 'UTF-8'); ?>">
                        <?php echo htmlspecialchars($game['title'], ENT_QUOTES, 'UTF-8'); ?>
                    </a></li>
                <?php endforeach; ?>
            </ul>
        </div>

        <!-- 掲示板一覧セクション -->
        <h1>掲示板一覧</h1>
        <div class="board-list">
            <?php if ($boards): ?>
                <?php foreach ($boards as $board) : ?>
                    <?php
                        // ユーザー名を取得
                        $user_sql = $pdo->prepare('SELECT user_name FROM users WHERE user_id = ?');
                        $user_sql->execute([$board['user_id']]);
                        $user = $user_sql->fetch(PDO::FETCH_ASSOC);

                        // 書き込み数を取得
                        $post_count_sql = $pdo->prepare('SELECT count(*) as post_count FROM board_chat WHERE board_title_id = ?');
                        $post_count_sql->execute([$board['board_id']]);
                        $post_count = $post_count_sql->fetch(PDO::FETCH_ASSOC);
                    ?>

                    <div class="board-item">
                        <p class="board-title"><?php echo htmlspecialchars($board['board_title'], ENT_QUOTES, 'UTF-8'); ?></p>
                        <p class="board-details">
                            投稿者: <?php echo htmlspecialchars($user['user_name'] ?? '不明なユーザー', ENT_QUOTES, 'UTF-8'); ?> | 
                            ゲーム: <?php echo htmlspecialchars($board['game_title'], ENT_QUOTES, 'UTF-8'); ?> | 
                            書き込み数: <?php echo htmlspecialchars($post_count['post_count'] ?? '0', ENT_QUOTES, 'UTF-8'); ?>
                        </p>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>選択したゲームに関連する掲示板タイトルはありません。</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
