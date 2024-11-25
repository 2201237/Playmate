<?php session_start(); 
require 'db-connect.php';
require 'header.php';
$pdo=new PDO($connect,USER,PASS);

$sql=$pdo->prepare('select * FROM contacts_ge');
$sql->execute();


?>
    <h2 style="text-align:center">お問い合わせ</h2>
    <form action = "infomation-complete.php" method = "post"> 
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
</body>
</html>