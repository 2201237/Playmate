<?php
require 'db-connect.php';
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/reply.css">
    <title>お問い合わせ返信</title>
</head>
<body>
    <a href="inquiry-list.php" class="back">←戻る</a>
    <h1>お問い合わせ返信</h1>
    <a href="login.php" class="logout">ログアウト</a>

<?php
try {
    // データベース接続
    $pdo = new PDO($connect, USER, PASS);

    // contacts_idとuser_idに基づいてデータを取得
    $sql_contacts = $pdo->prepare('SELECT * FROM contacts WHERE contacts_id=?');
    $sql_contacts->execute([$_GET['contacts_id']]);
    $row_contact = $sql_contacts->fetch(PDO::FETCH_ASSOC);

    // データが存在する場合は表示
    if ($row_contact) {
        echo '<table border="1">
                <tr>
                    <th>お問い合わせジャンル</th>
                    <th>お問い合わせ内容</th>
                    <th>ユーザー名</th>
                </tr>';
        echo '<tr>';

        // contactsテーブルから取得した「contact_ge_id」を使ってcontacts_geテーブルの「conge_name」を取ってくる
        $sql_conge = $pdo->prepare('SELECT * FROM contacts_ge WHERE conge_id=?');
        $sql_conge ->execute([$row_contact["contacts_ge_id"]]);
        $row_conge = $sql_conge->fetch(PDO::FETCH_ASSOC);

        echo '<td>' . htmlspecialchars($row_conge["conge_name"]) . '</td>';

        echo '<td>' . htmlspecialchars($row_contact["contacts"]) . '</td>';

        // contactsテーブルから取得した「user_id」を使ってusersテーブルの「user_name」を取ってくる
        $sql_user = $pdo->prepare('SELECT * FROM users WHERE user_id=?');
        $sql_user ->execute([$row_contact["user_id"]]);
        $row_user = $sql_user->fetch(PDO::FETCH_ASSOC);

        echo '<td>' . htmlspecialchars($row_user["user_name"]) . '</td>';
        echo '</tr>';
        echo '</table>';
    } else {
        echo "未解決のお問い合わせはありません。";
    }
} catch (PDOException $e) {
    echo "エラー: " . $e->getMessage();
} finally {
    $pdo = null; // 接続を閉じる
}
?>

    <form method="POST" action="reply-output.php" class="message-form">
        <textarea name="message" placeholder="メッセージ入力" class="message"></textarea>
        <div class="input-container" style="text-align: center;">
            <input type="hidden" name="reply_id" value="<?php echo htmlspecialchars($_GET['contacts_id']); ?>">
            <button type="submit">送信</button>
        </div>
    </form>
</body>
</html>
