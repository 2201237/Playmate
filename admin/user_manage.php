<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>ユーザー管理</title>
    <style>
        body {
            background-color: #d4edda;
            font-family: Arial, sans-serif;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 8px;
            text-align: left;
        }
        .block-button {
            background-color: red;
            color: white;
            border: none;
            padding: 5px 10px;
            cursor: pointer;
        }
        .block-button:hover {
            background-color: darkred;
        }
    </style>
</head>
<body>
    <h1>ユーザー一覧</h1>
    <a href="logout.php">ログアウト</a>
    <form method="POST">
        <label for="search">ユーザー検索</label>
        <input type="text" id="search" name="search" value="<?php echo htmlspecialchars($search); ?>">
        <button type="submit">検索</button>
    </form>

    <table>
        <tr>
            <th>メールアドレス</th>
            <th>ユーザー名</th>
            <th>自己紹介</th>
            <th>アクション</th>
        </tr>
        <?php foreach ($users as $user): ?>
        <tr>
            <td><?php echo htmlspecialchars($user['email']); ?></td>
            <td><?php echo htmlspecialchars($user['username']); ?></td>
            <td><?php echo htmlspecialchars(mb_strimwidth($user['self_intro'], 0, 20, '...')); ?></td>
            <td>
                <?php if (!$user['blocked']): ?>
                <form method="POST" style="display:inline;">
                    <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                    <button type="submit" name="block" class="block-button">ブロック</button>
                </form>
                <?php else: ?>
                <span>ブロック済み</span>
                <?php endif; ?>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>