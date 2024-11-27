<?php

// セッションからアイコンのパスを取得
$userIcon = isset($_SESSION['User']['icon']) ?  'https://aso2201222.kill.jp/'.$_SESSION['User']['icon'] : '../img/icon_user.png';
?>

<div class="hamburger">

    <div class="logo">
        <a href="home.php">
            <img src="../img/logo.png" class="logo" alt="ロゴ画像">
        </a>
    </div>

    <p class="btn-gNav">
        <span></span>
        <span></span>
        <span></span>
    </p>

    <nav class="gNav">
        <ul class="gNav-menu">
            <li><a class=”current” href="home.php">ホーム</a></li>
            <li><a href="tournament-list.php">大会一覧</a></li>
            <li><a href="#">掲示板</a></li>
            <li><a href="#">ランキング</a></li>
            <li><a href="infomation-input.php">お問い合わせ</a></li>
        </ul>
    </nav>

    <a href="profile-input.php">
        <img src="../img/icon_user.png" class="icon_user" width="70" height="70" alt="ユーザーアイコン画像">
    </a>
</div>