<?php 
    session_start();
    require 'db-connect.php';

    $pdo = new PDO($connect, USER, PASS);
    
    $sql = $pdo->prepare('INSERT INTO winlose(win_lose, user_id) VALUES (1,?)');
    $sql->execute([$_POST['winer']]);

    $sql = $pdo->prepare('INSERT INTO winlose(win_lose, user_id) VALUES (0,?)');
    $sql->execute([$_POST['loser']]);

    header('Location: admintop.php');
    exit();
?>