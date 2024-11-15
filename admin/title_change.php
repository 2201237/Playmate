<?php require 'db-connect.php'?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="css/title_change.css">
    <title>PlayMate Admin</title>                               
</head>
<body>
    <script>
        console.log("window.name :" + window.name);
        window.onload = function () {
            if (window.name != "any") {
                location.reload();
                window.name = "any";
            } else {        
                window.name = "";

            }
        }
    </script>
    <div class="list_field">
        <a href="game-manage.php" return false;" class="back">←戻る</a>
        <h1>ゲームタイトル変更</h1>
        <a href="login.php" class="logout">ログアウト</a>
        
        <form action="title_change.php" method="post" class="submit-form">
        <input type="text" name="user_name" placeholder="キーワード検索">
        <button type="submit">検索</button>
        </form>
        
        <div class="grouptable">
            <table align="center" border="1">
                <tr>
                    <th>ゲームタイトル</th>
                    <th>更新</th>
                </tr>
                
                <?php
                $pdo = new PDO($connect, USER, PASS);
                
                // ゲーム情報を1回のクエリで取得
                $sql = $pdo->query('SELECT * FROM game');
                foreach ($sql as $row) {
                    echo '<tr>';
                    // タイトル更新用のフォーム
                    echo '<form action="title_change.php" method="post">';
                    echo '<input type="hidden" name="game_id" value="' . $row['game_id'] . '">'; // ゲームIDを隠しフィールドに設定
                    echo '<td><input type="text" name="title" value="' . htmlspecialchars($row['title'], ENT_QUOTES) . '"></td>';
                    echo '<td><button type="submit">更新</button></td>';
                    echo '</form>';
                    echo '</tr>';
                }
                ?>
            </table>
        </div>
    </div>

    <?php
    // フォームがPOSTされたときに処理を行う
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['title'], $_POST['game_id'])) {
        $game_id = $_POST['game_id'];
        $new_title = $_POST['title'];

        // 入力されたタイトルが空でないか確認
        if (!empty($new_title)) {
            // ゲームタイトルを更新するクエリ
            $update_sql = $pdo->prepare('UPDATE game SET title = :title WHERE game_id = :game_id');
            $update_sql->execute([
                ':title' => $new_title,
                ':game_id' => $game_id
            ]);
            echo '<p></p>';
        } else {
            echo '<p>タイトルを入力してください。</p>';
        }
    }
    ?>
</body>
</html>