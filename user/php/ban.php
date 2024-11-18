<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="../img/favicon.ico">
    <link rel="stylesheet" href="../css/notlogin.css">
    <title>BANされています</title>
</head>
<body>
    <h1>あなたはBANされています</h1>
    <p>3秒後にログインページにリダイレクトされます。</p>
</body>
<script>
    // 3秒後にログインページにリダイレクト
    setTimeout(function() {
        window.location.href = 'login-input.php';
    }, 3000);
</script>
</html>