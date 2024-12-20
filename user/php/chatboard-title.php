<?php
    require 'db-connect.php';
    require 'header.php';

    $pdo = new PDO($connect, USER, PASS);

    // ゲーム情報を取得
    $gamesql = $pdo->prepare('SELECT * FROM game');
    $gamesql->execute();
    $games = $gamesql->fetchAll(PDO::FETCH_ASSOC);

    // URLパラメータでgame_idが指定されている場合、そのゲームIDに対応する掲示板タイトルのみを取得
    $game_id = $_GET['game_id'] ?? null;
    if ($game_id) {
        $boardsql = $pdo->prepare('SELECT bt.board_title_id AS board_id, bt.title AS board_title, g.title AS game_title, bt.user_id
            FROM board_title AS bt
            JOIN game AS g ON bt.game_id = g.game_id
            WHERE bt.game_id = ?');
        $boardsql->execute([$game_id]);
    } else {
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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="../img/favicon.ico">
    <link rel="stylesheet" href="../css/header.css">
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/chatboard-title.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="../js/owl.carousel.min"></script>
    <title>PlayMate掲示板一覧</title>
</head>

<body>

    <div class="container">
        <a href="chatboard-create.php">チャット作成</a>


        <!-- ゲームタイトルセクション -->
        <div class="game-title-list">
        <div class="headline">ゲームタイトル</div>
            <ul>
                <?php foreach ($games as $game): ?>
                    <li><a href="?game_id=<?php echo htmlspecialchars($game['game_id'], ENT_QUOTES, 'UTF-8'); ?>">
                            <?php echo htmlspecialchars($game['title'], ENT_QUOTES, 'UTF-8'); ?>
                        </a></li>
                <?php endforeach; ?>
            </ul>
        </div>

        <!-- 掲示板一覧セクション -->
        <div class="headline">掲示板一覧</div>
        <div class="board-list">
            <?php if ($boards): ?>
                <?php foreach ($boards as $board): ?>
                    <?php
                    $user_sql = $pdo->prepare('SELECT user_name FROM users WHERE user_id = ?');
                    $user_sql->execute([$board['user_id']]);
                    $user = $user_sql->fetch(PDO::FETCH_ASSOC);

                    $post_count_sql = $pdo->prepare('SELECT count(*) as post_count FROM board_chat WHERE board_title_id = ?');
                    $post_count_sql->execute([$board['board_id']]);
                    $post_count = $post_count_sql->fetch(PDO::FETCH_ASSOC);
                    ?>

                    <div class="board-item">
                        <p class="board-title">
                            <a
                                href="chatboard.php?board_title_id=<?php echo htmlspecialchars($board['board_id'], ENT_QUOTES, 'UTF-8'); ?>">
                                <?php echo htmlspecialchars($board['board_title'], ENT_QUOTES, 'UTF-8'); ?>
                            </a>
                        </p>
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
    <script src="../js/header.js"></script>
    <script src="../js/home.js"></script>
    <script src="../js/ranking-list.js"></script>
</body>

</html>