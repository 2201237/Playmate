<?php
require '../header.php';

$pdo=new PDO($connect,USER,PASS);
$sql=$pdo->prepare('select * from chatboard_title');
$sql->execute();

?>

<!DOCTYPE html>
<html lang="ja">
    <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/chatboard.css">
    <title>PlayMate</title>
    </head>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f9f9f9; }
        .container { width: 80%; margin: 0 auto; padding: 20px; background-color: #fff; }
        .board-list { margin-top: 20px; }
        .board-item { padding: 10px; border-bottom: 1px solid #ddd; }
        .board-title { font-weight: bold; color: #333; }
        .board-details { font-size: 0.9em; color: #666; }
    </style>
</head>
<body>
    <div class="container">
        <h1>掲示板一覧</h1>
        
        <!-- ゲームタイトルセクション -->
        <div class="game-title-list">
            <h2>ゲームタイトル</h2>
            <ul>
                <?php foreach ($boards as $board) : ?>
                    <li><?php echo htmlspecialchars($board['game_title'], ENT_QUOTES, 'UTF-8'); ?></li>
                <?php endforeach; ?>
            </ul>
        </div>

        <!-- 掲示板一覧セクション -->
        <div class="board-list">
            <?php foreach ($boards as $board) : ?>
                <div class="board-item">
                    <p class="board-title"><?php echo htmlspecialchars($board['board_title'], ENT_QUOTES, 'UTF-8'); ?></p>
                    <p class="board-details">投稿者: <?php echo htmlspecialchars($board['user_name'], ENT_QUOTES, 'UTF-8'); ?> | ゲーム: <?php echo htmlspecialchars($board['game_title'], ENT_QUOTES, 'UTF-8'); ?></p>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</body>
</html>