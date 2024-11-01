<?php session_start();?>
<?php require 'db-connect.php'; ?>
<?php

  unset($_SESSION['admins']);
  $pdo=new PDO($connect, USER, PASS);
  $sql=$pdo->prepare('select * from admins where admin_mail=?');
  $sql->execute([$_POST['email']]);

  if (empty($sql->fetchAll())) {
    echo 'メールアドレスが間違っています';
  }

  $sql=$pdo->prepare('select * from admins where admin_mail=?');
  $sql->execute([$_POST['email']]);

  foreach($sql as $row){
    if(password_verify($_POST['password'],$row['admin_pass'])){
      $_SESSION['admins']=[
        'admin_id'=>$row['admin_id'],'admin_name'=>$row['admin_name'],
      ]; 
      header('Location:home.php');
    }else{
      echo 'パスワードが違います';
    }
  }
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/login.css">
    <title>管理者ログイン</title>
</head>
<body>
    <div class="login-container">
        <h1>管理者ログイン</h1>
        <h2>PlayMate</h2>
        <?php if ($error_message): ?>
            <div class="error-message"><?php echo htmlspecialchars($error_message, ENT_QUOTES, 'UTF-8'); ?></div>
        <?php endif; ?>
        <form method="POST" action="">
            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES, 'UTF-8'); ?>">
            <div class="form-group">
                <label for="admin_id">管理者ID</label>
                <input type="text" id="admin_id" name="admin_id" required>
            </div>
            <div class="form-group">
                <label for="password">パスワード</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit">ログイン</button>
        </form>
    </div>
</body>
</html>
