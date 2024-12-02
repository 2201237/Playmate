<?php

// セッションからアイコンのパスを取得
$iconPath = $_SESSION['User']['user_icon'];
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
            <li><a href="query-top.php">お問い合わせ</a></li>
            <form action="search.php" class = "search" method="get">
                <input type="text" id="username" class = "stext" name="username" placeholder="ユーザー名を検索">
            <button type="submit" class = "sbut">🔍</button>
            </form>
        </ul>
    </nav>


    <a href="profile-input.php">
        <?php 
            echo "<img src='".$iconPath."' class='icon_user' width='50' height='50'>";
        ?>
    </a>

</div>