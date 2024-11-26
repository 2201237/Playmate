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
    // POSTデータを取得
    $genre = $_POST['genre'];
    $title = $_POST['title'];
    $icon_path = null;

    // アイコンアップロード処理
    if (!empty($_FILES['icon']['name'])) {
        $icon_name = basename($_FILES['icon']['name']);
        $target_dir = "uploads/"; // アイコン保存ディレクトリ
        $target_file = $target_dir . $icon_name;

        // アップロードディレクトリが存在しない場合は作成
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }

        // ファイルをアップロード
        if (move_uploaded_file($_FILES['icon']['tmp_name'], $target_file)) {
            $icon_path = $target_file;
        } else {
            $error_message = "アイコンのアップロードに失敗しました。";
        }
    }

    // データベースにデータを挿入
    if (empty($error_message)) {
        try {
            $stmt = $conn->prepare("INSERT INTO game (genre_id, title, game_icon) VALUES (:genre, :title, :icon_path)");
            $stmt->bindParam(':genre', $genre, PDO::PARAM_INT);
            $stmt->bindParam(':title', $title, PDO::PARAM_STR);
            $stmt->bindParam(':icon_path', $icon_path, PDO::PARAM_STR);

            if ($stmt->execute()) {
                echo "新しいゲームタイトルが追加されました！";
            } else {
                $error_message = "データベースへの保存に失敗しました。";
            }
        } catch (PDOException $e) {
            $error_message = "エラー: " . $e->getMessage();
        }
    }
}

// ジャンルデータを取得（ジャンルをプルダウンに表示）
$genre_options = [];
try {
    $stmt = $conn->query("SELECT genre_id, name FROM genre");
    $genre_options = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $error_message = "ジャンルの取得に失敗しました: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>新規ゲームタイトル追加</title>
</head>
<body>
    <h1>新規ゲームタイトル追加</h1>

    <?php if (!empty($error_message)) : ?>
        <p style="color: red;"><?php echo htmlspecialchars($error_message, ENT_QUOTES, 'UTF-8'); ?></p>
    <?php endif; ?>

    <form action="" method="post" enctype="multipart/form-data">
        <!-- ジャンル選択 -->
        <label for="genre">ジャンル:</label>
        <select name="genre" id="genre" required>
            <?php foreach ($genre_options as $genre) : ?>
                <option value="<?php echo htmlspecialchars($genre['id'], ENT_QUOTES, 'UTF-8'); ?>">
                    <?php echo htmlspecialchars($genre['name'], ENT_QUOTES, 'UTF-8'); ?>
                </option>
            <?php endforeach; ?>
        </select>
        <br><br>

        <!-- タイトル入力 -->
        <label for="title">タイトル:</label>
        <input type="text" name="title" id="title" required>
        <br><br>

        <!-- アイコンアップロード -->
        <label for="icon">アイコン:</label>
        <input type="file" name="icon" id="icon" accept="image/*">
        <br><br>

        <button type="submit">追加</button>
    </form>
</body>
</html>
