<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/notlogin.css">
    <title>ログインが必要です</title>
</head>
<body>
    <h1>ログインが必要です</h1>
    <p>このページを閲覧するにはログインが必要です。</p>
    <p>数秒後にログインページにリダイレクトされます。</p>
    <a href="login.php">今すぐログインする</a>
</body>
<script>
    // 3秒後にログインページにリダイレクト
    setTimeout(function() {
        window.location.href = 'login.php';
    }, 3000);
</script>
</html>