<?php
require 'db-connect.php';

// フォーム送信時の処理
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $pdo = new PDO($connect, USER, PASS);
        
        // 入力値を取得
        $tournament_name = $_POST['tournament-name'];
        $game_id = $_POST['game_title'];
        $rules = $_POST['story'];
        $tournament_deadline = $_POST['tournament_deadline']; // 締め切り時間を取得
        
        // SQLインジェクション対策のためプリペアドステートメントを使用
        $sql = 'INSERT INTO tournament (tournament_name, game_id, rule, tournament_deadline) VALUES (?, ?, ?, ?)';
        $stmt = $pdo->prepare($sql);
        
        // 実行
        if ($stmt->execute([$tournament_name, $game_id, $rules, $tournament_deadline])) {
            echo '<script>alert("大会が正常に作成されました。"); window.location.href = "tournament-view.php";</script>';
        } else {
            echo '<script>alert("大会の作成に失敗しました。");</script>';
        }
    } catch (PDOException $e) {
        echo '<script>alert("エラーが発生しました: ' . $e->getMessage() . '");</script>';
    }
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/tournament-create.css">
    <title>大会作成</title>
</head>
<body>
    <a href="tournament.php" return false;" class="back">←戻る</a>
    <h1>大会作成</h1>
    <a href="login.php" class="logout">ログアウト</a>
    <div class="container">
        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
            <input type="text" name="tournament-name" placeholder="大会名" required><br>
            
            <label for="game_title">ゲームタイトルを選択:</label>
            <select name="game_title" id="game_title" required>
                <?php
                try {
                    $pdo = new PDO($connect, USER, PASS);
                    $sql = $pdo->query('SELECT * FROM game');
                    foreach ($sql as $row) {
                        echo '<option value="', htmlspecialchars($row['game_id']), '">',
                              htmlspecialchars($row['title']), '</option>';
                    }
                } catch (PDOException $e) {
                    echo '<option value="">エラーが発生しました</option>';
                }
                ?>
            </select>
            
            <textarea id="story" name="story" rows="5" cols="33" required></textarea>
            
            <!-- 締め切り時間入力フィールドを追加 -->
            <label for="tournament_deadline">締め切り時間:</label>
            <input type="datetime-local" id="tournament_deadline" name="tournament_deadline" required><br>
            
            <div class="button-group">
                <button type="submit" class="create">作成</button>
            </div>
        </form>
    </div>
</body>
</html>