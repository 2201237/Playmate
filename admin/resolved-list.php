<!DOCTYPE html>
<html lang="ja">
<?php
require 'db-connect.php';
?>
<head>
    <meta charset="UTF-8">
    <title>お問い合わせ解決一覧</title>
    <link rel="stylesheet" href="css/resolved-list.css">
</head>
<body>
<h1>お問い合わせ一覧（解決）</h1>
<a href="inquiry-list.php" class="back">←戻る</a>
<a href="login.php" class="logout">ログアウト</a>

<!-- ユーザー名検索フォーム -->
<form method="GET" action="resolved-list.php" class="search-form">
    <input type="text" name="user_name" placeholder="ユーザー検索">
    <button type="submit">検索</button>
</form>

<?php
try {
    $pdo = new PDO($connect, USER, PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // ユーザー名検索用
    $user_name = isset($_GET['user_name']) ? $_GET['user_name'] : '';

    // ベースSQLクエリ
    $sql = "SELECT contacts.contacts_id, contacts.contacts, users.user_name, contacts.reply
            FROM contacts 
            JOIN users ON contacts.user_id = users.user_id
            WHERE contacts.status = 1";

    // ユーザー名でのフィルタリング
    if (!empty($user_name)) {
        $sql .= " AND users.user_name LIKE :user_name";
    }

    $stmt = $pdo->prepare($sql);

    // プレースホルダに値をバインド
    if (!empty($user_name)) {
        $stmt->bindValue(':user_name', '%' . $user_name . '%', PDO::PARAM_STR);
    }

    $stmt->execute();

    // テーブル表示
    if ($stmt->rowCount() > 0) {
        echo '<table border="1">
                <tr>
                    <th>お問い合わせ内容</th>
                    <th>ユーザー名</th>
                    <th>返信内容</th>
                    <th>戻す</th>
                </tr>';
        while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo '<tr>';
            echo '<td>' . htmlspecialchars($row["contacts"]) . '</td>';
            echo '<td>' . htmlspecialchars($row["user_name"]) . '</td>';
            echo '<td>' . htmlspecialchars($row["reply"]) . '</td>';

            // 戻すボタン（`status` を 0 に戻す）
            echo '<td>
                    <form action="update-status.php" method="post">
                        <input type="hidden" name="contacts_id" value="' . htmlspecialchars($row["contacts_id"]) . '">
                        <input type="hidden" name="status" value="0">
                        <button type="submit" style="background-color: #555; color: white;">戻す</button>
                    </form>
                  </td>';
            echo '</tr>';
        }
        echo '</table>';
    } else {
        echo "解決済みのお問い合わせはありません。";
    }
} catch (PDOException $e) {
    echo "エラー: " . $e->getMessage();
} finally {
    $pdo = null; // 接続を閉じる
}
?>
</body>
</html>