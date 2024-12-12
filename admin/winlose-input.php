<?php
    session_start();
    require 'db-connect.php';

    // if( ! isset($_SESSION['admin_logged_in']) ) {
    //     echo 'ログインしてください';
    // }
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/winlose.css">
    <title>勝敗登録</title>
</head>
<body>
    <div class="title-area">
        <div class="title-text">
            勝敗登録
        </div>
    </div>

    <div class="container">
        <form action="winlose-output.php" method="post">
           
            <label for="user_select">勝利したユーザーを選択:</label>
            <select name="winer" id="winter" required>
                <?php
                    try {
                        $pdo = new PDO($connect, USER, PASS);
                        $users_date = $pdo->query('select * from users');
                        foreach ($users_date as $user) {
                            echo '<option value="',$user['user_id'], '">',
                                    $user['user_id'],'：',$user['user_name'], 
                                '</option>';
                        }
                    } catch (PDOException $e) {
                        echo '<option value="">エラーが発生しました</option>';
                    }
                ?>
            </select>

            <label for="user_select">敗北したユーザーを選択:</label>
            <select name="loser" id="loser" required>
                <?php
                    try {
                        $pdo = new PDO($connect, USER, PASS);
                        $users_date = $pdo->query('select * from users');
                        foreach ($users_date as $user) {
                            echo '<option value="',$user['user_id'],'">',
                                    $user['user_id'],'：',$user['user_name'], 
                                '</option>';
                        }
                    } catch (PDOException $e) {
                        echo '<option value="">エラーが発生しました</option>';
                    }
                ?>
            </select>
           
            　
            
        
            <div class="button-group">
                <button type="submit" class="create">作成</button>
            </div>

        </form>
    </div>
</body>
</html>