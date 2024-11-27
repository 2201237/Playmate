<?php 
session_start();
require 'db-connect.php';
$pdo = new PDO($connect, USER, PASS);

$userId = $_SESSION['User']['user_id'];
$userIcon = isset($_SESSION['User']['icon']) ? 'https://aso2201222.kill.jp/'. $_SESSION['User']['icon'] : '../img/icon_user.png';
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

    if (isset($_POST['use_default_icon'])) {
        $iconPath = '../img/icon_user.png'; // デフォルトアイコンを使用
    } else {
        if (isset($_FILES['icon']) && $_FILES['icon']['error'] === UPLOAD_ERR_OK) {
            // FTPサーバーの情報
            $ftpHost = 'ftp.aso2201222.kill.jp'; // FTPサーバーのホスト名
            $ftpUser = 'kill.jp-aso2201222';       // FTPユーザー名
            $ftpPass = 'Pass0830';   // FTPパスワード
            $ftpDir  = 'Playmate/user/user_images/'; // アップロード先ディレクトリ
<<<<<<< HEAD
<<<<<<< HEAD
 
 
=======
>>>>>>> ad1cf0bf8e3e11dd237b8ad92cc3d8e2b01cd933
=======
>>>>>>> ad1cf0bf8e3e11dd237b8ad92cc3d8e2b01cd933

            // ファイル情報
            $fileTmpPath = $_FILES['icon']['tmp_name'];
            $originalFileName = basename($_FILES['icon']['name']);
            $fileExtension = pathinfo($originalFileName, PATHINFO_EXTENSION);
            $uniqueFileName = uniqid() . '.' . $fileExtension;

            // アップロード先のパス
            $remoteFilePath = $ftpDir . $uniqueFileName;

            // FTP接続とアップロード
            $ftpConnection = ftp_connect($ftpHost);
            if (!$ftpConnection) {
                echo "FTP接続に失敗しました。";
                exit;
            }

            // ログイン
            if (!ftp_login($ftpConnection, $ftpUser, $ftpPass)) {
                echo "FTPログインに失敗しました。";
                ftp_close($ftpConnection);
                exit;
            }

            // パッシブモードを有効化
            ftp_pasv($ftpConnection, true);

            // ファイルアップロード
            if (ftp_put($ftpConnection, $remoteFilePath, $fileTmpPath, FTP_BINARY)) {
                $iconPath = $remoteFilePath; // アップロードしたファイルのパスを保存
            } else {
                echo "ファイルアップロードに失敗しました。";
            }

            // 接続を閉じる
            ftp_close($ftpConnection);
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
    

    // 画像がアップロードされた場合は処理
    //     if (isset($_FILES['icon']) && $_FILES['icon']['error'] === UPLOAD_ERR_OK) {
    //         $uploadDir = '../user_images/';
    //         if (!is_dir($uploadDir)) {
    //             mkdir($uploadDir, 0777, true);
    //         }

    //         // アップロードされた元のファイル名を取得
    //         $originalFileName = basename($_FILES['icon']['name']);
    //         $destPath = $uploadDir . $originalFileName;

    //         // ファイル名が重複していた場合、既存ファイルを削除
    //         if (file_exists($destPath)) {
    //             unlink($destPath);
    //         }

    //         // ファイルを移動
    //         if (move_uploaded_file($_FILES['icon']['tmp_name'], $destPath)) {
    //             $iconPath = $destPath;
    //         } else {
    //             echo "画像のアップロードに失敗しました。";
    //         }
    //     }

}
?>

