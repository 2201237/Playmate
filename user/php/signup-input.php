<?php session_start(); ?>
<?php require 'db-connect.php'?>
<?php
    $pdo=new PDO($connect,USER,PASS);
    $user_name = $user_mail = $user_pw = $confirm = $user_address = '';
    $error = false; // 追加: エラーの変数を宣言
    $error_message = ''; // エラーメッセージを保持する変数
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $user_name = $_POST['user_name'];
        $user_mail = $_POST['user_mail'];
        $user_profile = $_POST['profile'];
        $user_pw = $_POST['user_pw'];
    }
?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/signup.css">
    <link rel="stylesheet" href="css/reset.css" />
    <title>PlayMate登録</title>
</head>

<body>
    <div class="form-wrapper">
        <h1>Sign Up</h1>
        <form action="signup-confirm.php" method="post">
            <div class="form-item">
                <label for="name"></label>
                <input type="name" name="name" required="required" placeholder="User Name"></input>
            </div>
            <div class="form-item">
                <label for="email"></label>
                <input type="email" name="email" required="required" placeholder="Email Address"></input>
            </div>
            <div class="form-item">
                <label for="password"></label>
                <input type="password" name="password" required="required" placeholder="Password"></input>
            </div>
            <div class="form-item">
                <label for="profile"></label>
                <textarea name="profile" placeholder="Profile"></textarea>
            </div>
            <div class="button-panel">
                <input type="submit" class="button" title="Sign Up" value="Sign UP"></input>
            </div>
        </form>
        <div class="form-footer">
            <a href="#" onclick="history.back()" return false;>back</a>
            <!-- <p><a href="#">Forgot password?</a></p> -->
        </div>
    </div>
</body>

</html>