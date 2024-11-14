<?php
class Database {
    private $connect = 'mysql:host=mysql311.phy.lolipop.lan;dbname=LAA1516826-playmate;charset=utf8';
    private $USER = 'LAA1516826';
    private $PASS = 'joyman';
    private $conn;

    // データベース接続を取得
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

    // コンストラクタ
    public function __construct($db) {
        $this->conn = $db;
    }

    // 全参加者を取得
    public function getAllParticipants() {
        try {
            $query = "SELECT 
                        u.user_id,
                        u.user_name,
                        tm.tournament_id,
                        tm.tournament_member_id
                    FROM 
                        users u
                        INNER JOIN tournament_member tm ON u.user_id = tm.user_id
                    ORDER BY 
                        tm.tournament_id, u.user_id";

            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch(PDOException $e) {
            echo "エラー: " . $e->getMessage();
            return false;
        }
    }

    // 特定の大会の参加者を取得
    public function getParticipantsByTournament($tournamentId) {
        try {
            $query = "SELECT 
                        u.user_id,
                        u.user_name,
                        tm.tournament_id,
                        tm.tournament_member_id
                    FROM 
                        users u
                        INNER JOIN tournament_member tm ON u.user_id = tm.user_id
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
}

// 使用例を含むHTMLページ
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>大会参加者一覧</title>
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
            margin: 20px 0;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }
        h2 {
            color: #333;
            margin-top: 30px;
        }
    </style>
</head>
<body>
    <div class="container">
        <?php
        // データベース接続
        $database = new Database();
        $db = $database->getConnection();

        // TournamentParticipantクラスのインスタンス化
        $tournamentParticipant = new TournamentParticipant($db);

        // 全参加者の取得と表示
        echo "<h2>全大会参加者一覧</h2>";
        $allParticipants = $tournamentParticipant->getAllParticipants();
        if ($allParticipants) {
            ?>
            <table>
                <tr>
                    <th>ユーザーID</th>
                    <th>ユーザー名</th>
                    <th>大会ID</th>
                    <th>大会メンバーID</th>
                </tr>
                <?php foreach ($allParticipants as $participant) { ?>
                    <tr>
                        <td><?php echo htmlspecialchars($participant['user_id']); ?></td>
                        <td><?php echo htmlspecialchars($participant['user_name']); ?></td>
                        <td><?php echo htmlspecialchars($participant['tournament_id']); ?></td>
                        <td><?php echo htmlspecialchars($participant['tournament_member_id']); ?></td>
                    </tr>
                <?php } ?>
            </table>
            <?php
        }

        // 特定の大会（例：大会ID=7）の参加者を表示
        echo "<h2>大会ID:7の参加者一覧</h2>";
        $tournament7Participants = $tournamentParticipant->getParticipantsByTournament(7);
        if ($tournament7Participants) {
            ?>
            <table>
                <tr>
                    <th>ユーザーID</th>
                    <th>ユーザー名</th>
                    <th>大会ID</th>
                    <th>大会メンバーID</th>
                </tr>
                <?php foreach ($tournament7Participants as $participant) { ?>
                    <tr>
                        <td><?php echo htmlspecialchars($participant['user_id']); ?></td>
                        <td><?php echo htmlspecialchars($participant['user_name']); ?></td>
                        <td><?php echo htmlspecialchars($participant['tournament_id']); ?></td>
                        <td><?php echo htmlspecialchars($participant['tournament_member_id']); ?></td>
                    </tr>
                <?php } ?>
            </table>
        <?php } ?>
    </div>
</body>
</html>