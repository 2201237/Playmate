<?php session_start(); ?>
<?php require 'db-connect.php'?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/login.css">
    <link rel="stylesheet" href="css/reset.css" />
    <title>PlayMateログイン</title>
</head>
<body>
    <div class="form-wrapper">
        <h1>Sign In</h1>
        <form action="login-output.php" method="post">
            <div class="form-item">
            <label for="email"></label>
            <input type="email" name="email" required="required" placeholder="Email Address"></input>
            </div>
            <div class="form-item">
            <label for="password"></label>
            <input type="password" name="password" required="required" placeholder="Password"></input>
            </div>
            <div class="button-panel">
            <input type="submit" class="button" title="Sign In" value="Sign In"></input>
            </div>
        </form>
        <div class="form-footer">
            <p><a href="signup-input.php">Create an account</a></p>
            <!-- <p><a href="#">Forgot password?</a></p> -->
        </div>
    </div>
</body>
</html>