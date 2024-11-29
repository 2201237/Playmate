<?php
require 'db-connect.php';

try {
    $pdo = new PDO($connect, USER, PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // 大会一覧を取得
    $tournaments = $pdo->query('SELECT tournament_id, tournament_name FROM tournament')->fetchAll(PDO::FETCH_ASSOC);

    // フォームから選ばれた値を取得
    $selected_tournament_id = isset($_POST['tournament_id']) ? (int)$_POST['tournament_id'] : null;
    $selected_round = isset($_POST['round']) ? (int)$_POST['round'] : null;

    // 勝敗判定が送信された場合
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['winner_id'], $_POST['loser_id'])) {
        $tournament_id = (int)$_POST['tournament_id'];
        $loser_id = (int)$_POST['loser_id'];

        try {
            // 負けたユーザーの loser カラムを更新
            $stmt = $pdo->prepare('
                UPDATE tournament_member 
                SET loser = 1 
                WHERE tournament_id = :tournament_id AND user_id = :loser_id
            ');
            $stmt->execute([':tournament_id' => $tournament_id, ':loser_id' => $loser_id]);

            echo "<p>勝敗が更新されました。</p>";
        } catch (PDOException $e) {
            echo "<p>エラー: " . htmlspecialchars($e->getMessage()) . "</p>";
        }
    }
    // 取消ボタンが押された場合の処理
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cancel_loser_id'])) {
        $tournament_id = (int)$_POST['tournament_id'];
        $loser_id = (int)$_POST['cancel_loser_id'];

        try {
            // 負けたユーザーの loser カラムを 0 に更新
            $stmt = $pdo->prepare('
                UPDATE tournament_member 
                SET loser = 0 
                WHERE tournament_id = :tournament_id AND user_id = :loser_id
            ');
            $stmt->execute([':tournament_id' => $tournament_id, ':loser_id' => $loser_id]);

            echo "<p>勝敗が取り消されました。</p>";
        } catch (PDOException $e) {
            echo "<p>エラー: " . htmlspecialchars($e->getMessage()) . "</p>";
        }
    }
    // 組情報を取得
    $results = [];
    if ($selected_tournament_id && $selected_round) {
        $stmt_kumi = $pdo->prepare('
            SELECT tk.tournament_id, tk.round, tk.user_id1, tk.user_id2, 
                   u1.user_name AS user1_name, u2.user_name AS user2_name,
                   tm1.loser AS loser1, tm2.loser AS loser2
            FROM tournament_kumi tk
            JOIN users u1 ON tk.user_id1 = u1.user_id
            JOIN users u2 ON tk.user_id2 = u2.user_id
            LEFT JOIN tournament_member tm1 ON tk.tournament_id = tm1.tournament_id AND tk.user_id1 = tm1.user_id
            LEFT JOIN tournament_member tm2 ON tk.tournament_id = tm2.tournament_id AND tk.user_id2 = tm2.user_id
            WHERE tk.tournament_id = :tournament_id 
              AND tk.round = :round
        ');
        $stmt_kumi->execute([':tournament_id' => $selected_tournament_id, ':round' => $selected_round]);
        $matches = $stmt_kumi->fetchAll(PDO::FETCH_ASSOC);

        // 画像データを取得
        $stmt_chat = $pdo->prepare('
            SELECT tc.user_id, tc.image_path
            FROM tournament_chat tc
            WHERE tc.tournament_id = :tournament_id 
              AND tc.round = :round 
              AND (tc.image_path IS NOT NULL AND TRIM(tc.image_path) != "")
        ');
        $stmt_chat->execute([':tournament_id' => $selected_tournament_id, ':round' => $selected_round]);
        $chat_images = $stmt_chat->fetchAll(PDO::FETCH_GROUP | PDO::FETCH_ASSOC);

        // 組に画像を紐付け
        foreach ($matches as $match) {
            $user1_images = $chat_images[$match['user_id1']] ?? [];
            $user2_images = $chat_images[$match['user_id2']] ?? [];
            $results[] = [
                'user1_id' => $match['user_id1'],
                'user2_id' => $match['user_id2'],
                'user1_name' => $match['user1_name'],
                'user2_name' => $match['user2_name'],
                'user1_images' => array_column($user1_images, 'image_path'),
                'user2_images' => array_column($user2_images, 'image_path'),
                'loser1' => $match['loser1'],
                'loser2' => $match['loser2'],
            ];
        }
    }
    
} catch (PDOException $e) {
    error_log("Database error: " . $e->getMessage());
    echo "データベースエラーが発生しました。管理者に連絡してください。";
} catch (Exception $e) {
    echo "エラー: " . htmlspecialchars($e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>大会とラウンド選択</title>
    <style>
        table { border-collapse: collapse; width: 100%; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: center; }
        th { background-color: #f2f2f2; }
        img { max-width: 400px; max-height: 400px; } /* 画像サイズ4倍 */
        button { padding: 10px 20px; font-size: 16px; border: none; cursor: pointer; }
        .win-btn { background-color: #4CAF50; color: white; }
        .lose-btn { background-color: #f44336; color: white; }
        .match-info { font-size: 1.2em; margin-bottom: 10px; }
        .match-container { display: flex; justify-content: space-between; margin: 10px 0; }
    </style>
</head>
<body>
    <h1>大会とラウンドの選択</h1>

    <!-- フィルタフォーム -->
    <form method="POST">
        <label for="tournament_id">大会:</label>
        <select name="tournament_id" id="tournament_id">
            <option value="">選択してください</option>
            <?php foreach ($tournaments as $tournament): ?>
                <option value="<?= htmlspecialchars($tournament['tournament_id']) ?>" <?= $selected_tournament_id == $tournament['tournament_id'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($tournament['tournament_name']) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label for="round">ラウンド:</label>
        <input type="number" name="round" id="round" value="<?= htmlspecialchars($selected_round) ?>">

        <button type="submit">表示</button>
    </form>

    <!-- 戻るボタン -->
    <button onclick="window.location.href='tournament.php'">戻る</button>

    <!-- 組み分け情報 -->
    <?php if (!empty($results)): ?>
        <h2>組み分け</h2>
        <table>
            <tr>
                <th>ユーザー1 (ID)</th>
                <th>ユーザー2 (ID)</th>
            </tr>
            <?php foreach ($results as $row): ?>
                <tr>
                    <td class="match-info">
                        <?= htmlspecialchars($row['user1_name']) ?> (<?= htmlspecialchars($row['user1_id']) ?>)
                        <?php if ($row['loser1'] == 1): ?>
                            <span style="color: red;">(敗北)</span>
                        <?php elseif ($row['loser2'] == 1): ?>
                            <span style="color: green;">(勝者)</span>
                        <?php endif; ?>
                    </td>
                    <td class="match-info">
                        <?= htmlspecialchars($row['user2_name']) ?> (<?= htmlspecialchars($row['user2_id']) ?>)
                        <?php if ($row['loser2'] == 1): ?>
                            <span style="color: red;">(敗北)</span>
                        <?php elseif ($row['loser1'] == 1): ?>
                            <span style="color: green;">(勝者)</span>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>

        <h2>結果</h2>
        <table>
            <tr>
                <th>ユーザー1 (ID)</th>
                <th>ユーザー1の画像</th>
                <th>ユーザー2 (ID)</th>
                <th>ユーザー2の画像</th>
                <th>勝敗</th>
                <th>取消</th>
            </tr>
            <?php foreach ($results as $row): ?>
                <tr>
                    <td><?= htmlspecialchars($row['user1_name']) ?> (<?= htmlspecialchars($row['user1_id']) ?>)</td>
                    <td>
                        <?php if (!empty($row['user1_images'])): ?>
                            <?php foreach ($row['user1_images'] as $image): ?>
                                <img src="win-loss-image/<?= htmlspecialchars($image) ?>" alt="画像">
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p>画像なし</p>
                        <?php endif; ?>
                    </td>
                    <td><?= htmlspecialchars($row['user2_name']) ?> (<?= htmlspecialchars($row['user2_id']) ?>)</td>
                    <td>
                        <?php if (!empty($row['user2_images'])): ?>
                            <?php foreach ($row['user2_images'] as $image): ?>
                                <img src="win-loss-image/<?= htmlspecialchars($image) ?>" alt="画像">
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p>画像なし</p>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if ($row['loser1'] == 1 && $row['loser2'] == 0): ?>
                            勝者: <?= htmlspecialchars($row['user2_name']) ?> (<?= htmlspecialchars($row['user2_id']) ?>)
                        <?php elseif ($row['loser1'] == 0 && $row['loser2'] == 1): ?>
                            勝者: <?= htmlspecialchars($row['user1_name']) ?> (<?= htmlspecialchars($row['user1_id']) ?>)
                        <?php else: ?>
                            <form method="POST" style="display: inline;">
                                <input type="hidden" name="tournament_id" value="<?= htmlspecialchars($selected_tournament_id) ?>">
                                <input type="hidden" name="round" value="<?= htmlspecialchars($selected_round) ?>">
                                <input type="hidden" name="winner_id" value="<?= htmlspecialchars($row['user1_id']) ?>">
                                <input type="hidden" name="loser_id" value="<?= htmlspecialchars($row['user2_id']) ?>">
                                <button type="submit" class="win-btn">ユーザー1 勝者決定</button>
                            </form>
                            <form method="POST" style="display: inline;">
                                <input type="hidden" name="tournament_id" value="<?= htmlspecialchars($selected_tournament_id) ?>">
                                <input type="hidden" name="round" value="<?= htmlspecialchars($selected_round) ?>">
                                <input type="hidden" name="winner_id" value="<?= htmlspecialchars($row['user2_id']) ?>">
                                <input type="hidden" name="loser_id" value="<?= htmlspecialchars($row['user1_id']) ?>">
                                <button type="submit" class="win-btn">ユーザー2 勝者決定</button>
                            </form>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if ($row['loser1'] == 1 || $row['loser2'] == 1): ?>
                            <form method="POST" style="display: inline;">
                                <input type="hidden" name="tournament_id" value="<?= htmlspecialchars($selected_tournament_id) ?>">
                                <input type="hidden" name="round" value="<?= htmlspecialchars($selected_round) ?>">
                                <input type="hidden" name="cancel_loser_id" value="<?= htmlspecialchars($row['loser1'] == 1 ? $row['user1_id'] : $row['user2_id']) ?>">
                                <button type="submit" class="win-btn" style="background-color: #f44336;">取消</button>
                            </form>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php endif; ?>
</body>
</html>
