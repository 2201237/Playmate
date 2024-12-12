<?php 
session_start(); 
require 'db-connect.php'; 
 
// ログインチェック（管理者） 
if (!isset($_SESSION['admins'])) { 
    echo "管理者ログインが必要です。"; 
    exit; 
} 

$admin_id = $_SESSION['admins']['admin_id']; 

// 大会名とラウンド選択フォーム
$pdo = new PDO($connect, USER, PASS);

// 大会情報を取得
$stmt = $pdo->prepare('SELECT * FROM tournament');
$stmt->execute();
$tournaments = $stmt->fetchAll();

?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>大会アナウンス</title>
</head>
<body>
    <h1>大会アナウンス投稿</h1>

    <form action="admin_tournament_chat_post.php" method="post" enctype="multipart/form-data">
        <label for="tournament_id">大会名:</label>
        <select name="tournament_id" id="tournament_id" required>
            <?php foreach ($tournaments as $tournament): ?>
                <option value="<?php echo $tournament['tournament_id']; ?>"><?php echo htmlspecialchars($tournament['tournament_name']); ?></option>
            <?php endforeach; ?>
        </select><br><br>

        <label for="round">ラウンド:</label>
        <input type="number" name="round" id="round" min="0" required><br><br>

        <textarea name="chat" required></textarea><br><br>

        <input type="file" name="image" accept="image/*"><br><br>

        <button type="submit">アナウンス</button>
    </form>

    <!-- 戻るボタン -->
    <br><br>
    <button onclick="location.href='tournament.php'">戻る</button>

</body>
</html>
