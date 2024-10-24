<?php session_start(); 
require 'db-connect.php';
require '../../home.html';
$pdo=new PDO($connect,USER,PASS);
$tsql=$pdo->prepare('select * FROM tournament');
$tsql->execute();

$csql=$pdo->prepare('select * FROM chat_boards');
$csql->execute();

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
        echo "<a href = 'tournament.php?tournament_id=" . $tournament['tournament_id'] . "'>" . $tournament['tournament_id'];
        echo $tournament['tournament_name'];
    }
    echo "</div>";

    echo "<h3 class = 'chat'>掲示板</h3>";
    $chat_bords = $csql->fetchAll(PDO::FETCH_ASSOC);
    echo "<div class = 'Chat_bord'>";
    foreach($chat_bords as $chat_bord){
        echo "<a href = 'chat_bord.php?chat_id=" . $chat_bord['chat_id'] . "'>" . $chat_bord['chat_id'];
        echo $chat_bord['chat_name'];
    }
    echo "</div>";

    echo "<h3 class = 'chat'>ランキング</h3>";
    $rankings = $rsql->fetchAll(PDO::FETCH_ASSOC);
    echo "<div class = 'Ranking'>";
    foreach($rankings as $ranking){
        echo $ranking['ranking_id'];
        echo "<a href = 'user-part.php?user_id=" . $ranking['user_id'] . "'>" . $ranking['user_id'];
        echo $ranking['player_rank'];
    }
    echo "</div>";
    ?>
    <a href = "infomation.php">お問い合わせ</a>
</body>
</html>