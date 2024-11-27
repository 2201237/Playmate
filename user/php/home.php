<?php
    session_start();
    require 'db-connect.php';

    $pdo = new PDO($connect, USER, PASS);
    $tsql = $pdo->prepare('select * FROM tournament');
    $tsql->execute();

    $rsql = $pdo->prepare('select * FROM ranking');
    $rsql->execute();
?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="../img/favicon.ico">
    <link rel="stylesheet" href="../css/header.css">
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/tournament-slide.css">
    <link rel="stylesheet" href="../css/board-slide.css">
    <link rel="stylesheet" href="../css/ranking.css">
    <link rel="stylesheet" href="../css/owl.carousel.min.css">
    <link rel="stylesheet" href="../css/owl.theme.default.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="../js/owl.carousel.min"></script>
    <title>PlayMate</title>
</head>

<body>

    <?php require 'header.php'; ?>



    <div class="headline">現在開催中の大会</div>
    <!-- 大会スライドショー -->
    <section class="testimonials">
        <div class="container">
            <div class="row">
                <div class="col-sm-12">
                    <div id="customers-testimonials" class="owl-carousel">
                        <!-- DBに登録されている大会情報を表示 -->
                        <?php
                        $tournaments = $tsql->fetchAll(PDO::FETCH_ASSOC);
                        foreach ($tournaments as $tournament) {
                            $game_id = $tournament['game_id'];
                            $game_image_path = "../img/" . $game_id . ".jpg";
                            echo '<div class="item">
                                <div class="shadow-effect">
                                    <img class="img-circle" src="', $game_image_path, '" alt="ゲーム画像">
                                    <span>', $tournament['tournament_name'], '</span>
                                    <p>', $tournament['rule'], '</p>
                                </div>
                                <a href = "tournament-join.php?tournament_id=" ', $tournament['tournament_id'], '">
                                    <div class="testimonial-name">
                                        <span class="name_text">詳細はこちら</span>
                                    </div>
                                </a>
                            </div>';
                        }
                        ?>
                        <!-- 処理終了 -->
                    </div>
                </div>
            </div>
    </section>
    <!-- 大会スライドショー終了 -->



    <div class="headline">掲示板</div>
    <!-- 掲示板スライドショー -->
    <div class="wrap slide-paused">
        <ul class="slideshow">
            <?php
            $gsql = $pdo->query('select * FROM game');
            $games = $gsql->fetchAll(PDO::FETCH_ASSOC);
            foreach ($games as $game) {
                $game_id = $game['game_id'];
                $image_path = "../img/" . $game_id . ".jpg";
                echo '<li class="content content-hover">',
                        '<a href = "chatboard-title.php?game_id=' . $game_id . '">',
                            '<img src = "' . $image_path . '" width = "215px" alt="ゲーム画像">',
                            '<p>'.$game['title'],
                        '</a></p>
                    </li>';
            }
            ?>
        </ul>
        <ul class="slideshow">
            <?php
                $gsql = $pdo->query('select * FROM game');
                $games = $gsql->fetchAll(PDO::FETCH_ASSOC);
                foreach ($games as $game) {
                    $game_id = $game['game_id'];
                    $image_path = "../img/" . $game_id . ".jpg";
                    echo '<li class="content content-hover">',
                            '<a href = "board-list.php?game_id=' . $game_id . '">',
                                '<img src = "' . $image_path . '" width = "215px" alt="ゲーム画像">',
                                '<p>'.$game['title'],
                            '</a></p>
                        </li>';
            }
            ?>
        </ul>
        <ul class="slideshow">
            <?php
                $gsql = $pdo->query('select * FROM game');
                $games = $gsql->fetchAll(PDO::FETCH_ASSOC);
                foreach ($games as $game) {
                    $game_id = $game['game_id'];
                    $image_path = "../img/" . $game_id . ".jpg";
                    echo '<li class="content content-hover">',
                            '<a href = "board-list.php?game_id=' . $game_id . '">',
                                '<img src = "' . $image_path . '" width = "215px" alt="ゲーム画像">',
                                '<p>'.$game['title'],
                            '</a></p>
                        </li>';
                }
                ?>
        </ul>
    </div>
    <!-- 大会スライドショー終了 -->



    <!--<p class="no_users">現在ユーザーが登録されていません</p>-->
    <div class="headline">ランキング</div>
    <!-- ランキング表 -->
    <section>
        <div class="container">
            <div class="card">
            <div class="card-inner" style="--clr:#fafafa;">
                <div class="box">
                <div class="imgBox">
                    <img src="../img/tnk.jpg" alt="Trust & Co.">
                </div>
                <div class="icon">
                    <a href="#" class="iconBox"> <span class="material-symbols-outlined">
                        arrow_outward
                    </span></a>
                </div>
                </div>
            </div>
            <div class="content">
                <h3><img src="../img/1st.png" class="f"><div class="name">バスケットマン3号！！！</div></h3>
                <p>
                    90勝5敗<br>
                    俺以外、雑魚 　GG</p>
                <ul>
                <li style="--clr-tag:#d3b19a;" class="branding">にゃんこ大戦争（クソゲー）</li>
                <li style="--clr-tag:#70b3b1;" class="packaging">マリオカート 8DX</li>
                </ul>
            </div>
            </div>
            <div class="card">
            <div class="card-inner" style="--clr:#fafafa;">
                <div class="box">
                <div class="imgBox">
                    <img src="../img/srt.png" alt="Tonic">
                </div>
                <div class="icon">
                    <a href="#" class="iconBox"> <span class="material-symbols-outlined">
                        arrow_outward
                    </span></a>
                </div>
                </div>
            </div>
            <div class="content">
                <h3><img src="../img/2nd.png" class="f"><div class="name">SRtama</div></h3>
                <p>ほっともっと新宮店</p>
                <ul>
                <li style="--clr-tag:#d3b19a;" class="branding">にゃんこ大戦争（クソゲー）</li>
                <li style="--clr-tag:#d05fa2;" class="marketing">League of Legends</li>
                </ul>
            </div>
            </div>
            <div class="card">
            <div class="card-inner" style="--clr:#fafafa;">
                <div class="box">
                <div class="imgBox">
                    <img src="../img/nhm.jfif" alt="Shower Gel">
                </div>
                <div class="icon">
                    <a href="#" class="iconBox"> <span class="material-symbols-outlined">
                        arrow_outward
                    </span></a>
                </div>
                </div>
            </div>
            <div class="content">
                <h3><img src="../img/3rd.png" class="f"><div class="name">なまはむ</div></h3>
                <p>アンベッサアンチ　基本ロームしません</p>
                <ul>
                <li style="--clr-tag:#d3b19a;" class="branding">League of Legends</li>
                <li style="--clr-tag:#70b3b1;" class="packaging">モンスターハンターライズ</li>
                </ul>
            </div>
            </div>
        </div>
    </section>


    <script src="../js/header.js"></script>
    <script src="../js/home.js"></script>
    <script src="../js/ranking.js"></script>
</body>
</html>