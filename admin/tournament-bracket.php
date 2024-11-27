<?php
session_start();
require 'db-connect.php';

$pdo = new PDO($connect, USER, PASS);

// 大会リストを取得する関数
function getTournamentList($pdo) {
    $stmt = $pdo->query("SELECT tournament_id, tournament_name FROM tournament ORDER BY tournament_name ASC");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// 大会名を取得する関数
function getTournamentName($pdo, $tournament_id) {
    $stmt = $pdo->prepare("SELECT tournament_name FROM tournament WHERE tournament_id = ?");
    $stmt->execute([$tournament_id]);
    return $stmt->fetchColumn();
}

// 特定の回戦の組み分け情報を取得して表示する関数
function displayMatchList($pdo, $tournament_id, $round) {
    $stmt = $pdo->prepare("
        SELECT round, user_id1, user_id2
        FROM tournament_kumi
        WHERE tournament_id = ? AND round = ?
        ORDER BY round ASC
    ");
    $stmt->execute([$tournament_id, $round]);
    $matches = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (empty($matches)) {
        echo '<p>選択された回戦の組み分けはまだ行われていません。</p>';
        return;
    }

    echo '<table>';
    echo '<thead>';
    echo '<tr>';
    echo '<th>ラウンド</th>';
    echo '<th>対戦者1 (User ID)</th>';
    echo '<th>対戦者2 (User ID)</th>';
    echo '</tr>';
    echo '</thead>';
    echo '<tbody>';

    foreach ($matches as $match) {
        echo '<tr>';
        echo '<td>第' . htmlspecialchars($match['round'], ENT_QUOTES, 'UTF-8') . '回戦</td>';
        echo '<td>' . htmlspecialchars($match['user_id1'], ENT_QUOTES, 'UTF-8') . '</td>';
        echo '<td>' . htmlspecialchars($match['user_id2'], ENT_QUOTES, 'UTF-8') . '</td>';
        echo '</tr>';
    }

    echo '</tbody>';
    echo '</table>';
}

// URLパラメータまたはPOSTデータから値を取得
$tournament_id = $_POST['tournament_id'] ?? $_GET['tournament_id'] ?? null;
$round = $_POST['round'] ?? null;

if ($tournament_id) {
    $tournament_name = getTournamentName($pdo, $tournament_id);

    if (!$tournament_name) {
        $error_message = '無効な大会IDです。';
    }
} else {
    $tournament_name = null;
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>組み分け一覧</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        h1, h2 {
            text-align: center;
            color: #333;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table, th, td {
            border: 1px solid #ccc;
        }
        th, td {
            padding: 10px;
            text-align: center;
        }
        th {
            background-color: #f4f4f4;
        }
        p {
            text-align: center;
            font-size: 18px;
            color: #666;
        }
        .error {
            color: red;
            text-align: center;
        }
        form {
            text-align: center;
            margin: 20px 0;
        }
        select, button {
            font-size: 16px;
            padding: 5px 10px;
        }
    </style>
</head>
<body>
    <h1>組み分け一覧</h1>

    <?php if (isset($error_message)): ?>
        <p class="error"><?= htmlspecialchars($error_message, ENT_QUOTES, 'UTF-8'); ?></p>
    <?php endif; ?>

    <form method="post" action="tournament-bracket.php">
        <label for="tournament_id">大会を選択:</label>
        <select name="tournament_id" id="tournament_id" required>
            <option value="">-- 大会を選択 --</option>
            <?php
            $tournaments = getTournamentList($pdo);
            foreach ($tournaments as $tournament) {
                $selected = ($tournament_id == $tournament['tournament_id']) ? 'selected' : '';
                echo "<option value=\"{$tournament['tournament_id']}\" $selected>" . htmlspecialchars($tournament['tournament_name'], ENT_QUOTES, 'UTF-8') . "</option>";
            }
            ?>
        </select>
        <label for="round">回戦を選択:</label>
        <select name="round" id="round" required>
            <option value="">-- 回戦を選択 --</option>
            <?php for ($i = 1; $i <= 10; $i++): ?>
                <option value="<?= $i ?>" <?= ($round == $i) ? 'selected' : '' ?>>第<?= $i ?>回戦</option>
            <?php endfor; ?>
        </select>
        <button type="submit">表示</button>
    </form>

    <div style="text-align: center; margin-top: 20px;">
        <a href="tournament.php" style="
            display: inline-block;
            text-decoration: none;
            padding: 10px 20px;
            background-color: #007BFF;
            color: #fff;
            border-radius: 5px;
            font-size: 16px;
        ">戻る</a>
    </div>

    <?php if ($tournament_id && $round): ?>
        <h2><?= htmlspecialchars($tournament_name, ENT_QUOTES, 'UTF-8') ?> - 第<?= htmlspecialchars($round, ENT_QUOTES, 'UTF-8') ?>回戦</h2>
        <?php displayMatchList($pdo, $tournament_id, $round); ?>
    <?php endif; ?>
</body>
</html>
