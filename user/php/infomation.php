<?php session_start(); 
require 'db-connect.php';
require '../header.html';
$pdo=new PDO($connect,USER,PASS);

$sql=$pdo->prepare('select * FROM contacts');
$sql->execute();

$sql=$pdo->prepare('select * FROM contacts_ge');
$sql->execute();


?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    
</body>
</html>