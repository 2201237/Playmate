<?php
session_start();
require 'db-connect.php';

// ユーザーのアイコンURLを設定
$iconBaseUrl = 'https://aso2201222.kill.jp/';
$userIcon = isset($_SESSION['User']['icon']) ? $iconBaseUrl . $_SESSION['User']['icon'] : '../img/icon_user.png';

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
    $boards = $boardsql->fetchAll();
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
    <link rel="icon" href="../img/favicon.ico">
    <link rel="stylesheet" href="../css/header.css">
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/chatboard-title.css">
    <title>PlayMate掲示板一覧</title>
</head>

<body>
    <?php require 'header.php'; ?>

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
                            <a href="chatboard.php?board_title_id=<?php echo htmlspecialchars($board['board_id'], ENT_QUOTES, 'UTF-8'); ?>">
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
</body>

</html>