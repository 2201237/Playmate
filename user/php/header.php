<?php   
$iconPath = $_SESSION['User']['user_icon'];
$current_page = basename($_SERVER['REQUEST_URI']);

?>
<!--ヘッダー-->
    <header>

<!--▽▽ヘッダーロゴ▽▽-->
      <div class="logo">
          <a href="home.php">
            <img src="../img/logo.png" >
          </a>
      </div>
<!--△△ヘッダーロゴ△△-->

  <form action="search.php" class="search-form-6" method="get">
    <label>
        <input type="text" name="username" aria-label="キーワードを入力">
    </label>
    </form>



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
        <li><a href="profile-input.php"><img src=<?php echo $iconPath ?>></a></li>
        <li><a href="home.php" class="<?php echo ($current_page == 'home.php') ? 'active' : ''; ?>">ホーム</a></li>
        <li><a href="chatboard-title.php" class="<?php echo ($current_page == 'chatboard-title.php') ? 'active' : ''; ?>">掲示板</a></li>
        <li><a href="tournament-list.php" class="<?php echo ($current_page == 'tournament-list.php') ? 'active' : ''; ?>">大会一覧</a></li>
        <li><a href="ranking.php" class="<?php echo ($current_page == 'ranking.php') ? 'active' : ''; ?>">ランキング</a></li>
        <li><a href="infomation-input" class="<?php echo ($current_page == 'infomation-input') ? 'active' : ''; ?>">お問い合わせ</a></li>
        <li><a href="profile-input.php" class = " <?php echo ($current_page == 'profile-input.php') ? 'active' : ''; ?>"  >プロフィール</a></li>
        <li><a href="follow.php" class = " <?php echo ($current_page == 'follow.php') ? 'active' : ''; ?>" >フォロー</a></li>
        <li><a href="follower.php" class = " <?php echo ($current_page == 'follower.php') ? 'active' : ''; ?>" >フォロワー</a></li>

          
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
          <li><a href="profile-input.php"><img src=<?php echo $iconPath ?>></a></li>
        </ul>
      </nav>
<!--△△ヘッダーリスト△△-->


    </header>
  