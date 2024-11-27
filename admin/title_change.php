<?php require 'db-connect.php' ?>
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
       
        <form method="GET" action="title_change.php" class="submit-form">
            <input type="text" name="title" placeholder="タイトル検索" value="<?= htmlspecialchars($_GET['title'] ?? '', ENT_QUOTES) ?>">
            <button type="submit">検索</button>
        </form>
       
        <div class="grouptable">
            <table align="center" border="1">
                <tr>
                    <th>ゲームタイトル</th>
                    <th>更新</th>
                </tr>
               
                <?php
                try {
                    // データベース接続
                    $pdo = new PDO($connect, USER, PASS);

                    // 検索条件を取得
                    $searchTitle = $_GET['title'] ?? '';

                    // SQLクエリの組み立て
                    if (!empty($searchTitle)) {
                        $sql = $pdo->prepare('SELECT * FROM game WHERE title LIKE :title');
                        $sql->execute([':title' => '%' . $searchTitle . '%']);
                    } else {
                        $sql = $pdo->query('SELECT * FROM game');
                    }

                    // 結果をテーブルに表示
                    foreach ($sql as $row) {
                        echo '<tr>';
                        echo '<form action="title_change.php" method="post">';
                        echo '<input type="hidden" name="game_id" value="' . htmlspecialchars($row['game_id'], ENT_QUOTES) . '">';
                        echo '<td><input type="text" name="title" value="' . htmlspecialchars($row['title'], ENT_QUOTES) . '"></td>';
                        echo '<td><button type="submit">更新</button></td>';
                        echo '</form>';
                        echo '</tr>';
                    }
                } catch (Exception $e) {
                    echo '<tr><td colspan="2">エラーが発生しました: ' . htmlspecialchars($e->getMessage(), ENT_QUOTES) . '</td></tr>';
                }
                ?>
            </table>
        </div>
    </div>

    <?php
    // タイトル更新処理
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['game_id'], $_POST['title'])) {
        $game_id = $_POST['game_id'];
        $new_title = $_POST['title'];

        if (!empty($new_title)) {
            $update_sql = $pdo->prepare('UPDATE game SET title = :title WHERE game_id = :game_id');
            $update_sql->execute([
                ':title' => $new_title,
                ':game_id' => $game_id
            ]);
            echo '<p>タイトルが更新されました。</p>';
        } else {
            echo '<p>タイトルを入力してください。</p>';
        }
    }
    ?>
</body>
</html>
