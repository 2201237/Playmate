<?php
session_start();

if (isset($_SESSION["status_message"])) {
    echo "<p>" . htmlspecialchars($_SESSION["status_message"]) . "</p>";
    unset($_SESSION["status_message"]); // 表示後、メッセージを削除
}
?>
<!DOCTYPE html>
<html lang="ja">
<?php
require 'db-connect.php';
?>
<head>
    <meta charset="UTF-8">
    <title>お問い合わせ一覧</title>
    <link rel="stylesheet" href="css/inquiry-list.css"> <!-- 外部CSSをリンク -->
</head>
<body>
<h1>お問い合わせ一覧</h1>
<a href="contact.php" class="back">←戻る</a>
<a href="login.php" class="logout">ログアウト</a>
<div class="button-container">
    <button onclick="window.location.href='resolved-list.php'">解決一覧</button>
    <button onclick="window.location.href='on-hold-list.php'">保留一覧</button>
</div>
<?php
try {
    $pdo = new PDO($connect, USER, PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // contactsテーブルからデータを取得
    $sql = "SELECT contacts.contacts_id, contacts.contacts, users.user_name, contacts.status, contacts_ge.conge_name 
            FROM contacts 
            JOIN users ON contacts.user_id = users.user_id
            JOIN contacts_ge ON contacts.contacts_ge_id = contacts_ge.conge_id";
    $stmt = $pdo->query($sql);

    // テーブル表示
    if ($stmt->rowCount() > 0) {
        echo '<table border="1">
                <tr>
                    <th>お問い合わせジャンル</th>
                    <th>お問い合わせ内容</th>
                    <th>ユーザー名</th>
                    <th>ステータス</th>
                    <th>解決</th>
                    <th>保留</th>
                    <th>返信</th>
                </tr>';
        while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo '<tr>';
            echo '<td>' . htmlspecialchars($row["conge_name"]) . '</td>';
            echo '<td>' . htmlspecialchars($row["contacts"]) . '</td>';
            echo '<td>' . htmlspecialchars($row["user_name"]) . '</td>';

            // ステータス表示
            $status_text = '';
            switch ($row["status"]) {
                case 0:
                    $status_text = '未解決';
                    break;
                case 1:
                    $status_text = '解決';
                    break;
                case 2:
                    $status_text = '保留';
                    break;
            }
            echo '<td>' . htmlspecialchars($status_text) . '</td>';

            // 解決と保留ボタン
            echo '<td>
                    <form action="update-status.php" method="post">
                        <input type="hidden" name="contacts_id" value="' . htmlspecialchars($row["contacts_id"]) . '">
                        <input type="hidden" name="status" value="1">
                        <button type="submit" style="background-color: red; color: white;">解決</button>
                    </form>
                  </td>';
            
            echo '<td>
                    <form action="update-status.php" method="post">
                        <input type="hidden" name="contacts_id" value="' . htmlspecialchars($row["contacts_id"]) . '">
                        <input type="hidden" name="status" value="2">
                        <button type="submit" style="background-color: blue; color: white;">保留</button>
                    </form>
                  </td>';

            // 返信ボタン
            echo '<td>
                    <form action="reply.php" method="get">
                        <input type="hidden" name="contacts_id" value="' . htmlspecialchars($row["contacts_id"]) . '">
                        <button type="submit" style="background-color: green; color: white;">返信</button>
                    </form>
                  </td>';
            
            echo '</tr>';
        }
        echo '</table>';
    } else {
        echo "お問い合わせはありません。";
    }
} catch (PDOException $e) {
    echo "エラー: " . $e->getMessage();
} finally {
    $pdo = null; // 接続を閉じる
}
?>
</body>
</html>