<?php session_start(); ?>
<?php require 'db-connect.php'?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="../img/favicon.ico">
    <link rel="stylesheet" href="../css/login.css">
    <title>PlayMateログイン</title>
</head>
<body>
    <!-- エラーメッセージある場合表示される -->
    <?php
        if(isset( $_SESSION['User']['message'] )){
            echo '<div class="msg_area">',
                    '<img src="../img/err_exm.png" class="err_exm">',
                    '<span class="err_con">ご確認ください</span><br>',
                    '<span class="err_msg">',$_SESSION['User']['message'],'</span>',
                    '</div>';
        }
    ?>
    <div class="form-wrapper">
        <h1>ログイン</h1>
        <form action="login-output.php" method="post">
            <div class="form-item">
                <label for="email"></label>
                <input type="email" name="email" required="required" placeholder="Eメールアドレス"></input>
            </div>
            <div class="form-item">
                <label for="password"></label>
                <input type="password" name="password" required="required" placeholder="パスワード"></input>
            </div>
                <div class="button-panel">
                <input type="submit" class="button" title="Sign In" value="ログイン"></input>
            </div>
        </form>
        <div class="form-footer">
            <p><a href="signup-input.php">新規作成</a></p>
            <!-- <p><a href="#">Forgot password?</a></p> -->
        </div>
    </div>
</body>
</html>
<?php unset($_SESSION['User']['message']); ?>