<?php session_start(); 
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
    <title>PlayMate</title>
</head>
<body>
    <h3 class = "join" >現在大会中の参加</h3>
    <?php 
    $tournaments = $tsql->fetchAll(PDO::FETCH_ASSOC);
    echo "<div class = 'Tournament'>";
    foreach ($tournaments as $tournament) {
        echo "<a href = 'tournament.php?tournament_id=" . $tournament['tournament_id'] . "'>" . $tournament['tournament_id'] . "</a>";
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
</body>
</html>