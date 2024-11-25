<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/reply.css">
    <title>お問い合わせ返信</title>
</head>
<body>
<form method="POST" action="reply-output.php" class="message-form">
<textarea name="message" placeholder="メッセージ入力"></textarea>
    <div class="input-container">
        <input type = "hidden" name = "reply_id" value = "<?php echo $_GET['contacts_id']; ?> ">
    <button type="submit">送信</button>
    </div>
</form>
</body>
</html>