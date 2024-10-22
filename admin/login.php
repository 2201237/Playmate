<?php
// セッションを開始
session_start();

// データベース接続情報
$host = 'mysql311.phy.lolipop.lan'; // サーバーホスト名
$dbname = '	LAA1516826-playmate'; // データベース名
$username = 'LAA1516826'; // データベースユーザー名
$password = 'joyman'; // データベースパスワード

// PDOを使ったデータベース接続
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("データベース接続失敗: " . $e->getMessage());
}

// ログイン処理が送信されたか確認
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // フォームから送信された管理者IDとパスワードを取得
    $admin_id = $_POST['admin_id'];
    $admin_password = $_POST['admin_password'];

    // SQLクエリを使用してデータベースから管理者情報を取得
    $stmt = $pdo->prepare("SELECT * FROM administrators WHERE admin_id = :admin_id");
    $stmt->bindParam(':admin_id', $admin_id);
    $stmt->execute();
    $admin = $stmt->fetch(PDO::FETCH_ASSOC);

    // 管理者が存在し、パスワードが一致するか確認
    if ($admin && password_verify($admin_password, $admin['password'])) {
        // ログイン成功時、セッションに管理者情報を保存
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['admin_id'] = $admin['admin_id'];
        header('Location: admin_dashboard.php'); // 管理者専用ページへリダイレクト
        exit;
    } else {
        // エラーメッセージ
        $error = '管理者IDまたはパスワードが間違っています';
    }
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>管理者ログイン</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f2f2f2;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .login-container {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .login-container h2 {
            margin-bottom: 20px;
        }
        .login-container input[type="text"],
        .login-container input[type="password"] {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        .login-container input[type="submit"] {
            width: 100%;
            padding: 10px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .login-container input[type="submit"]:hover {
            background-color: #45a049;
        }
        .error-message {
            color: red;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>管理者ログイン</h2>
        <!-- エラーメッセージがある場合に表示 -->
        <?php if (!empty($error)): ?>
            <div class="error-message"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <form action="admin_login.php" method="POST">
            <label for="admin_id">管理者ID:</label>
            <input type="text" id="admin_id" name="admin_id" required>

            <label for="admin_password">パスワード:</label>
            <input type="password" id="admin_password" name="admin_password" required>

            <input type="submit" value="ログイン">
        </form>
    </div>
</body>
</html>
