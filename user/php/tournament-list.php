<?php
      session_start();
      require 'db-connect.php';
      require 'header.php';
      $pdo=new PDO($connect,USER,PASS);
session_start();
require 'db-connect.php';
require '../header.html';

$pdo = new PDO($connect, USER, PASS);
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="../img/favicon.ico">
    <title>大会一覧</title>
</head>
<body>
        <div>
            <h1  style='text-align:center'>大会一覧</h1>
            <table border="1" >
            <?php
                $sql=$pdo->prepare('select * from tournament');
                $sql->execute();
                foreach($sql as $list){
                    $icon_id = $list['game_id']; 
                    $image_path = "../img/" . $icon_id . ".jpg";    
                    echo "<thead>";
                        echo "<tr>";
                            echo "<th></th>";
                            echo "<th>大会名</th>";
                            echo "<th>参加</th>";
                        echo "</tr>";
                    echo "</thead>";
                    echo "<tbody>";
                        echo "<tr>";
                            echo "<td><img src = '$image_path' width = '180' height = '' ></td>";
                            echo "<td  style='word-wrap: break-word;  max-width: 150px;'>". $list['tournament_name'] ."</td>";
                            echo "<form action = 'tournament-input.php?tournament_id=" . $list['tournament_id'] ."' method = 'post'>";
                            echo "<td><input type = 'submit' class = 'button' value = '参加'></td>";
                            echo "</form>";
                    echo "</tr>";
                    echo "</tbody>";
                }
            ?>
        </div>
    </table>
    <div>
        <h1 style='text-align:center'>大会一覧</h1>
        <table border="1">
        <?php
            $sql = $pdo->prepare('SELECT * FROM tournament');
            $sql->execute();

            echo "<thead>";
                echo "<tr>";
                    echo "<th>アイコン</th>";
                    echo "<th>大会名</th>";
                    echo "<th>参加人数</th>";
                    echo "<th>詳細</th>";  // 詳細リンクのカラムを追加
                echo "</tr>";
            echo "</thead>";
            echo "<tbody>";

            foreach ($sql as $list) {
                $tournament_id = $list['tournament_id'];
                $icon_id = $list['game_id'];
                $image_path = "../img/" . $icon_id . ".jpg";

                // 現在の大会の参加人数をカウントするクエリ
                $count_sql = $pdo->prepare('SELECT COUNT(*) AS participant_count FROM tournament_member WHERE tournament_id = ?');
                $count_sql->execute([$tournament_id]);
                $participant_count = $count_sql->fetchColumn();

                echo "<tr>";
                    echo "<td><img src='$image_path' width='180' height=''></td>";
                    echo "<td style='word-wrap: break-word; max-width: 150px;'>". htmlspecialchars($list['tournament_name']) ."</td>";
                    echo "<td>$participant_count</td>";
                    echo "<td><a href='tournament-detail.php?tournament_id=$tournament_id'>詳細</a></td>"; // 詳細リンクを追加
                echo "</tr>";
            }
            echo "</tbody>";
        ?>
        </table>
    </div>
</body>
</html>
</html>