<?php
    session_start(); 
    require 'db-connect.php';
    require '../header.html';
    
    $pdo=new PDO($connect,USER,PASS);
    $tsql=$pdo->prepare('select * FROM tournament');
    $tsql->execute();

    $bsql=$pdo->prepare('select * FROM genre');
    $bsql->execute();

    $rsql=$pdo->prepare('select * FROM ranking');
    $rsql->execute();
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/home.scss">
    <title>PlayMate</title>
</head>
<body>
    <h3 class = "join" >現在大会中の参加</h3>

    <!-- スライダー -->
<section class="testimonials">
    <div class="container">
      <div class="row">
        <div class="col-sm-12">
          <div id="customers-testimonials" class="owl-carousel">
            <!--TESTIMONIAL 1 -->
            <?php
                $tournaments = $tsql->fetchAll(PDO::FETCH_ASSOC);
                foreach ($tournaments as $tournament) {
                    $game_id = $tournament['game_id']; 
                    $game_image_path = "../img/" . $game_id . ".jpg";
                    echo '<div class="item">
                            <div class="shadow-effect">
                                <a href = "tournament-join.php?tournament_id=" ', $tournament['tournament_id'] ,'">
                                <img class="img-circle" src="', $game_image_path ,'" alt="">
                                </a>
                                <p>', $tournament['rule'] ,'</p>
                            </div>
                            <div class="testimonial-name">', $tournament['tournament_name'] ,'</div>
                        </div>';
                }
            ?>
            <!--END OF TESTIMONIAL 1 -->
        </div>
      </div>
    </div>
</section>
    <!-- END OF TESTIMONIALS -->
    
    <?php 
        $tournaments = $tsql->fetchAll(PDO::FETCH_ASSOC);
        echo "<div class = 'Tournament'>";
        foreach ($tournaments as $tournament) {
            $game_id = $tournament['game_id']; 
            $game_image_path = "../img/" . $game_id . ".jpg";

            echo "<a href = 'tournament-input.php?tournament_id=" . $tournament['tournament_id'] . "'>" .
                    "<img src = '$game_image_path' width = '180' height = '' >" . "</a><br>";
            echo $tournament['tournament_name'];
        }
        echo "</div>";

        echo "<h3 class = 'board'>掲示板</h3>";
        $genres = $bsql->fetchAll(PDO::FETCH_ASSOC);
        echo "<div class = 'genre'>";
        foreach($genres as $genre){
            $genre_id = $genre['genre_id']; 
            $image_path = "../img/" . $genre_id . ".jpg";
            // echo "<img src = '$image_path' width = '180' height = '' >";
            echo "<a href = 'genre.php?genre_id=" . $genre['genre_id'] . "'>" .
                    "<img src = '$image_path' width = '180' height = '' >" . "</a><br>";
            echo $genre['genre'];
        }
        echo "</div>";

        echo "<h3 class = 'chat'>ランキング</h3>";
        $rankings = $rsql->fetchAll(PDO::FETCH_ASSOC);
        echo "<div class = 'Ranking'>";
        foreach($rankings as $ranking){
            echo $ranking['ranking_id'];
            echo "<a href = 'user-part.php?user_id=" . $ranking['user_id'] . "'>" . $ranking['user_id'] . "</a>";
            echo $ranking['player_rank'];
        }
        echo "</div>";
    ?>
    <script src="../js/jquery-3.7.1.min.js"></script>
    <script src="../js/home.js"></script>
</body>
</html>