<?php
session_start();
require 'db-connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die('不正なリクエストです。');
    }

    unset($_SESSION['admins']);
    $error_message = '';

    try {
        $pdo = new PDO($connect, USER, PASS);
        $sql = $pdo->prepare('SELECT * FROM admins WHERE admin_id = ?');
        $sql->execute([$_POST['admin_id']]);
        $row = $sql->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            // データベースのパスワードがハッシュ化されている場合
            // if (password_verify($_POST['password'], $row['admin_pass'])) {
            //    ↓↓ ハッシュ化されていない場合はこちらを使用
            if ($_POST['password'] === $row['admin_pass']) {
                $_SESSION['admins'] = [
                    'admin_id' => $row['admin_id'],
                    'admin_name' => $row['admin_name']
                ];
                header('Location: admintop.php');
                exit;
            } else {
                $error_message = 'パスワードまたは管理者IDが間違っています';
            }
        } else {
            $error_message = 'パスワードまたは管理者IDが間違っています';
        }
    } catch (PDOException $e) {
        $error_message = 'データベースエラーが発生しました: ' . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8');
    }
}

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/login.css">
    <title>管理者ログイン</title>
</head>
<body>
    <div class="login-container">
        <h1>管理者ログイン</h1>
        <h2>PlayMate</h2>
        <?php if (!empty($error_message)): ?>
            <div class="error-message"><?php echo htmlspecialchars($error_message, ENT_QUOTES, 'UTF-8'); ?></div>
        <?php endif; ?>
        <form method="POST" action="">
            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES, 'UTF-8'); ?>">
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
