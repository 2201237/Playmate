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
    <div class="modal-content">
        <!-- ログアウトボタンにIDを追加 -->
        <a href="" id="logoutButton">ログアウト</a>
    </div>

    <form action='profile-edit.php' method='post'>

    <?php
        
        echo "<input type = 'hidden' name = '" . $_SESSION['User']['user_id'] . "' value = '" . $_SESSION['User']['user_id'] . "'></input>";
        echo "<input type = 'hidden' name = '" . $_SESSION['User']['user_name'] . "' value = '" . $_SESSION['User']['user_name'] . "'></input>";
        


        echo "<p class = 'profile-icon'>";
            if (isset($_SESSION['User']['icon']) && $_SESSION['User']['icon'] !== '') {
                echo "<input type = 'hidden' name = '" . $_SESSION['User']['icon'] . "' value = '" . $_SESSION['User']['icon'] . "'></input>";

                echo "<img src='".$_SESSION['User']['icon']."' class='icon_user' width='50' height='50'>";
            } else {
                echo "<img src='../img/icon_user.png' class='icon_user' width='50' height='50'>";
            }
        echo "</p>";

        echo "<p class = 'user' >" . $_SESSION['User']['user_name'] . "</p>";
        echo "<p class = 'user' >" . $_SESSION['User']['user_mail'] . "</p>";

        echo '<p class = "profile-p" >自己紹介</p>';
            if(isset($_SESSION['User']['profile']) && $_SESSION['User']['profile'] !== ''){
                echo '<input type = "hidden" name = "' . $_SESSION['User']['profile'] . '" value = "' . $_SESSION['User']['profile'] . '"></input>';

                echo '<textarea rows="4" cols="50" class = "profile-area" readonly value = "' . $_SESSION['User']['profile'] . '">';
                echo '</texrarea><br>';
            }else{
                echo '<textarea rows="4" cols="50" class = "profile-area" readonly placeholder="プロフィールは未設定です">';
                echo '</textarea><br>';
            }
        echo "<input type='submit' class='edit' value='Profile edit'>";
        
   ?>
   </form>


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
</body>
</html>
