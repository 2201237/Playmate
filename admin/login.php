<?php
session_start();
require 'db-connect.php';

// ログイン済みの場合はダッシュボードへリダイレクト
if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
    header('Location: dashboard.php');
    exit;
}

// ログイン処理
$error_message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $admin_id = filter_input(INPUT_POST, 'admin_id', FILTER_SANITIZE_STRING);
        $password = $_POST['password'];

        $stmt = $pdo->prepare('SELECT * FROM administrators WHERE admin_id = ?');
        $stmt->execute([$admin_id]);
        $admin = $stmt->fetch();

        if ($admin && password_verify($password, $admin['password_hash'])) {
            // ログイン成功
            $_SESSION['admin_logged_in'] = true;
            $_SESSION['admin_id'] = $admin['id'];
            
            // 最終ログイン日時を更新
            $stmt = $pdo->prepare('UPDATE administrators SET last_login = NOW() WHERE id = ?');
            $stmt->execute([$admin['id']]);

            header('Location: dashboard.php');
            exit;
        } else {
            $error_message = '管理者IDまたはパスワードが正しくありません。';
        }
    } catch (PDOException $e) {
        $error_message = 'システムエラーが発生しました。';
        error_log($e->getMessage());
    }
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
<link rel="stylesheet" href="css/login.css" />
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>管理者ログイン</title>
</head>
<body>
    <div class="login-container">
        <h1>管理者ログイン</h1>
        <?php if ($error_message): ?>
            <div class="error-message"><?php echo htmlspecialchars($error_message, ENT_QUOTES, 'UTF-8'); ?></div>
        <?php endif; ?>
        <form method="POST" action="">
            <div class="form-group">
                <label for="admin_id">管理者ID</label>
                <input type="text" id="admin_id" name="admin_id" required>
            </div>
            <div class="form-group">
                <label for="password">パスワード</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit">ログイン</button>
        </form>
    </div>
</body>
</html>