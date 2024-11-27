<?php require 'db-connect.php' ?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="css/title_change.css">
    <title>ゲームジャンル変更</title>                              
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
        <h1>ゲームジャンル変更</h1>
        <a href="login.php" class="logout">ログアウト</a>
       
        <form method="GET" action="genre_change.php" class="submit-form">
            <input type="text" name="title" placeholder="ゲームジャンル検索" value="<?= htmlspecialchars($_GET['genre'] ?? '', ENT_QUOTES) ?>">
            <button type="submit">検索</button>
        </form>
       
        <div class="grouptable">
            <table align="center" border="1">
                <tr>
                    <th>ゲームジャンル</th>
                    <th>更新</th>
                </tr>
               
                <?php
                try {
                    // データベース接続
                    $pdo = new PDO($connect, USER, PASS);

                    // 検索条件を取得
                    $searchGenre = $_GET['genre'] ?? '';

                    // SQLクエリの組み立て
                    if (!empty($searchGenre)) {
                        $sql = $pdo->prepare('SELECT * FROM genre WHERE genre LIKE :genre');
                        $sql->execute([':genre' => '%' . $searchGenre . '%']);
                    } else {
                        $sql = $pdo->query('SELECT * FROM genre');
                    }

                    // 結果をテーブルに表示
                    foreach ($sql as $row) {
                        echo '<tr>';
                        echo '<form action="genre_change.php" method="post">';
                        echo '<input type="hidden" name="genre_id" value="' . htmlspecialchars($row['genre_id'], ENT_QUOTES) . '">';
                        echo '<td><input type="text" name="genre" value="' . htmlspecialchars($row['genre'], ENT_QUOTES) . '"></td>';
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
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['genre_id'], $_POST['genre'])) {
        $genre_id = $_POST['genre_id'];
        $new_genre = $_POST['genre'];

        if (!empty($new_genre)) {
            $update_sql = $pdo->prepare('UPDATE genre SET genre = :genre WHERE genre_id = :genre_id');
            $update_sql->execute([
                ':genre' => $new_genre,
                ':genre_id' => $genre_id
            ]);
            echo '<p>ゲームジャンルが更新されました。</p>';
        } else {
            echo '<p>ゲームジャンルを入力してください。</p>';
        }
    }
    ?>
</body>
</html>