<?php
    session_start();
    require 'db-connect.php';
    $pdo = new PDO($connect, USER, PASS);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="../img/favicon.ico">
    <link rel="stylesheet" href="../css/header.css">
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/tournament-slide.css">
    <link rel="stylesheet" href="../css/board-slide.css">
    <link rel="stylesheet" href="../css/ranking-list.css">
    <link rel="stylesheet" href="../css/owl.carousel.min.css">
    <link rel="stylesheet" href="../css/owl.theme.default.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="../js/owl.carousel.min"></script>
    <title>PlayMateランキング</title>
    
    <?php require 'header.php'; ?>
</head>
<div class='headline'>ランキング</div>
    
    <!-- TOP3を表示する処理 -->
    <section class="ranking_top3">
        <div class="container">
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
                                        <a href="#" class="iconBox"> <span class="material-symbols-outlined">
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
    
    <script src="../js/header.js"></script>
    <script src="../js/home.js"></script>
    <script src="../js/ranking-list.js"></script>
</body>
</html>