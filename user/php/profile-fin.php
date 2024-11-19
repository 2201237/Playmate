<?php 
session_start();
require 'db-connect.php';
$pdo = new PDO($connect, USER, PASS);

$userId = $_SESSION['User']['user_id'];
$userIcon = isset($_SESSION['User']['icon']) ? $_SESSION['User']['icon'] : '../img/icon_user.png';
$userName = $_SESSION['User']['user_name'];
$userProfile = isset($_SESSION['User']['user_profile']) ? $_SESSION['User']['user_profile'] : '';
$userMail = isset($_SESSION['User']['user_mail']) ? $_SESSION['User']['user_mail'] : '';

// プロフィール更新処理
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['user_name'], $_POST['profile'], $_POST['user_pass'])) {
    $userName = $_POST['user_name'];
    $userProfile = $_POST['profile'];
    $userPass = password_hash($_POST['user_pass'], PASSWORD_DEFAULT);

    // アイコンの初期値は現在のアイコン
    $iconPath = $userIcon;

    // デフォルトアイコンの選択を確認
    if (isset($_POST['use_default_icon'])) {
        $iconPath = '../img/icon_user.png'; // デフォルトアイコンを使用
    } else {
        // 画像がアップロードされた場合は処理
        if (isset($_FILES['icon']) && $_FILES['icon']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = '../user_images/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }
            $fileTmpPath = $_FILES['icon']['tmp_name'];
            $fileExtension = pathinfo($_FILES['icon']['name'], PATHINFO_EXTENSION);
            $newFileName = uniqid() . '.' . $fileExtension;
            $destPath = $uploadDir . $newFileName;

            if (move_uploaded_file($fileTmpPath, $destPath)) {
                $iconPath = $destPath;
            } else {
                echo "画像のアップロードに失敗しました。";
            }
        }
    }

    // データベースを更新
    $stmt = $pdo->prepare('UPDATE users SET user_name = ?, profile = ?, user_pass = ?, icon = ? WHERE user_id = ?');
    $stmt->execute([$userName, $userProfile, $userPass, $iconPath, $userId]);

    // セッションを更新
    $_SESSION['User']['user_name'] = $userName;
    $_SESSION['User']['user_profile'] = $userProfile;
    $_SESSION['User']['icon'] = $iconPath;

    $_SESSION['update_message'] = "更新できました";
    header('Location: profile-input.php');
    exit();
}
?>
