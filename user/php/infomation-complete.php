<?php
session_start();
require 'db-connect.php';
$pdo=new PDO($connect,USER,PASS);

$lastId=$pdo->lastInsertId();

$sql=$pdo->prepare('insert into contacts(contacts, contacts_ge_id) values(?,?)');
$sql->execute([$_POST['infomation'],$_POST['conge_id']]);

echo '<h1>お問い合わせ内容が送信されました。</h1>';
echo '<p>返信内容が返却されるまでしばらくお待ちください</p>';
echo "<a href = 'home.php'>戻る";

?>
