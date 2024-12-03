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

<<<<<<< HEAD
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
=======

<!--▽▽ハンバーガーメニューのリスト▽▽-->
      <nav class="sm">
        <ul>
          <li><a href="#">ホーム</a></li>
          <li><a href="#">掲示板</a></li>
          <li><a href="#">大会一覧</a></li>
          <li><a href="#">ランキング</a></li>
          <li><a href="#">お問い合わせ</a></li>
       </ul>
     </nav>
<!--△△ハンバーガーメニューのリスト△△-->




<!--▽▽ヘッダーリスト▽▽-->
      <nav class="pc">  <!--pcクラスを追記-->
        <ul>
          <li><form action="search.php" class="search-form-6" method="get">
    <label>
        <input type="text" name="username" aria-label="キーワードを入力">
    </label>
</form></li>
          <li><a href="home.php">ホーム</a></li>
          <li><a href="chatboard-title.php">掲示板</a></li>
          <li><a href="#">大会一覧</a></li>
          <li><a href="ranking.php">ランキング</a></li>
          <li><a href="infomation-input.php">お問い合わせ</a></li>
          <li><a href="profile-input.php"><img src=<?php echo $_SESSION['User']['user_icon']?>></a></li>
>>>>>>> cd0bb936e7dcc76c789ffb2bce7adc380da5d1ff
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