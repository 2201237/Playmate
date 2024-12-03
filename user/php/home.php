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
                                <a href = "tournament-detail.php?tournament_id= '. $tournament['tournament_id'] . '">
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
<<<<<<< HEAD
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


=======
            <?php
                //試合数と勝利数、勝率を取得する処理

                // テーブル一覧
                // winlose = 勝敗テーブル
                // user_winlose = ユーザーごとに試合数、勝利数をカウントしているテーブル ASで命名している
                // users = ユーザーテーブル
                
                //カラム一覧
                // user_games = 試合数, user_win = 勝利数, wp = 勝率

                // ユーザー名取得のためユーザーテーブルと勝敗テーブルを結合
                // 重複したカラム名はどのテーブルのカラムかを表すため、「テーブル名.カラム名」で表記している

                $user_winlose = $pdo->query('SELECT user_winlose.user_id, user_name, user_games, user_win, user_win / user_games AS wp, profile, icon
                                            FROM (
                                                SELECT winlose.user_id, count( winlose.user_id ) AS user_games, count( win_lose = 1 OR NULL ) AS user_win
                                                FROM winlose
                                                GROUP BY winlose.user_id
                                            ) AS user_winlose
                                            INNER JOIN users ON user_winlose.user_id = users.user_id
                                            GROUP BY user_winlose.user_id
                                            ORDER BY wp DESC');

                $cnt = 1;
                foreach ($user_winlose as $win_lose) {

                    //カウントが4以上になるとループから抜ける
                    if( $cnt > 3 ){
                        break;
                    }
                    
                    //敗北数の計算
                    $lose_num = (int)$win_lose['user_games'] - (int)$win_lose['user_win'];

                    echo 
                        '<div class="card">
                            <div class="card-inner" style="--clr:#fafafa;">
                                <div class="box">
                                    <div class="imgBox">',
                                        '<img src="' .$win_lose['icon']. '">',
                                    '</div>
                                    <div class="icon">
                                        <a href="profile-partner.php?user_id='. $win_lose['user_id']. '"class="iconBox"> <span class="material-symbols-outlined">
                                            arrow_outward
                                        </span></a>
                                    </div>
                                </div>
                            </div>
                            <div class="content">
                                <h3><img src="../img/rank_', $cnt ,'.png" class="f"><div class="name">', $win_lose['user_name'] ,'</div></h3>
                                <p>',
                                    '勝率：',round( $win_lose['wp'], 4 )*100,'%　',
                                    '試合数：'.$win_lose['user_games'].'　'.$win_lose['user_win'].'勝' .$lose_num. '敗<br>',
                                    $win_lose['profile'], '</p>
                                <ul>',
                                '<li style="--clr-tag:#d3b19a;" class="branding">大乱闘スマッシュブラザーズ</li>
                                <li style="--clr-tag:#70b3b1;" class="packaging">マリオカート 8DX</li>
                                </ul>
                            </div>
                        </div>';
                    $cnt++;
                }
        ?>
        </div>
    </section>

    <!-- ランキング表 -->
    <div class="container_not_3">
        <!-- Responsive Table Section -->
        <table class="responsive-table">
            <!-- Responsive Table Header Section -->
            <thead class="responsive-table__head">
            <tr class="responsive-table__row">
                <th class="responsive-table__head__title responsive-table__head__title--name">Name
                </th>
                <th class="responsive-table__head__title responsive-table__head__title--status">Rank</th>
                <th class="responsive-table__head__title responsive-table__head__title--types">Win Rate</th>
                <th class="responsive-table__head__title responsive-table__head__title--update">Games</th>
                <th class="responsive-table__head__title responsive-table__head__title--country">Favorite</th>
            </tr>
            </thead>

            <!-- Responsive Table Body Section -->
            <tbody class="responsive-table__body">
            <?php
                $user_winlose = $pdo->query('SELECT user_winlose.user_id, user_name, user_games, user_win, user_win / user_games AS wp, profile, icon
                                            FROM (
                                                SELECT winlose.user_id, count( winlose.user_id ) AS user_games, count( win_lose = 1 OR NULL ) AS user_win
                                                FROM winlose
                                                GROUP BY winlose.user_id
                                            ) AS user_winlose
                                            INNER JOIN users ON user_winlose.user_id = users.user_id
                                            GROUP BY user_winlose.user_id
                                            ORDER BY wp DESC');

                $cnt = 1;
                foreach ($user_winlose as $win_lose) {

                    if( $cnt <= 3 ){
                        $cnt++;
                        continue;
                    }

                    //敗北数の計算
                    $lose_num = (int)$win_lose['user_games'] - (int)$win_lose['user_win'];
                    echo
                        '<tr class="responsive-table__row">
                            <td class="responsive-table__body__text responsive-table__body__text--name">',
                                '<img src="'.$win_lose['icon'].'" class="user_icon">',
                                $win_lose['user_name'],
                            '</td>
                            <td class="responsive-table__body__text responsive-table__body__text--status">',$cnt,'</td>
                            <td class="responsive-table__body__text responsive-table__body__text--types">',round( $win_lose['wp'], 4 )*100,'%</td>
                            <td class="responsive-table__body__text responsive-table__body__text--update">試合数：'.$win_lose['user_games'].'　'.$win_lose['user_win'].'勝'.$lose_num.'敗</td>
                            <td class="responsive-table__body__text responsive-table__body__text--country">なし</td>
                        </tr>';
                    $cnt++;
                }
            ?>
            </tbody>
        </table>
    </div>
    
>>>>>>> cd0bb936e7dcc76c789ffb2bce7adc380da5d1ff
    <script src="../js/header.js"></script>
    <script src="../js/home.js"></script>
    <script src="../js/ranking.js"></script>
</body>
</html>