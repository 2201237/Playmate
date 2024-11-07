<?php
session_start(); // セッション開始

// ログインチェック
if (!isset($_SESSION['user_id'])) { // user_idがセッションにない場合
    header('Location: notlogin.php'); // login.phpにリダイレクト
    exit(); // 以降のコードを実行しない
}
?>
<?php
require 'db-connect.php';

try {
    $pdo = new PDO($connect, USER, PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // 初期値
    $mode = 'list'; // デフォルトは一覧表示モード
    $message = '';

    // 新規追加の処理
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add') {
        $name = $_POST['name'] ?? '';
        if ($name !== '') {
            $stmt = $pdo->prepare("INSERT INTO contacts_ge (conge_name) VALUES (:name)");
            $stmt->bindValue(':name', $name, PDO::PARAM_STR);
            $stmt->execute();
            $message = 'ジャンルを追加しました。';
        } else {
            $message = 'ジャンル名を入力してください。';
        }
    }

    // 更新処理
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update') {
        $id = $_POST['id'] ?? '';
        $name = $_POST['name'] ?? '';
        if ($id !== '' && $name !== '') {
            $stmt = $pdo->prepare("UPDATE contacts_ge SET conge_name = :name WHERE conge_id = :id");
            $stmt->bindValue(':name', $name, PDO::PARAM_STR);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            $message = 'ジャンルを更新しました。';
        } else {
            $message = 'IDとジャンル名を入力してください。';
        }
    }

    // 削除処理
    if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action']) && $_GET['action'] === 'delete') {
        $id = $_GET['id'] ?? '';
        if ($id !== '') {
            $stmt = $pdo->prepare("DELETE FROM contacts_ge WHERE conge_id = :id");
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            $message = 'ジャンルを削除しました。';
        }
    }

    // ジャンル一覧の取得
    $stmt = $pdo->query("SELECT conge_id, conge_name FROM contacts_ge");
    $genres = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    echo 'Connection failed: ' . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>ジャンル管理</title>
    <style>
        body { 
            background-color: #aaffaa; 
            display: flex; 
            flex-direction: column; 
            align-items: center; 
        }
        h1 { text-align: center; }
        form { 
            margin: 10px 0; 
            display: flex; 
            flex-direction: column; 
            align-items: center; 
        }
        table {
            border-collapse: collapse; 
            margin-top: 20px;
        }
        td, th {
            border: 1px solid black; 
            padding: 5px; 
        }
        .back, .logout {
    position: absolute;
    top: 20px;
    font-size: 20px;
}

.back {
    left: 20px;
}

.logout {
    right: 20px;
}
    </style>
</head>
<body>
    <h1>ジャンル管理</h1>
    <a href="contact.php" class="back">←戻る</a>
    <a href="login.php" class="logout">ログアウト</a>
    <?php if ($message): ?>
        <p><?= htmlspecialchars($message) ?></p>
    <?php endif; ?>

    <!-- 新規追加フォーム -->
    <form method="POST">
        <input type="hidden" name="action" value="add">
        <input type="text" name="name" placeholder="ジャンル名を入力">
        <button type="submit">追加</button>
    </form>

    <!-- ジャンル一覧表示 -->
    <table>
        <tr>
            <th>ID</th>
            <th>ジャンル名</th>
            <th>操作</th>
        </tr>
        <?php foreach ($genres as $genre): ?>
            <tr>
                <td><?= htmlspecialchars($genre['conge_id']) ?></td>
                
                <!-- ジャンル名編集用テキストボックス -->
                <td>
                    <form method="POST" style="display: inline;">
                        <input type="hidden" name="action" value="update">
                        <input type="hidden" name="id" value="<?= $genre['conge_id'] ?>">
                        <input type="text" name="name" value="<?= htmlspecialchars($genre['conge_name']) ?>">
                        <button type="submit">更新</button>
                    </form>
                </td>
                
                <!-- 削除ボタン -->
                <td>
                    <a href="?action=delete&id=<?= $genre['conge_id'] ?>" onclick="return confirm('本当に削除しますか？');">削除</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>