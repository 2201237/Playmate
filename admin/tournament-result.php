<?php
class Database {
    private $connect = 'mysql:host=mysql311.phy.lolipop.lan;dbname=LAA1516826-playmate;charset=utf8';
    private $USER = 'LAA1516826';
    private $PASS = 'joyman';
    private $conn;

    public function getConnection() {
        $this->conn = null;
        try {
            $this->conn = new PDO($this->connect, $this->USER, $this->PASS);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $e) {
            echo "接続エラー: " . $e->getMessage();
        }
        return $this->conn;
    }
}

class TournamentParticipant {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    // 全参加者を取得（大会名を含む）
    public function getAllParticipants() {
        try {
            $query = "SELECT 
                        u.user_id,
                        u.user_name,
                        t.tournament_id,
                        t.tournament_name,
                        tm.tournament_member_id
                    FROM 
                        users u
                        INNER JOIN tournament_member tm ON u.user_id = tm.user_id
                        INNER JOIN tournament t ON tm.tournament_id = t.tournament_id
                    ORDER BY 
                        t.tournament_id, u.user_id";

            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch(PDOException $e) {
            echo "エラー: " . $e->getMessage();
            return false;
        }
    }

    // 特定の大会の参加者を取得（大会名を含む）
    public function getParticipantsByTournament($tournamentId) {
        try {
            $query = "SELECT 
                        u.user_id,
                        u.user_name,
                        t.tournament_id,
                        t.tournament_name,
                        tm.tournament_member_id
                    FROM 
                        users u
                        INNER JOIN tournament_member tm ON u.user_id = tm.user_id
                        INNER JOIN tournament t ON tm.tournament_id = t.tournament_id
                    WHERE
                        tm.tournament_id = :tournament_id
                    ORDER BY 
                        u.user_id";

            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":tournament_id", $tournamentId);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch(PDOException $e) {
            echo "エラー: " . $e->getMessage();
            return false;
        }
    }

    // 全大会のリストを取得
    public function getAllTournaments() {
        try {
            $query = "SELECT 
                        tournament_id,
                        tournament_name
                    FROM 
                        tournament
                    ORDER BY 
                        tournament_id";

            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch(PDOException $e) {
            echo "エラー: " . $e->getMessage();
            return false;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="css/tournament-member.css">
    <title>大会参加者一覧</title>
</head>
<body>
<a href="tournament-view.php" class="back">←戻る</a>
<a href="login.php" class="logout">ログアウト</a>
    <div class="container">
        <?php
        // データベース接続
        $database = new Database();
        $db = $database->getConnection();

        // TournamentParticipantクラスのインスタンス化
        $tournamentParticipant = new TournamentParticipant($db);

        // 大会選択フォーム
        $tournaments = $tournamentParticipant->getAllTournaments();
        if ($tournaments) {
            ?>
            <form method="GET">
                <select name="tournament_id" class="tournament-select">
                    <option value="">全ての大会</option>
                    <?php foreach ($tournaments as $tournament) { ?>
                        <option value="<?php echo htmlspecialchars($tournament['tournament_id']); ?>"
                                <?php echo (isset($_GET['tournament_id']) && $_GET['tournament_id'] == $tournament['tournament_id']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($tournament['tournament_name']); ?>
                        </option>
                    <?php } ?>
                </select>
                <input type="submit" value="表示">
            </form>
            <?php
        }

        // 選択された大会の参加者または全参加者を表示
        if (isset($_GET['tournament_id']) && !empty($_GET['tournament_id'])) {
            $participants = $tournamentParticipant->getParticipantsByTournament($_GET['tournament_id']);
            echo "<h2>選択された大会の参加者一覧</h2>";
        } else {
            $participants = $tournamentParticipant->getAllParticipants();
            echo "<h2>大会戦績</h2>";
        }

        if ($participants) {
            ?>
            <table>
                <tr>
                    <th>ユーザーID</th>
                    <th>ユーザー名</th>
                    <th>ゲーム名</th>
                    <th>大会戦績一覧</th>
                </tr>
                <?php foreach ($participants as $participant) { ?>
                    <tr>
                        <td><?php echo htmlspecialchars($participant['user_id']); ?></td>
                        <td><?php echo htmlspecialchars($participant['user_name']); ?></td>
                        <td><?php echo htmlspecialchars($participant['title']); ?></td>
                        <td><?php echo htmlspecialchars($participant['tournament_id']); ?></td>
                    </tr>
                <?php } ?>
            </table>
        <?php } else { ?>
            <p>参加者が見つかりませんでした。</p>
        <?php } ?>
    </div>
</body>
</html>