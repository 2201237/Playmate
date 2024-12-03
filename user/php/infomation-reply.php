<?php session_start(); 
require 'db-connect.php';
require 'header.php';
$pdo=new PDO($connect,USER,PASS);

$sql=$pdo->prepare('select * FROM contacts_ge');
$sql->execute();

?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/header.css">

    <title>Document</title>
</head>
<body>
    <h1>お問い合わせ返信</h1>
<?php
try {
    // データベース接続
    $pdo = new PDO($connect, USER, PASS);

    // contacts_idとuser_idに基づいてデータを取得
    $sql_contacts = $pdo->prepare('SELECT * FROM contacts WHERE contacts_id=?');
    $sql_contacts->execute([$_POST['contacts_id']]);
    $row_contact = $sql_contacts->fetch(PDO::FETCH_ASSOC);

    // データが存在する場合は表示
    if ($row_contact) {
        echo '<table border="1">
                <tr>
                    <th>お問い合わせ内容</th>
                    <th>ユーザー名</th>
                    <th>返信内容</th>
                </tr>';

        echo '<tr>';
        echo '<td>' . htmlspecialchars($row_contact["contacts"]) . '</td>';

        // contactsテーブルから取得した「user_id」を使ってusersテーブルの「user_name」を取ってくる
        $sql_user = $pdo->prepare('SELECT * FROM users WHERE user_id=?');
        $sql_user ->execute([$row_contact["user_id"]]);
        $row_user = $sql_user->fetch(PDO::FETCH_ASSOC);

        echo '<td>' . htmlspecialchars($row_user["user_name"]) . '</td>';

        echo '<td>' . htmlspecialchars($row_contact["reply"]) . '</td>';
        echo '</tr>';
        echo '</table>';
    } else {
        
    }
} catch (PDOException $e) {
    echo "エラー: " . $e->getMessage();
} finally {
    $pdo = null; // 接続を閉じる
}
?>

    <form method="post" action="reply-output.php" class="message-form">
        <textarea name="message" placeholder="メッセージ入力" class="message"></textarea>
        <div class="input-container" style="text-align: center;">
            <input type="hidden" name="reply_id" value="<?php echo htmlspecialchars($_POST['contacts_id']); ?>">
            <button type="submit">送信</button>
        </div>
    </form>
    <button class="menu-button" onclick="location.href='infomation-reception.php'">戻る</button>
</body>
</html>