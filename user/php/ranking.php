<?php
    session_start();
    require 'db-connect.php';
    $pdo = new PDO($connect, USER, PASS);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<<<<<<< HEAD
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
=======
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="../img/favicon.ico">
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/ranking-list.css">
    <title>PlayMateランキング</title>
</head>
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

        //TOP3を表示する処理
        $cnt = 0;
        foreach ($user_winlose as $win_lose) {

            //カウントが3以上になるとループから抜ける
            if( $cnt > 2 ){
                break;
            }
            
            echo $win_lose['user_name'],'<br>',
                    '試合数',$win_lose['user_games'],'<br>',
                    '勝率',round( $win_lose['wp'], 2 )*100,'%<br>';
            $cnt++;
        }
    ?>
<!-- /* Please ❤ this if you like it! 😊 */ -->

<!-- Page wrapper/Container Section -->
<div class="container">
  <!-- Responsive Table Section -->
  <table class="responsive-table">
    <!-- Responsive Table Header Section -->
    <thead class="responsive-table__head">
      <tr class="responsive-table__row">
        <th class="responsive-table__head__title responsive-table__head__title--name">ユーザー名
          <svg version="1.1" class="up-arrow" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 512 512" style="enable-background:new 0 0 512 512;" xml:space="preserve">
            <path d="M374.176,110.386l-104-104.504c-0.006-0.006-0.013-0.011-0.019-0.018c-7.818-7.832-20.522-7.807-28.314,0.002c-0.006,0.006-0.013,0.011-0.019,0.018l-104,104.504c-7.791,7.829-7.762,20.493,0.068,28.285    c7.829,7.792,20.492,7.762,28.284-0.067L236,68.442V492c0,11.046,8.954,20,20,20c11.046,0,20-8.954,20-20V68.442l69.824,70.162c7.792,7.829,20.455,7.859,28.284,0.067C381.939,130.878,381.966,118.214,374.176,110.386z" />
          </svg>
        </th>
        <th class="responsive-table__head__title responsive-table__head__title--status">順位</th>
        <th class="responsive-table__head__title responsive-table__head__title--types">勝率</th>
        <th class="responsive-table__head__title responsive-table__head__title--update">勝敗数</th>
        <th class="responsive-table__head__title responsive-table__head__title--country">お気に入り</th>
      </tr>
    </thead>
    <!-- Responsive Table Body Section -->
    <tbody class="responsive-table__body">
        
      <tr class="responsive-table__row">
        <td class="responsive-table__body__text responsive-table__body__text--name">
            <img src="../img/tnk.jpg" class="user_icon">
            田中 亮丞
        </td>
        <td class="responsive-table__body__text responsive-table__body__text--status">4</td>
        <td class="responsive-table__body__text responsive-table__body__text--types">91%</td>
        <td class="responsive-table__body__text responsive-table__body__text--update">試合数：11　10勝1敗</td>
        <td class="responsive-table__body__text responsive-table__body__text--country">大乱闘スマッシュブラザーズ</td>
      </tr>

      <tr class="responsive-table__row">
        <td class="responsive-table__body__text responsive-table__body__text--name">
            <img src="../img/srt.png" class="user_icon">
            白石 涼太
        </td>
        <td class="responsive-table__body__text responsive-table__body__text--status">5</td>
        <td class="responsive-table__body__text responsive-table__body__text--types">89%</td>
        <td class="responsive-table__body__text responsive-table__body__text--update">試合数：12　10勝2敗</td>
        <td class="responsive-table__body__text responsive-table__body__text--country">原神　League of Legends</td>
      </tr>
      <tr class="responsive-table__row">
        <td class="responsive-table__body__text responsive-table__body__text--name">
            <img src="../img/ts.jpg" class="user_icon">
            池永 大晟
        </td>
        <td class="responsive-table__body__text responsive-table__body__text--status">6</td>
        <td class="responsive-table__body__text responsive-table__body__text--types">87%</td>
        <td class="responsive-table__body__text responsive-table__body__text--update">試合数：13　10勝2敗</td>
        <td class="responsive-table__body__text responsive-table__body__text--country">League of Legends</td>
      </tr>
      <!-- <tr class="responsive-table__row">
        <td class="responsive-table__body__text responsive-table__body__text--name">
          <svg class="user-icon" enable-background="new 0 0 512 512" height="512" viewBox="0 0 512 512" width="512" xmlns="http://www.w3.org/2000/svg">
            <path d="m256.025 483.334 101.429-25.614c57.895-48.074 94.771-120.586 94.771-201.719 0-125.144-87.711-229.801-205.012-255.852-137.316 4.631-247.213 117.407-247.213 255.851 0 71.112 29 135.446 75.812 181.836z" fill="#cbe2ff" />
            <path d="m446.914 256c0 83.915-40.381 158.391-102.765 205.079l92.031-23.241c46.815-46.39 75.82-110.724 75.82-181.838 0-141.385-114.615-256-256-256-11.024 0-21.886.698-32.543 2.05 126.019 15.988 223.457 123.59 223.457 253.95z" fill="#bed8fb" />
            <path d="m319.621 96.952c0-13.075-10.599-23.674-23.674-23.674h-81.582c-30.091 0-54.485 24.394-54.485 54.485v60.493h192.209v-59.635c0-13.075-10.599-23.674-23.674-23.674h-.798c-4.416 0-7.996-3.579-7.996-7.995z" fill="#365e7d" />
            <path d="m328.415 104.947h-.798c-4.416 0-7.996-3.58-7.996-7.996 0-13.075-10.599-23.674-23.674-23.674h-8.945v114.978h65.086v-59.635c.001-13.073-10.599-23.673-23.673-23.673z" fill="#2b4d66" />
            <path d="m425.045 372.355c-6.259-6.182-14.001-10.963-22.79-13.745l-69.891-22.128-76.348-2.683-76.38 2.683-69.891 22.128c-23.644 7.486-39.713 29.428-39.713 54.229v19.094c44.789 47.328 107.451 77.568 177.183 79.92 78.128-17.353 143.129-69.576 177.83-139.498z" fill="#4a80aa" />
            <path d="m441.968 431.932v-19.094c0-17.536-8.04-33.635-21.105-44.213-37.111 75.626-110.422 130.268-197.346 141.317 10.492 1.329 21.178 2.038 32.026 2.057 10.423-.016 20.708-.62 30.824-1.782 61.031-7.212 115.485-35.894 155.601-78.285z" fill="#407093" />
            <path d="m261.796 508.168c15.489-30.751 55.822-118.067 44.321-172.609l-50.101-19.499-50.148 19.5c-11.856 56.225 31.37 147.277 45.681 175.29 3.442-.826 6.859-1.721 10.247-2.682z" fill="#e4f6ff" />
            <path d="m288.197 483.789-20.314-79.917h-23.767l-20.264 79.699 25.058 27.897c6.361-1.457 12.634-3.146 18.81-5.057z" fill="#e28086" />
            <path d="m249.302 511.905c2.075.054 4.154.091 6.241.095 2.415-.004 4.822-.046 7.222-.113l12.907-14.259c-10.159 3.564-20.61 6.506-31.309 8.779z" fill="#dd636e" />
            <path d="m298.774 328.183v-45.066h-85.58v45.066c0 23.632 42.79 49.446 42.79 49.446s42.79-25.814 42.79-49.446z" fill="#ffddce" />
            <path d="m352.089 180.318h-16.359c-9.098 0-16.473-7.375-16.473-16.473v-9.015c0-11.851-11.595-20.23-22.847-16.511-26.243 8.674-54.579 8.676-80.823.006l-.031-.01c-11.252-3.717-22.845 4.662-22.845 16.512v9.019c0 9.098-7.375 16.473-16.473 16.473h-16.358v26.938c0 6.883 5.58 12.464 12.464 12.464 2.172 0 3.939 1.701 4.076 3.869 2.628 41.668 37.235 74.654 79.565 74.654 42.33 0 76.937-32.986 79.565-74.654.137-2.167 1.904-3.869 4.076-3.869 6.883 0 12.464-5.58 12.464-12.464v-26.939z" fill="#ffddce" />
            <path d="m335.73 180.318c-9.098 0-16.473-7.375-16.473-16.473v-9.015c0-11.851-11.595-20.23-22.847-16.511-3.108 1.027-6.247 1.923-9.407 2.707v88.972c-.438 28.948-16.3 54.142-39.725 67.758 2.861.311 5.763.486 8.706.486 42.33 0 76.937-32.986 79.565-74.654.137-2.167 1.904-3.869 4.076-3.869 6.883 0 12.464-5.58 12.464-12.464v-26.938h-16.359z" fill="#ffcbbe" />
            <g fill="#f4fbff">
              <path d="m213.194 316.06-33.558 27.267 35.192 43.513c4.281 4.168 11.019 4.424 15.605.594l26.465-22.107z" />
              <path d="m298.79 316.06-41.892 49.267 24.874 21.268c4.557 3.896 11.327 3.7 15.651-.453l34.94-42.815z" />
            </g>
            <path d="m213.194 316.06-49.256 24.199c-3.75 1.842-5.256 6.404-3.341 10.117l9.65 18.71c2.501 4.848 1.578 10.756-2.282 14.61-1.987 1.983-4.139 4.131-6.004 5.993-3.338 3.332-4.537 8.255-3.067 12.737 11.651 35.517 67.725 89.828 88.946 109.478 1.427.038 2.857.064 4.29.08-15.389-29.933-69.922-143.655-38.936-195.924z" fill="#365e7d" />
            <path d="m344.019 383.695c-3.861-3.854-4.783-9.762-2.282-14.61l9.65-18.71c1.915-3.713.409-8.275-3.341-10.117l-49.256-24.198c30.978 52.255-23.517 165.929-38.923 195.9 1.448-.025 2.893-.061 4.335-.109 21.265-19.695 77.248-73.94 88.888-109.424 1.47-4.482.271-9.405-3.067-12.737-1.865-1.863-4.017-4.012-6.004-5.995z" fill="#365e7d" />
            <path d="m256.898 365.327-26.06 21.764 13.278 16.781h23.767l13.279-17.771z" fill="#dd636e" />
          </svg>
          Dan Broughan
        </td>
        <td class="responsive-table__body__text responsive-table__body__text--status"><span class="status-indicator status-indicator--active"></span>Active</td>
        <td class="responsive-table__body__text responsive-table__body__text--types">Attendee, Instructor, MSR</td>
        <td class="responsive-table__body__text responsive-table__body__text--update">Dec 15, 2021, 08:25 AM</td>
        <td class="responsive-table__body__text responsive-table__body__text--country">US</td>
      </tr> -->
    </tbody>
  </table>
</div>
<script src="../js/ranking-list.js"></script>
>>>>>>> parent of eb71ce9 (commit)
</body>
</html>