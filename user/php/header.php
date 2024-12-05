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
          <li><a href="tournament-list.php">大会一覧</a></li>
          <li><a href="ranking.php">ランキング</a></li>
          <li><a href="infomation-input.php">お問い合わせ</a></li>
          <li><a href="profile-input.php"><img src=<?php echo $_SESSION['User']['user_icon']?>></a></li>
        </ul>
      </nav>
<!--△△ヘッダーリスト△△-->


    </header>
  