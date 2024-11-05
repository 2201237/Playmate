<!DOCTYPE html>
<html lang="ja">
<?php
require 'db-connect.php';
?>
<head>
    <meta charset="UTF-8">
    <title>お問い合わせ保留</title>
    <link rel="stylesheet" href="css/on-hold.css">
</head>
<body>
<h1>お問い合わせ保留</h1>
<div class="button-container">
    <button onclick="window.location.href='resolved-list.php'">解決一覧</button>
</div>
<a href="inquiry-list.php" class="back">←戻る</a>
<a href="login.php" class="logout">ログアウト</a>

<?php
try {
    $pdo = new PDO($connect, USER, PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // status が 2 の問い合わせを取得する SQL
    $sql = "SELECT contacts.contacts_id, contacts.contacts, users.user_name
            FROM contacts 
            JOIN users ON contacts.user_id = users.user_id
            WHERE contacts.status = 2";
    $stmt = $pdo->query($sql);

    // テーブル表示
    if ($stmt->rowCount() > 0) {
        echo '<table border="1">
                <tr>
                    <th>お問い合わせ内容</th>
                    <th>ユーザー名</th>
                    <th>解決</th>
                </tr>';
        while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo '<tr>';
            echo '<td>' . htmlspecialchars($row["contacts"]) . '</td>';
            echo '<td>' . htmlspecialchars($row["user_name"]) . '</td>';

            // 解決ボタン（status を 1 に変更）
            echo '<td>
                    <form action="update-status.php" method="post">
                        <input type="hidden" name="contacts_id" value="' . htmlspecialchars($row["contacts_id"]) . '">
                        <input type="hidden" name="status" value="1">
                        <button type="submit" style="background-color: #f00; color: white;">解決</button>
                    </form>
                  </td>';
            echo '</tr>';
        }
        echo '</table>';
    } else {
        echo "保留中のお問い合わせはありません。";
    }
} catch (PDOException $e) {
    echo "エラー: " . $e->getMessage();
} finally {
    $pdo = null; // 接続を閉じる
}
?>
</body>
</html>