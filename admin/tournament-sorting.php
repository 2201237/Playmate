<?php
session_start();
require 'db-connect.php';

$pdo = new PDO($connect, USER, PASS);

// 大会リストを取得する関数
function getTournamentList($pdo) {
    $stmt = $pdo->query("SELECT tournament_id, tournament_name FROM tournament ORDER BY tournament_name ASC");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// 指定された大会・回戦の組み分けが存在するか確認する関数
function isRoundRegistered($pdo, $tournament_id, $round) {
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM tournament_kumi WHERE tournament_id = ? AND round = ?");
    $stmt->execute([$tournament_id, $round]);
    return $stmt->fetchColumn() > 0;
}

// 組み分け処理の関数
function createSorting($pdo, $tournament_id, $round) {
    // 参加者を取得（loser=1のプレイヤーを除外）
    $stmt = $pdo->prepare("
        SELECT user_id 
        FROM tournament_member 
        WHERE tournament_id = ? AND (loser IS NULL OR loser = 0)
    ");
    $stmt->execute([$tournament_id]);
    $participants = $stmt->fetchAll(PDO::FETCH_COLUMN);

    if (count($participants) < 2) {
        return '参加者が2人未満のため、組み分けができません。';
    }

    shuffle($participants);

    $stmt = $pdo->prepare("
        INSERT INTO tournament_kumi (tournament_id, round, user_id1, user_id2) 
        VALUES (?, ?, ?, ?)
    ");

    for ($i = 0; $i < count($participants); $i += 2) {
        $user1 = $participants[$i];
        $user2 = $participants[$i + 1] ?? null;

        $stmt->execute([$tournament_id, $round, $user1, $user2]);
    }

    return '組み分けが完了しました。';
}

// POSTデータの処理
$message = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tournament_id = $_POST['tournament_id'] ?? null;
    $round = $_POST['round'] ?? null;

    if ($tournament_id && $round) {
        if (isRoundRegistered($pdo, $tournament_id, $round)) {
            $message = '既にこの大会のこの回戦の組み分けが登録されています。';
        } else {
            $message = createSorting($pdo, $tournament_id, $round);
        }
    } else {
        $message = '大会と回戦を選択してください。';
    }
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>大会組み分け</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        h1, h2 {
            text-align: center;
            color: #333;
        }
        form {
            text-align: center;
            margin: 20px 0;
        }
        select, button {
            font-size: 16px;
            padding: 5px 10px;
            margin: 5px;
        }
        .message {
            text-align: center;
            font-size: 18px;
            color: #666;
        }
        .error {
            color: red;
        }
    </style>
</head>
<body>
    <h1>大会組み分け</h1>

    <?php if ($message): ?>
        <p class="message <?= strpos($message, '既に') !== false ? 'error' : ''; ?>">
            <?= htmlspecialchars($message, ENT_QUOTES, 'UTF-8'); ?>
        </p>
    <?php endif; ?>

    <form method="post" action="tournament-sorting.php">
        <label for="tournament_id">大会を選択:</label>
        <select name="tournament_id" id="tournament_id" required>
            <option value="">-- 大会を選択 --</option>
            <?php
            $tournaments = getTournamentList($pdo);
            foreach ($tournaments as $tournament) {
                $selected = ($_POST['tournament_id'] ?? '') == $tournament['tournament_id'] ? 'selected' : '';
                echo "<option value=\"{$tournament['tournament_id']}\" $selected>" . htmlspecialchars($tournament['tournament_name'], ENT_QUOTES, 'UTF-8') . "</option>";
            }
            ?>
        </select>
        <label for="round">回戦を選択:</label>
        <select name="round" id="round" required>
            <option value="">-- 回戦を選択 --</option>
            <?php for ($i = 1; $i <= 10; $i++): ?>
                <option value="<?= $i ?>" <?= ($_POST['round'] ?? '') == $i ? 'selected' : '' ?>>第<?= $i ?>回戦</option>
            <?php endfor; ?>
        </select>
        <button type="submit" <?= isset($_POST['tournament_id'], $_POST['round']) && isRoundRegistered($pdo, $_POST['tournament_id'], $_POST['round']) ? 'disabled' : ''; ?>>
            組み分けを実行
        </button>
    </form>
</body>
</html>
