<?php require 'db-connect.php'
$message = $_POST["message"];
$pdo = new PDO($connect, USER, PASS);

?>
