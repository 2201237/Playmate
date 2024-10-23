<?php
      session_start();
      require 'db-connect.php';
?>
<!DOCTYPE html>
<html lang="ja">
<head>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <title>Document</title>
</head>
<body>
<div class="form-wrapper">
        <h1>Sign Up</h1>
        <form action="signup-complete.php" method="post">
        <input type="hidden" name="name" value= "<?php echo $_POST['name']; ?>"></input>
        <input type="hidden" name="email" value= "<?php echo $_POST['email']; ?>"></input>
        <input type="hidden" name="password" value= "<?php echo $_POST['password']; ?>"></input>
        <input type="hidden" name="profile" value= "<?php echo $_POST['profile']; ?>"></input>

            <div class="form-item">
                  <p>ユーザー名 <?php echo $_POST['name']; ?></p>
            </div>
            <div class="form-item">
                  <p>メールアドレス <?php echo $_POST['email']; ?></p>
            </div>
            <div class="form-item">
                  <p>パスワード <?php echo $_POST['password']; ?></p>
            </div>
            <div class="form-item">
                  <p>プロフィール <?php echo $_POST['profile']; ?></p>
            </div>
            
            <div class="button-panel">
                <input type="submit" class="button" title="Sign Up" value="Sign UP"></input>
            </div>
      </form>
      <div>
                <button type="submit" class="back"  onclick=history.back()>戻る</button>
      </div>
</body>
</html>