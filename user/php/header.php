<?php

// セッションからアイコンのパスを取得
$userIcon = isset($_SESSION['User']['icon']) ? $_SESSION['User']['icon'] : '../img/icon_user.png';
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
            <li><a href="chatboard-title.php">掲示板</a></li>
            <li><a href="#">ランキング</a></li>
            <li><a href="infomation-input.php">お問い合わせ</a></li>
            <form action="search.php" method="get">
                <label for="username">ユーザー名を検索:</label>
                <input type="text" id="username" name="username">
            <button type="submit">検索</button>
            </form>
        </ul>
    </nav>


    <a href="profile-input.php">
        <?php 
           if (isset($userIcon) && $userIcon !== '') {

            echo "<img src='".$userIcon."' class='icon_user' width='50' height='50'>";
            } else {
                echo "<img src='../img/icon_user.png' class='icon_user' width='50' height='50'>";
            }
        ?>
    </a>

</div>