<?php require 'db-connect.php' ?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="css/user_block.css">
    <title>PlayMate Admin</title>                               
</head>
<body>
    <div class="list_field">
        <a href="user.php" return false; class="back">←戻る</a>
        <h1>BANユーザー</h1>
        <a href="login.php" class="logout">ログアウト</a>

        <!-- 検索フォーム -->
        <form method="GET" action="user_block.php" class="search-form">
            <input type="text" name="user_name" placeholder="ユーザー検索" value="<?= htmlspecialchars($_GET['user_name'] ?? '', ENT_QUOTES) ?>">
            <button type="submit">検索</button>
        </form>

        <button onclick="location.href='user_manage.php'" class="add">ユーザー一覧へ</button>

        <div class="grouptable">
            <table align="center" border="1">
                <tr>
                    <th>メールアドレス</th>
                    <th>ユーザー名</th>
                    <th>自己紹介</th>
                    <th></th>
                </tr>

                <?php
                $pdo = new PDO($connect, USER, PASS);

                // ユーザー検索処理
                $searchName = $_GET['user_name'] ?? '';

                if (!empty($searchName)) {
                    // 検索条件に一致するBANされたユーザーを取得
                    $sql = $pdo->prepare('
                        SELECT u.user_mail, u.user_name, u.profile, b.user_id 
                        FROM ban b 
                        JOIN users u ON b.user_id = u.user_id 
                        WHERE u.user_name LIKE :user_name
                    ');
                    $sql->execute([':user_name' => '%' . $searchName . '%']);
                } else {
                    // 全てのBANされたユーザーを取得
                    $sql = $pdo->query('
                        SELECT u.user_mail, u.user_name, u.profile, b.user_id 
                        FROM ban b 
                        JOIN users u ON b.user_id = u.user_id
                    ');
                }

                // 検索結果を表示
                foreach ($sql as $row) {
                    echo '<tr>';
                    echo '<td>' . htmlspecialchars($row['user_mail'], ENT_QUOTES) . '</td>';
                    echo '<td>' . htmlspecialchars($row['user_name'], ENT_QUOTES) . '</td>';
                    echo '<td>' . htmlspecialchars($row['profile'], ENT_QUOTES) . '</td>';
                    echo '<td>';
                    echo '<div class="unlock"><a href="user_manage.php?id=' . htmlspecialchars($row['user_id'], ENT_QUOTES) . '"><button type="button">解除</button></a></div>';
                    echo '</td>';
                    echo '</tr>';
                }
                ?>
            </table>
        </div>
    </div>
</body>
</html>
