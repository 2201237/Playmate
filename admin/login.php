<?php
session_start();

// すでにログインしている場合はダッシュボードにリダイレクト
if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
    header('Location: admin_dashboard.php');
    exit;
}

// CSRF対策のトークンを生成
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

require 'db-connect.php';

try {
    $pdo = new PDO(
        "mysql:host=$host;dbname=$dbname;charset=utf8mb4",
        $username,
        $password,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false
        ]
    );
} catch (PDOException $e) {
    error_log('データベース接続エラー: ' . $e->getMessage());
    die('システムエラーが発生しました。しばらく時間をおいてから再度お試しください。');
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // CSRF対策のトークン検証
    if (!hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'] ?? '')) {
        die('不正なリクエストです。');
    }

    // 入力値の検証
    $admin_id = filter_input(INPUT_POST, 'admin_id', FILTER_SANITIZE_STRING);
    $admin_password = filter_input(INPUT_POST, 'admin_password', FILTER_UNSAFE_RAW);

    if (!$admin_id || !$admin_password) {
        $error = '全ての項目を入力してください。';
    } else {
        try {
            $stmt = $pdo->prepare("SELECT * FROM administrators WHERE admin_id = :admin_id LIMIT 1");
            $stmt->bindParam(':admin_id', $admin_id, PDO::PARAM_STR);
            $stmt->execute();
            $admin = $stmt->fetch();

            if ($admin && password_verify($admin_password, $admin['password'])) {
                // ログイン成功時の処理
                session_regenerate_id(true); // セッションIDの再生成
                $_SESSION['admin_logged_in'] = true;
                $_SESSION['admin_id'] = $admin['admin_id'];
                $_SESSION['last_activity'] = time();

                // ログイン試行回数をリセット
                if (isset($_SESSION['login_attempts'])) {
                    unset($_SESSION['login_attempts']);
                }

                header('Location: admin_dashboard.php');
                exit;
            } else {
                // ログイン試行回数を記録
                $_SESSION['login_attempts'] = ($_SESSION['login_attempts'] ?? 0) + 1;
                
                // 一定回数以上の失敗でログインを一時的にブロック
                if ($_SESSION['login_attempts'] >= 5) {
                    $error = 'ログイン試行回数が上限を超えました。しばらく時間をおいてから再度お試しください。';
                    sleep(3); // ブルートフォース攻撃対策
                } else {
                    $error = '管理者IDまたはパスワードが間違っています';
                }
            }
        } catch (PDOException $e) {
            error_log('ログインエラー: ' . $e->getMessage());
            $error = 'システムエラーが発生しました。';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-Content-Type-Options" content="nosniff">
    <meta http-equiv="X-Frame-Options" content="DENY">
    <title>管理者ログイン</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Arial, sans-serif;
            background-color: #f5f5f5;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            padding: 15px;
        }
        .login-container {
            background-color: white;
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
        }
        .login-container h2 {
            margin: 0 0 1.5rem;
            color: #333;
            text-align: center;
        }
        .form-group {
            margin-bottom: 1rem;
        }
        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            color: #555;
        }
        .form-control {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
            font-size: 1rem;
        }
        .form-control:focus {
            border-color: #4CAF50;
            outline: none;
            box-shadow: 0 0 0 2px rgba(76, 175, 80, 0.2);
        }
        .btn-login {
            width: 100%;
            padding: 0.75rem;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 1rem;
            transition: background-color 0.2s;
        }
        .btn-login:hover {
            background-color: #45a049;
        }
        .error-message {
            color: #dc3545;
            background-color: #f8d7da;
            border: 1px solid #f5c6cb;
            padding: 0.75rem;
            border-radius: 4px;
            margin-bottom: 1rem;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>管理者ログイン</h2>
        <?php if (!empty($error)): ?>
            <div class="error-message"><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></div>
        <?php endif; ?>
        
        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF'], ENT_QUOTES, 'UTF-8'); ?>" method="POST">
            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES, 'UTF-8'); ?>">
            
            <div class="form-group">
                <label for="admin_id">管理者ID</label>
                <input type="text" id="admin_id" name="admin_id" class="form-control" 
                       required autocomplete="username">
            </div>

            <div class="form-group">
                <label for="admin_password">パスワード</label>
                <input type="password" id="admin_password" name="admin_password" class="form-control" 
                       required autocomplete="current-password">
            </div>

            <button type="submit" class="btn-login">ログイン</button>
        </form>
    </div>
</body>
</html>