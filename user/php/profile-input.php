<?php
    session_start();
    require 'db-connect.php';
    $pdo = new PDO($connect, USER, PASS);

    $userId = $_SESSION['User']['user_id'];
    $userName = $_SESSION['User']['user_name'];
    $userProfile = isset($_SESSION['User']['user_profile']) ? $_SESSION['User']['user_profile'] : '';
    $userMail = isset($_SESSION['User']['user_mail']) ? $_SESSION['User']['user_mail'] : '';

    $iconPath = $_SESSION['User']['user_icon'];

    $cacheBuster = file_exists($iconPath) ? filemtime($iconPath) : time(); 
?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="../img/favicon.ico">
    <link rel="stylesheet" href="../css/header.css">
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" type="text/css" href="../css/profile-input.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <title>Playmate</title>
</head>

<body>

    <?php require 'header.php'; ?>

    <form action='profile-edit.php' method='post'>

    <?php

        echo "<div class = 'profile-icon'>";
                echo "<input type = 'hidden' name = '" . $iconPath . "' value = '" . $iconPath . "'></input>";
                echo "<img src='".$iconPath. "' class='icon_user'>";
        echo "</div>";

        echo "<p class = 'user' >" . $userName . "</p>";
        echo "<p class = 'user' >" . $userMail . "</p>";

        echo '<p class = "profile-p" >自己紹介</p>';
            if(isset($userProfile) && $userProfile !== ''){

                echo '<textarea rows="4" cols="50" class = "profile-area">' .$userProfile. '</textarea>';
                echo '<br>';
            }else{
                echo '<textarea rows="4" cols="50" class = "profile-area" readonly placeholder="プロフィールは未設定です">';
                echo '</textarea><br>';
            }
        echo "<input type='submit' class='edit' value='編集'>";
   ?>
   </form>

   <div class="modal-content">
        <!-- ログアウトボタンにIDを追加 -->
        <a href="" id="logoutButton">ログアウト</a>
    </div>


    <div id="logoutModal" class="modal-logout">
        <spqn class = "close"></span>
        <div class="modal-pro">
            <p>本当にログアウトしますか？</p>
            <form action="logout-output.php" method="post">
                <button type="submit" class="confirm-button">はい</button>
                <button type="button" class="cancel-button">いいえ</button>
            </form>
        </div>
    </div>

    <script>
        // ログアウトボタンをクリックしたときの処理
        const logoutButton = document.getElementById('logoutButton');
        const logoutModal = document.getElementById('logoutModal');
        const closeButton = document.querySelector('.close');
        const cancelButton = document.querySelector('.cancel-button');

        logoutButton.addEventListener('click', function (event) {
            event.preventDefault(); // デフォルトのリンク動作を防ぐ
            logoutModal.style.display = 'block'; // モーダルを表示
        });

        closeButton.addEventListener('click', function () {
            logoutModal.style.display = 'none'; // モーダルを非表示
        });

        cancelButton.addEventListener('click', function () {
            logoutModal.style.display = 'none'; // モーダルを非表示
        });

        window.addEventListener('click', function (event) {
            if (event.target == logoutModal) {
                logoutModal.style.display = 'none'; // モーダルを非表示
            }
        });
    </script>
        <script src="../js/header.js"></script>

</body>
</html>
