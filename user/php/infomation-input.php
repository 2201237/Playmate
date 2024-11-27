<<<<<<< Updated upstream
<?php session_start(); 
require 'db-connect.php';
require 'header.php';
$pdo=new PDO($connect,USER,PASS);

$sql=$pdo->prepare('select * FROM contacts_ge');
$sql->execute();

=======
<?php session_start();
    require 'db-connect.php';

    $pdo = new PDO($connect, USER, PASS);
    $sql = $pdo->prepare('select * FROM contacts_ge');
    $sql->execute();

>>>>>>> Stashed changes
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
<<<<<<< Updated upstream
    <link rel="stylesheet" href="../css/header.css">

    <title>Document</title>
</head>
<body>
    
    <h2 style="text-align:center">お問い合わせ</h2>
    <form action = "infomation-complete.php" method = "post"> 
        <?php
            $contacts_ge = $sql->fetchAll(PDO::FETCH_ASSOC);
            echo "<div  style='text-align:center'>";
            echo "<select name = 'conge_id' required> ";
            echo "<option value='' hidden>選択してください</option>";
                foreach ($contacts_ge as $contacts_ges){
=======
    <link rel="icon" href="../img/favicon.ico">
    <link rel="stylesheet" href="../css/header.css">
    <link rel="stylesheet" href="../css/infomation.css">
    <title>PlayMateお問い合わせ</title>
</head>

<body>
    <?php require 'header.php'; ?>

    <h2 style="text-align:center">お問い合わせ</h2>

    <div include="form-input-select()">
        <select required>
            <!--
            This is how we can do "placeholder" options.
            note: "required" attribute is on the select
            -->
            <?php
                $contacts_ge = $sql->fetchAll(PDO::FETCH_ASSOC);
                echo "<select name = 'conge_id'> ";
                echo "<option value='' hidden>選択してください</option>";
                foreach ($contacts_ge as $contacts_ges) {
>>>>>>> Stashed changes
                    echo "<option value='" . $contacts_ges['conge_id'] . "'>" . $contacts_ges['conge_name'] . "</option>";
                }
            ?>
        </select>
    </div>


            <option value=""
                    
            >Example Placeholder</option>

            <!-- normal options -->
            <option value="1">Option 1</option>
            <option value="2">Option 2</option>
            <option value="3">Option 3</option>
            <option value="4">Option 4</option>
            <option value="5">Option 5</option>


    <form action="infomation-complete.php" method="post">
        
        <div style="text-align:center">お問い合わせ内容<br>
            <textarea name="infomation" required="required"></textarea>
        </div>

        <div style='text-align:center'>
            <input type="submit" class="button" value="送信">
        </div>
    </form>
</body>

</html>