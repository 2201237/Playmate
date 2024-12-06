<?php
try {
    session_start();
    require 'db-connect.php';

    $pdo = new PDO($connect, USER, PASS);

    // 1. 大会リストを取得
    $query = "SELECT tournament_id, tournament_name FROM tournament";
    $stmt = $pdo->prepare($query);
    $stmt->execute();
    $tournaments = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // エラーメッセージ用
    $error_message = "";

    // 表示用データ初期化
    $selected_tournament_id = null;
    $selected_round = null;
    $kumi_data = [];

    // 2. POSTリクエストがある場合の処理
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['tournament_id']) && isset($_POST['round'])) {
            $tournament_id = (int)$_POST['tournament_id'];
            $round = (int)$_POST['round'];

            // 組み分けが既に存在するか確認
            $query = "
                SELECT COUNT(*) AS count
                FROM tournament_kumi
                WHERE tournament_id = :tournament_id AND round = :round;
            ";
            $stmt = $pdo->prepare($query);
            $stmt->bindParam(':tournament_id', $tournament_id, PDO::PARAM_INT);
            $stmt->bindParam(':round', $round, PDO::PARAM_INT);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($result['count'] > 0) {
                $error_message = "エラー: この大会とラウンドの組み分けデータはすでに存在します。";
            } else {
                // losserが1でないユーザーをランダムに取得
                $query = "
                    SELECT user_id
                    FROM tournament_member
                    WHERE tournament_id = :tournament_id AND loser != 1
                    ORDER BY RAND();
                ";
                $stmt = $pdo->prepare($query);
                $stmt->bindParam(':tournament_id', $tournament_id, PDO::PARAM_INT);
                $stmt->execute();
                $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

                // ユーザーが偶数でない場合の警告
                if (count($users) % 2 != 0) {
                    echo "警告: ユーザー数が奇数です。最後の1人が残ります。\n";
                }

                // 組み分け処理 (2人1組)
                for ($i = 0; $i < count($users); $i += 2) {
                    if (isset($users[$i + 1])) {
                        // 正常な組み合わせ
                        $query = "
                            INSERT INTO tournament_kumi (tournament_id, round, user_id1, user_id2)
                            VALUES (:tournament_id, :round, :user_id1, :user_id2);
                        ";
                        $stmt = $pdo->prepare($query);
                        $stmt->bindParam(':tournament_id', $tournament_id, PDO::PARAM_INT);
                        $stmt->bindParam(':round', $round, PDO::PARAM_INT);
                        $stmt->bindParam(':user_id1', $users[$i]['user_id'], PDO::PARAM_INT);
                        $stmt->bindParam(':user_id2', $users[$i + 1]['user_id'], PDO::PARAM_INT);
                        $stmt->execute();
                    } else {
                        // 奇数の場合
                        $query = "
                            INSERT INTO tournament_kumi (tournament_id, round, user_id1, user_id2)
                            VALUES (:tournament_id, :round, :user_id1, NULL);
                        ";
                        $stmt = $pdo->prepare($query);
                        $stmt->bindParam(':tournament_id', $tournament_id, PDO::PARAM_INT);
                        $stmt->bindParam(':round', $round, PDO::PARAM_INT);
                        $stmt->bindParam(':user_id1', $users[$i]['user_id'], PDO::PARAM_INT);
                        $stmt->execute();
                    }
                }

                // 成功メッセージ
                echo "組み分けが完了しました！<br>";
            }

            // 選択された大会とラウンドを保持
            $selected_tournament_id = $tournament_id;
            $selected_round = $round;
        }
    }

    // 組み分け結果を取得
    if ($selected_tournament_id !== null && $selected_round !== null) {
        $query = "
            SELECT user_id1, user_id2
            FROM tournament_kumi
            WHERE tournament_id = :tournament_id AND round = :round;
        ";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':tournament_id', $selected_tournament_id, PDO::PARAM_INT);
        $stmt->bindParam(':round', $selected_round, PDO::PARAM_INT);
        $stmt->execute();
        $kumi_data = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
} catch (PDOException $e) {
    // エラーハンドリング
    echo "エラー: " . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/tournament-sorting.css">
    <title>大会組み分け</title>
</head>
<body>
    <h1>大会組み分けフォーム</h1>
    <form action="tournament-sorting.php" method="POST">
        <label for="tournament_id">大会を選択:</label>
        <select name="tournament_id" id="tournament_id" required>
            <option value="">選択してください</option>
            <?php
            foreach ($tournaments as $tournament) {
                // 選択済みの大会IDの場合は selected を付与
                $selected = ($tournament['tournament_id'] == $selected_tournament_id) ? 'selected' : '';
                echo "<option value=\"{$tournament['tournament_id']}\" $selected>{$tournament['tournament_name']}</option>";
            }
            ?>
        </select>
        <br><br>
        <label for="round">ラウンド番号:</label>
        <input type="number" name="round" id="round" min="1" value="<?= htmlspecialchars($selected_round) ?>" required>
        <br><br>
        <button type="submit">組み分け開始</button>
    </form>

    <?php if (!empty($error_message)): ?>
        <p style="color: red;"><?= htmlspecialchars($error_message) ?></p>
    <?php endif; ?>

    <hr>

    <!-- 組み分け表を表示 -->
    <?php if (!empty($kumi_data)): ?>
        <h2>組み分け結果 (大会ID: <?= htmlspecialchars($selected_tournament_id) ?>, ラウンド: <?= htmlspecialchars($selected_round) ?>)</h2>
        <table border="1">
            <tr>
                <th>ユーザー1</th>
                <th>ユーザー2</th>
            </tr>
            <?php foreach ($kumi_data as $kumi): ?>
                <tr>
                    <td><?= htmlspecialchars($kumi['user_id1']) ?></td>
                    <td><?= htmlspecialchars($kumi['user_id2'] ?? '不戦勝') ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php endif; ?>

    <!-- 戻るボタン -->
    <br><br>
    <button onclick="location.href='tournament.php'">戻る</button>
</body>
</html>
