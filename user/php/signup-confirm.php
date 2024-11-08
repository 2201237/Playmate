<?php
      session_start();
      require 'db-connect.php';

      $sql=$pdo->prepare('select * from users where user_mail=?');
      $sql->execute([$_POST['email']]);

      if ( !empty($sql->fetchAll()) ) {
            $_SESSION['User']['message'] = 'このEメールアドレスを持つアカウントはすでに存在しています';
            header('Location:signup-input.php');
      }
?>
<!DOCTYPE html>
<html lang="ja">

<head>
      <meta charset="UTF-8">
      <meta name="viewport" coFntent="width=device-width, initial-scale=1.0">
      <link rel="stylesheet" href="../css/confirm.css">
      <link rel="stylesheet" href="css/reset.css" />
      <title>PlayMate登録確認</title>
</head>
<body>
      <div class="form-wrapper">
            <h1>Confirm</h1>
            <form action="signup-complete.php" method="post">
                  <input type="hidden" name="name" value="<?php echo $_POST['name']; ?>"></input>
                  <input type="hidden" name="email" value="<?php echo $_POST['email']; ?>"></input>
                  <input type="hidden" name="password" value="<?php echo $_POST['password']; ?>"></input>
                  <input type="hidden" name="profile" value="<?php echo $_POST['profile']; ?>"></input>

                  <div class="form-item">
                        <label for="name"></label>
                        <p>User Name：
                              &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                              <span name="name"><?php echo $_POST['name']; ?></span>
                        </p>
                  </div>
                  <div class="form-item">
                        <label for="email"></label>
                        <p>Email Address：
                              &nbsp;&nbsp;&nbsp;
                              <span name="email"><?php echo $_POST['email']; ?></span>
                        </p>
                  </div>
                  <div class="form-item">
                        <label for="password"></label>
                        <p>Password：
                              &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                              <span name="password"><?php echo $_POST['password']; ?></span>
                        </p>
                  </div>
                  <div class="form-item">
                        <label for="profile"></label>
                        <p>Profile：
                              <br><span name="profile"><?php echo $_POST['profile']; ?></span>
                        </p>
                  </div>
                  <div class="button-panel">
                        <input type="submit" class="button" title="Sign Up" value="Sign UP"></input>
                  </div>
            </form>
            <div class="form-footer">
                  <a href="#" onclick="history.back()" return false;>back</a>
            </div>
      </div>
</body>
</html>