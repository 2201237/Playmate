<?php
session_start();
require 'db-connect.php';
$pdo = new PDO($connect, USER, PASS);

// セッションからデータを取得
$iconPath = $_SESSION['User']['user_icon'];
$userId = $_SESSION['User']['user_id'];
$userName = $_SESSION['User']['user_name'];
$userPass = isset($_SESSION['User']['user_pass']) ? $_SESSION['User']['user_pass'] : '';
$userProfile = isset($_SESSION['User']['user_profile']) ? $_SESSION['User']['user_profile'] : '';
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Playmate</title>
    <link rel="stylesheet" href="../css/profile-edit.css">

</head>
<body>
<input type = 'hidden' name = 'user_id' value = "<?php echo $userId; ?>"></input>

    <div  style='text-align:center'>
        <form action="profile-fin.php" method="post" enctype="multipart/form-data">

        <input type='hidden' name='user_id' value='<?php echo $userId; ?>'>

            <label for="fileInput" id="fileLabel">画像を選択：</label><br>
            <input type="file" name="icon" id="fileInput" accept="image/*" onchange="previewImage(event)">
            <?php
                echo '<img id="preview" src="'.$iconPath.'" alt="Profile Image" width="50" height="50" ><br>';
            ?>

        <label>
            <input type="checkbox" id="useDefaultIcon" name="use_default_icon" onclick="toggleDefaultIcon()"> デフォルトアイコンを使用する
        </label><br>
            
            <label for="user_name">User Name：</label>
            <input type="text" name="user_name" value="<?php echo $userName; ?>" required="required" id="user_name" placeholder="User Name" pattern=".*\S+.*"><br>
            
            <label for="password">Password：</label>
            <input type="password" name="user_pass" value="<?php echo $userPass; ?>" id="password" required="required" placeholder="Password" pattern=".*\S+.*">
            <button type="button" id="togglePassword">👁️</button><br>
            
            <label for="profile">Profile：</label><br>
            <textarea name="profile" id="profile" placeholder="User Profile" pattern=".*\S+.*"><?php echo $userProfile; ?></textarea><br>

            <input type='submit' class='input' name = "upload" value='編集完了'>

            </form>
            <a href="#" onclick="window.history.back(); return false;">戻る</a><br>


            <script>
                // プレビュー画像表示のJavaScript
        function previewImage(event) {
            const reader = new FileReader();
            reader.onload = function () {
                const preview = document.getElementById('preview');
                preview.src = reader.result;
            };
            reader.readAsDataURL(event.target.files[0]);

            // チェックボックスを無効化
            document.getElementById("useDefaultIcon").checked = false;
        }

        // デフォルトアイコンの表示切替
        function toggleDefaultIcon() {
            const preview = document.getElementById('preview');
            const fileInput = document.getElementById('fileInput');

            if (document.getElementById("useDefaultIcon").checked) {
                preview.src = 'https://aso2201222.kill.jp/Playmate/user/img/icon_user.png'; // デフォルトアイコン表示
                fileInput.value = ''; // アップロード選択解除
            }
        }

                // 表示/非表示切替のJavaScript
                const passwordField = document.getElementById("password");
                const togglePasswordButton = document.getElementById("togglePassword");

                togglePasswordButton.addEventListener("click", function () {
                    const type = passwordField.getAttribute("type") === "password" ? "text" : "password";
                    passwordField.setAttribute("type", type);
                    this.textContent = type === "password" ? "👁️" : "🙈";
                });
            </script>


    </div>
</body>
</html>
