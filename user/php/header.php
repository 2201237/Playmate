<!--ヘッダー-->
    <header>


<!--▽▽ヘッダーロゴ▽▽-->
      <div class="logo">
          <a href="home.php">
            <img src="../img/logo.png" >
          </a>
      </div>
<!--△△ヘッダーロゴ△△-->


<!--▽▽ハンバーガーメニュー▽▽-->
      <div id="hamburger">
          <div class="hm-icon">
              <span></span>
              <span></span>
              <span></span>
          </div>
      </div>
<!--△△ハンバーガーメニュー△△-->

<<<<<<< HEAD
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
=======
>>>>>>> parent of eb71ce9 (commit)

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


<<<<<<< HEAD


<!--▽▽ヘッダーリスト▽▽-->
      <nav class="pc">  <!--pcクラスを追記-->
        <ul>
          <li><form action="search.php" class="search-form-6" method="get">
    <label>
        <input type="text" name="username" aria-label="キーワードを入力">
    </label>
</form></li>
          <li><a href="home.php">ホーム</a></li>
=======
<!--▽▽ヘッダーリスト▽▽-->
      <nav class="pc">  <!--pcクラスを追記-->
        <ul>
          <li><a href="#">ホーム</a></li>
>>>>>>> parent of eb71ce9 (commit)
          <li><a href="chatboard-title.php">掲示板</a></li>
          <li><a href="#">大会一覧</a></li>
          <li><a href="ranking.php">ランキング</a></li>
          <li><a href="infomation-input.php">お問い合わせ</a></li>
          <li><a href="profile-input.php"><img src=<?php echo $_SESSION['User']['user_icon']?>></a></li>
<<<<<<< HEAD
>>>>>>> cd0bb936e7dcc76c789ffb2bce7adc380da5d1ff
=======
>>>>>>> parent of eb71ce9 (commit)
        </ul>
      </nav>
<!--△△ヘッダーリスト△△-->


    </header>
  