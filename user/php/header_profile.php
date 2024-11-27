<?php
session_start();

require 'db-connect.php';
$pdo = new PDO($connect, USER, PASS);
$current_page = basename($_SERVER['REQUEST_URI']);

// セッションからアイコンのパスを取得
$userIcon = isset($_SESSION['User']['icon']) ? 'https://aso2201222.kill.jp/'.$_SESSION['User']['icon'] : '../img/icon_user.png';
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
                <li class = "header-li"><nobr><a class=”current” href="home.php">ホーム</a></nobr></li>
                <li class = "header-li"><nobr><a class=”current” href="tournament-list.php">大会一覧</a></nobr></li>
                <li class = "header-li"><nobr><a class=”current” href="#">掲示板</a></nobr></li>
                <li class = "header-li"><nobr><a class=”current” href="#">ランキング</a></nobr></li>
                <li class = "header-li"><nobr><a class=”current” href="infomation-input.php">お問い合わせ</a></nobr></li>
                <form action="search.php" class = "search" method="get">
                    <input type="text" id="username" class = "stext" name="username" placeholder="ユーザー名を検索">
                    <button type="submit" class = "sbut">🔍</button>
                </form>
            </ul>
        </nav>
    </div>
    <div class = "profile-header">
        <nav class = "profile-nav">
            <ul class = "profile-ul">
                <li class = "profile"><nobr><a class = "profile <?php echo ($current_page == 'profile-input.php') ? 'active' : ''; ?>"  href="profile-input.php">プロフィール</a></nobr></li>
                <li class = "profile"><nobr><a class = "profile <?php echo ($current_page == 'follow.php') ? 'active' : ''; ?>" href="follow.php">フォロー</a></nobr></li>
                <li class = "profile"><nobr><a class = "profile <?php echo ($current_page == 'follower.php') ? 'active' : ''; ?>" href="follower.php">フォロワー</a></nobr></li>
            </ul>
        </nav>
    </div>
</body>
</html>