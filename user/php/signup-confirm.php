<?php
      session_start();
      require 'db-connect.php';
      
      $pdo=new PDO($connect, USER, PASS);
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
            <h1>登録確認</h1>
            <form action="signup-complete.php" method="post">
                  <input type="hidden" name="name" value="<?php echo $_POST['name']; ?>"></input>
                  <input type="hidden" name="email" value="<?php echo $_POST['email']; ?>"></input>
                  <input type="hidden" name="password" value="<?php echo $_POST['password']; ?>"></input>
                  <input type="hidden" name="profile" value="<?php echo $_POST['profile']; ?>"></input>

                  <div class="form-item">
                        <label for="name"></label>
                        <div class="signup_label">お名前：</div>
                        <div class="signup_input" name="name"><?php echo $_POST['name']; ?></div>
                  </div>
                  <div class="form-item">
                        <label for="email"></label>
                        <div class="signup_label">Eメールアドレス：</div>
                        <div class="signup_input" name="email"><?php echo $_POST['email']; ?></div>
                  </div>
                  <div class="form-item">
                        <label for="password"></label>
                        <div class="signup_label">パスワード：</div>
                        <div class="signup_input" name="password"><?php echo $_POST['password']; ?></div>
                  </div>
                  <div class="form-item">
                        <label for="profile"></label>
                        <div class="signup_label">プロフィール：</div>
                        <div class="signup_input_profile" name="profile"><?php echo $_POST['profile']; ?></div>
                  </div>
                  <div class="button-panel">
                        <input type="submit" class="button" title="Sign Up" value="登録"></input>
                  </div>
            </form>
            <div class="form-footer">
                  <a href="#" onclick="history.back()" return false;>戻る</a>
            </div>
      </div>
</body>
</html>