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

    <?php
    echo "<form action='profile-edit.php' method='post'>";
    echo $_POST['email'] . "<br>";
    echo $_POST['name'] . "<br>";
    echo $_POST['profile'] . "<br>";
    echo "<input type='submit' class='edit' value='Profile edit'>";
    echo "</form>";
    ?>

    <div id="logoutModal" class="modal-logout">
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
