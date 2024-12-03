<?php 
session_start(); 
require 'db-connect.php';
require 'header.php';
$pdo=new PDO($connect,USER,PASS);

$sql=$pdo->prepare('select * FROM contacts_ge');
$sql->execute();

?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/header.css">
    <link rel="stylesheet" href="../css/infomation-input.css">

    <title>Document</title>
</head>
<body>
    
    <h2 style="text-align:center">お問い合わせ</h2>
    <form action ="infomation-complete.php" method ="post"> 
        <?php
            $contacts_ge = $sql->fetchAll(PDO::FETCH_ASSOC);
            echo "<div  style='text-align:center'>";
            echo "<select name = 'conge_id' required> ";
            echo "<option value='' hidden>選択してください</option>";
                foreach ($contacts_ge as $contacts_ges){
                    echo "<option value='" . $contacts_ges['conge_id'] . "'>" . $contacts_ges['conge_name'] . "</option>";
                }
            echo "</select>";
            echo "</div>";
        ?>
        
        <div style="text-align:center">お問い合わせ内容<br>
        <textarea name="infomation" required="required"></textarea></div>

        <div  style='text-align:center'>
            <input type ="submit" class = "button" value = "送信">
        </div>
    </form>
    <button class="menu-button" onclick="location.href='query-top.php'">戻る</button>
</body>
</html>