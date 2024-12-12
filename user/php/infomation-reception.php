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
    <link rel="stylesheet" href="../css/infomation-reception.css">

    <title>Document</title>
</head>
<body>
    
    <h2 style="text-align:center">お問い合わせ一覧</h2>
<?php
try {
    $pdo = new PDO($connect, USER, PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
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
                </tr>';
        while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo '<tr>';
            echo '<td>' . htmlspecialchars($row["contacts"]) . '</td>';
            echo '<td>' . htmlspecialchars($row["user_name"]) . '</td>';
            echo '<td>' . htmlspecialchars($row["reply"]) . '</td>';

            
            // echo '<td>
            //         <form action="infomation-reply.php" method="post">
            //             <input type="hidden" name="contacts_id" value="' . htmlspecialchars($row["contacts_id"]) . '">
            //             <input type="hidden" name="status" value="0">
            //             <button type="submit" class="restore-button">返信する</button>
            //         </form>
            //       </td>';
            // echo '</tr>';
        }
        echo '</table>';
    } else {
        echo "現在受信しているメッセージはありません。";
    }
} catch (PDOException $e) {
    echo "エラー: " . $e->getMessage();
} finally {
    $pdo = null; // 接続を閉じる
}
?>
    <button class="menu-button" onclick="location.href='query-top.php'">メニューへ</button>
</body>
</html>