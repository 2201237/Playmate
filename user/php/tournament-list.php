<?php
      session_start();
      require 'db-connect.php';
      require '../header.html';
      $pdo=new PDO($connect,USER,PASS);
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
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
</body>
</html>