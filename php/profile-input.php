<?php
      session_start();
      require 'db-connect.php';
      require './home.html';
      $pdo=new PDO($connect,USER,PASS);

      $lastId=$pdo->lastInsertId();
      $sql=$pdo->prepare('select * from users where user_mail=? and ');
      $sql->execute([$_POST['name'], password_hash($_POST['password'], PASSWORD_DEFAULT),$_POST['email']]);
      $user_name = htmlspecialchars($_POST['name']);
      $user_pass = htmlspecialchars($_POST['password']);
      $user_mail = htmlspecialchars($_POST['email']);
      $user_profile = htmlspecialchars($_POST['profile']);
?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="css/profile-input.css">
    <title>Playmate</title>
</head>
<!-- <script>
    function forceReload() {
        location.reload(true); // trueを渡すことでキャッシュを無視して強制的に再読み込み
    }

    // 一定時間ごとに自動的にリロードする例
    // setTimeout(forceReload, 000); // 5000ミリ秒（5秒）ごとに再読み込み
</script> -->

<body>
<header class="header">
    <div class="modal-content">
        <a href="logout.php">ログアウト</a>
    </div>
</div>
</header>
        <table class="profile-input">
            <tr>
                <th colspan="2" class="h1-pro">プロフィール</th>
            </tr>
            <tr>
                <th>名前</th>
                <td><?php echo 'user_name';?></td>
            </tr>
            <tr>
                <th>メールアドレス</th>
                <td><?php echo 'usere_name';?></td>
            </tr>
            <tr>
                <th>自己紹介</th>
                <td><?php echo 'user_prpfile';?><br/>
        </table>

        <div id="logoutModal" class="modal-logout">
            <div class="modal-pro">
                <span class="close">&times;</span>
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

            logoutButton.addEventListener('click', function () {
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