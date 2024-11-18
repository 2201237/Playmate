<?php session_start(); ?>
<?php require 'db-connect.php'?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="../img/favicon.ico">
    <link rel="stylesheet" href="../css/signup.css">
    <link rel="stylesheet" href="css/reset.css" />
    <title>PlayMate登録</title>
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
        <h1>新規登録</h1>
        <form action="signup-confirm.php" method="post">
            <div class="form-item">
                <label for="name"></label>
                <input type="name" name="name" required="required" placeholder="お名前"></input>
            </div>
            <div class="form-item">
                <label for="email"></label>
                <input type="email" name="email" required="required" placeholder="Eメールアドレス"></input>
            </div>
            <div class="form-item">
                <label for="password"></label>
                <input type="password" name="password" required="required" placeholder="パスワード"></input>
            </div>
            <div class="form-item">
                <label for="profile"></label>
                <textarea name="profile" placeholder="プロフィール"></textarea>
            </div>
            <div class="button-panel">
                <input type="submit" class="button" title="Sign Up" value="次へ"></input>
            </div>
        </form>
        <div class="form-footer">
            <a href="#" onclick="history.back()" return false;>戻る</a>
            <!-- <p><a href="#">Forgot password?</a></p> -->
        </div>
    </div>
</body>
</html>
<?php unset($_SESSION['User']['message']); ?>