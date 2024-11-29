<?php
// DB接続クラス
class Database {
    private $connect = 'mysql:host=mysql311.phy.lolipop.lan;dbname=LAA1516826-playmate;charset=utf8';
    private $USER = 'LAA1516826';
    private $PASS = 'joyman';
    private $conn;

    public function getConnection() {
        $this->conn = null;
        try {
            $this->conn = new PDO($this->connect, $this->USER, $this->PASS);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo "接続エラー: " . $e->getMessage();
        }
        return $this->conn;
    }
}

// データベース接続
$db = new Database();
$conn = $db->getConnection();

$error_message = ""; // エラーメッセージ用

// フォームが送信された場合の処理
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $genre_name = $_POST['genre_name'];

    // データベースにジャンルを挿入
    if (!empty($genre_name)) {
        try {
            $stmt = $conn->prepare("INSERT INTO genre (genre) VALUES (:genre)");
            $stmt->bindParam(':genre', $genre_name, PDO::PARAM_STR);

            if ($stmt->execute()) {
                echo "新しいジャンルが追加されました！";
            } else {
                $error_message = "データベースへの保存に失敗しました。";
            }
        } catch (PDOException $e) {
            $error_message = "エラー: " . $e->getMessage();
        }
    } else {
        $error_message = "ジャンル名を入力してください。";
    }
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/game-genre-add.css">
    <title>新規ジャンル追加</title>
</head>
<body>
<a href="game-manage.php" class="back">←戻る</a>
<a href="logout.php" class="logout">ログアウト</a>
    <h1>新規ジャンル追加</h1>

    <?php if (!empty($error_message)) : ?>
        <p style="color: red;"><?php echo htmlspecialchars($error_message, ENT_QUOTES, 'UTF-8'); ?></p>
    <?php endif; ?>

    <form action="" method="post">
        <!-- ジャンル名入力 -->
        <label for="genre_name">ジャンル名:</label>
        <input type="text" name="genre_name" id="genre_name" required>
        <br><br>

        <button type="submit">追加</button>
    </form>
</body>
</html>
