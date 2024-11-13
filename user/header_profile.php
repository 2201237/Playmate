<?php
session_start();

require 'php/db-connect.php';
$pdo = new PDO($connect, USER, PASS);


// セッションからアイコンのパスを取得
$userIcon = isset($_SESSION['User']['icon']) ? $_SESSION['User']['icon'] : '../img/icon_user.png';
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <title>ホーム</title>
    <link rel="stylesheet" href="../css/header_profile.css" />
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script type="text/javascript" src="js/header.js"></script>
</head>
<body>
    <div class="header">
            <img src="../img/logo.png" class="logo" width="180" height="">
        <a href="./profile-input.php">
        <?php 
           if (isset($userIcon) && $userIcon !== '') {

            echo "<img src='".$userIcon."' class='icon_user' width='50' height='50'>";
            } else {
                echo "<img src='../img/icon_user.png' class='icon_user' width='50' height='50'>";
            }
        ?>
        </a>

        <nav  class = "header-side">
            <ul class = "header-ul " >
                <li class = "header-li"><a class=”current” href="home.php">ホーム</a></li>
                <li class = "header-li"><a class=”current” href="tournament-list.php">大会一覧</a></li>
                <li class = "header-li"><a class=”current” href="#">掲示板</a></li>
                <li class = "header-li"><a class=”current” href="#">ランキング</a></li>
                <li class = "header-li"><a class=”current” href="infomation-input.php">お問い合わせ</a></li>
            </ul>
        </nav>
    </div>
    <div class = "profile-header">
        <nav class = "profile-nav">
            <ul class = "profile-ul">
                <li class = "profile"><a class = "profile" href="profile-input.php">プロフィール</a></li>
                <li class = "profile"><a class = "profile" href="#">フォロー</a></li>
                <li class = "profile"><a class = "profile" href="#">フォロワー</a></li>
            </ul>
        </nav>
    </div>
</body>
</html>