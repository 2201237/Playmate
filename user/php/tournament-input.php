<?php
      session_start();
      require 'db-connect.php';
      $pdo=new PDO($connect,USER,PASS);
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="../img/favicon.ico">
    <title>大会参加</title>
</head>
<body>
    <form action="tournament-output.php" method="post">
    <?php
        $lastId=$pdo->lastInsertId();
        $sql=$pdo->prepare('select * from tournament where tournament_id = ?');
        $sql->execute([$_GET['tournament_id']]);
        foreach($sql as $tournament){
            echo "<div style='text-align:center'>";
            $tournament_id = $tournament['game_id']; 
            $image_path = "../img/" . $tournament_id . ".jpg";    
            echo "<h1>" . $tournament['tournament_name'] . "</h1>";
            echo "<img src = '$image_path' width = '180' height = '' >";
            echo '<div class="rure">';
            echo "<p> ~ルール~ <br>" . $tournament['rure']. "</p>";
            echo "</div>";
            echo '<input type ="submit" class = "button" value = "参加">';
            echo "</div>";

        }

    ?>
    </form>
    <form action = "home.php"  method = "post">
        <button type="button" class="button" onclick="history.back()">戻る</button>
    </form>

</body>
</html>