<?php
require '../header_profile.php';
$pdo = new PDO($connect, USER, PASS);

$userId = $_SESSION['User']['user_id'];
$userIcon = isset($_SESSION['User']['icon']) ? $_SESSION['User']['icon'] : '../img/icon_user.png';
$userName = $_SESSION['User']['user_name'];
$userProfile = isset($_SESSION['User']['user_profile']) ? $_SESSION['User']['user_profile'] : '';
$userMail = isset($_SESSION['User']['user_mail']) ? $_SESSION['User']['user_mail'] : '';
$iconPath = isset($_SESSION['User']['icon']) ? $_SESSION['User']['icon'] : '';
$cacheBuster = file_exists($iconPath) ? filemtime($iconPath) : time();


// // プロフィール更新処理
// if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['user_name'], $_POST['profile'], $_POST['user_pass'])) {
//     $userName = $_POST['user_name'];
//     $userProfile = $_POST['profile'];
//     $userPass = password_hash($_POST['user_pass'], PASSWORD_DEFAULT);

//     // デフォルトアイコンのパスを取得または画像のアップロード処理
//     if (isset($_POST['use_default_icon'])) {
//         $iconPath = '../img/icon_user.png';
//     } else {
//         if (isset($_FILES['icon']) && $_FILES['icon']['error'] === UPLOAD_ERR_OK) {
//             $uploadDir = '../user_images/';
//             if (!is_dir($uploadDir)) {
//                 mkdir($uploadDir, 0777, true);
//             }
//             $fileTmpPath = $_FILES['icon']['tmp_name'];
//             $fileExtension = pathinfo($_FILES['icon']['name'], PATHINFO_EXTENSION);
//             $newFileName = uniqid() . '.' . $fileExtension;
//             $destPath = $uploadDir . $newFileName;

//             if (move_uploaded_file($fileTmpPath, $destPath)) {
//                 $iconPath = $destPath;
//             } else {
//                 echo "画像のアップロードに失敗しました。";
//             }
//         }
//     }

//     // データベースを更新
//     $stmt = $pdo->prepare('UPDATE users SET user_name = ?, profile = ?, user_pass = ?, icon = ? WHERE user_id = ?');
//     $stmt->execute([$userName, $userProfile, $userPass, $iconPath ?? $userIcon, $userId]);

//     // セッションを更新
//     $_SESSION['User']['user_name'] = $userName;
//     $_SESSION['User']['user_profile'] = $userProfile;
//     $_SESSION['User']['icon'] = $iconPath ?? $userIcon;

//     $_SESSION['update_message'] = "更新できました";

// }

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
        
        // echo "<input type = 'hidden' name = '" . $_SESSION['User']['user_id'] . "' value = '" . $_SESSION['User']['user_id'] . "'></input>";
        // echo "<input type = 'hidden' name = '" . $_SESSION['User']['user_name'] . "' value = '" . $_SESSION['User']['user_name'] . "'></input>";
        


        echo "<div class = 'profile-icon'>";
            if (isset($iconPath) && $iconPath !== '') {
                echo "<input type = 'hidden' name = '" . $iconPath . "' value = '" . $iconPath . "'></input>";

                echo "<img src='".$iconPath."' class='icon_user' width='50' height='50'>";
            } else {
                echo "<img src='../img/icon_user.png' class='icon_user' width='50' height='50'>";
            }

        echo "<p class = 'user' >" . $userName . "</p>";
        echo "<p class = 'user' >" . $userMail . "</p>";

        echo '<p class = "profile-p" >自己紹介</p>';
            if(isset($userProfile) && $userProfile !== ''){
                // echo '<input type = "hidden" name = "' . $_SESSION['User']['profile'] . '" value = "' . $_SESSION['User']['profile'] . '"></input>';

                echo '<textarea rows="4" cols="50" class = "profile-area">' .$userProfile. '</textarea>';
                echo '<br>';
            }else{
                echo '<textarea rows="4" cols="50" class = "profile-area" readonly placeholder="プロフィールは未設定です">';
                echo '</textarea><br>';
            }
        echo "<input type='submit' class='edit' value='Profile edit'>";
        echo "</div>";
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
