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
    <link rel="stylesheet" href="../css/home.css">
    <link rel="stylesheet" href="../css/owl.carousel.min.css">
    <link rel="stylesheet" href="../css/owl.theme.default.css">
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
                                <a href = "tournament-detail.php?tournament_id= '. $tournament['tournament_id'] . '">
                                    <div class="testimonial-name">
                                        詳細はこちら
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



    <div class='headline'>掲示板</div>
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



    <div class='headline'>ランキング</div>
    <p class="no_users">現在ユーザーが登録されていません</p>
    <?php 
    $rankings = $rsql->fetchAll(PDO::FETCH_ASSOC);
    echo "<div class = 'Ranking'>";
    foreach ($rankings as $ranking) {
        echo $ranking['ranking_id'];
        echo "<a href = 'user-part.php?user_id=" . $ranking['user_id'] . "'>" . $ranking['user_id'] . "</a>";
        echo $ranking['player_rank'];
    }
    echo "</div>";
    ?>

    <script src="../js/header.js"></script>
    <script src="../js/home.js"></script>
</body>
</html>