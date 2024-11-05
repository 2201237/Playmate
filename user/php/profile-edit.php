<?php
      session_start();
      require 'db-connect.php';
      require '../header_profile.html';
      $pdo=new PDO($connect,USER,PASS);

      $lastId=$pdo->lastInsertId();
      $sql=$pdo->prepare('select * from users');
      $sql->execute();
?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="../css/profile-input.css">
    <title>Playmate</title>
</head>

<body>

    <?php
    echo "<form action='profile-edit.php' method='post'>";
    echo "<p class = 'profile-icon'>";
    echo isset($_SESSION['User']['icon']) && $_SESSION['User']['icon'] !== '' ? $_SESSION['User']['icon'] : "<img src='../img/icon_user.png' class='icon_user' width='50' height='50'>";
    echo "</p>";
    echo "<p class = 'user' >" . $_SESSION['User']['user_name'] . "</p>";
    echo "<p class = 'user' >" . $_SESSION['User']['user_mail'] . "</p>";
    $profileText = isset($_SESSION['User']['profile']) && $_SESSION['User']['profile'] !== '' ? $_SESSION['User']['profile'] : "";
    echo '<p class = "profile-p" >自己紹介</p>';
    echo '<textarea rows="4" cols="50" class = "profile-area" readonly placeholder="プロフィールは未設定です">';
    echo $profileText;
    echo '</textarea><br>';
    echo "<input type='submit' class='edit' value='Profile edit'>";
    echo "</form>";
    ?>

</body>
</html>
