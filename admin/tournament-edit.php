<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/tournament-edit.css">
    <title>大会管理（更新・削除）</title>
</head>
<body>
    <div class="nav-links">
        <a href="tournament.php" class="back">←戻る</a>
        <a href="login.php" class="logout">ログアウト</a>
    </div>
    
    <div class="container">
        <h2>大会管理（更新・削除）</h2>
        <?php
        // データベース接続情報
        $connect = 'mysql:host=mysql311.phy.lolipop.lan;dbname=LAA1516826-playmate;charset=utf8';
        $USER = 'LAA1516826';
        $PASS = 'joyman';
        
        try {
            $pdo = new PDO($connect, $USER, $PASS);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            // 更新処理
            if (isset($_POST['update']) && isset($_POST['tournament_id'])) {
                $update_id = $_POST['tournament_id'];
                $new_name = $_POST['tournament_name'];
                
                $stmt = $pdo->prepare("UPDATE tournament SET tournament_name = ? WHERE tournament_id = ?");
                if ($stmt->execute([$new_name, $update_id])) {
                    echo '<div class="message success">大会情報が更新されました。</div>';
                } else {
                    echo '<div class="message error">更新中にエラーが発生しました。</div>';
                }
            }
            
            // 削除処理
            if (isset($_POST['delete']) && isset($_POST['tournament_id'])) {
                $delete_id = $_POST['tournament_id'];
                
                // トランザクション開始
                $pdo->beginTransaction();
                
                try {
                    // 関連する参加者データを削除
                    $stmt = $pdo->prepare("DELETE FROM tournament_member WHERE tournament_id = ?");
                    $stmt->execute([$delete_id]);
                    
                    // 大会データを削除
                    $stmt = $pdo->prepare("DELETE FROM tournament WHERE tournament_id = ?");
                    $stmt->execute([$delete_id]);
                    
                    $pdo->commit();
                    echo '<div class="message success">大会が正常に削除されました。</div>';
                } catch (Exception $e) {
                    $pdo->rollBack();
                    echo '<div class="message error">削除中にエラーが発生しました。</div>';
                }
            }
            
            // 大会一覧の表示
            $sql = "SELECT t.tournament_id, t.tournament_name, 
                          (SELECT COUNT(*) FROM tournament_member tm WHERE tm.tournament_id = t.tournament_id) as participant_count 
                   FROM tournament t";
            $stmt = $pdo->query($sql);
            
// データベース表示部分のコードを以下のように変更します
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    echo '<div class="tournament-item">';
    echo '<div class="form-group">';
    echo '<form method="POST" class="tournament-form">';
    
    // 大会名入力フィールド
    echo '<div class="input-group">';
    echo '<label>大会名:</label>';
    echo '<input type="text" name="tournament_name" value="' . htmlspecialchars($row['tournament_name'], ENT_QUOTES, 'UTF-8') . '" required>';
    echo '</div>';
    
    // ルール入力フィールド
    echo '<div class="input-group">';
    echo '<label>ルール:</label>';
    echo '<textarea name="rule" rows="4" required>' . htmlspecialchars('[ルール]' . $row['rule'], ENT_QUOTES, 'UTF-8') . '</textarea>';

    echo '</div>';
    
    echo '<p>参加者数: ' . $row['participant_count'] . '人</p>';
    echo '<input type="hidden" name="tournament_id" value="' . $row['tournament_id'] . '">';
    
    echo '<div class="button-group">';
    echo '<button type="submit" name="update" class="btn btn-update">更新</button>';
    echo '<button type="submit" name="delete" class="btn btn-delete" onclick="return confirm(\'本当にこの大会を削除しますか？\');">削除</button>';
    echo '</div>';
    
    echo '</form>';
    echo '</div>';
    echo '</div>';
}

// 更新処理の部分も以下のように変更します
if (isset($_POST['update']) && isset($_POST['tournament_id'])) {
    $update_id = $_POST['tournament_id'];
    $new_name = $_POST['tournament_name'];
    $new_rules = $_POST['rule'];
    
    $stmt = $pdo->prepare("UPDATE tournament SET tournament_name = ?, rule = ? WHERE tournament_id = ?");
    if ($stmt->execute([$new_name, $new_rules, $update_id])) {
        echo '<div class="message success">大会情報が更新されました。</div>';
    } else {
        echo '<div class="message error">更新中にエラーが発生しました。</div>';
    }
}

// SQLクエリも rules カラムを含むように更新
$sql = "SELECT t.tournament_id, t.tournament_name, t.rule,
              (SELECT COUNT(*) FROM tournament_member tm WHERE tm.tournament_id = t.tournament_id) as participant_count 
       FROM tournament t";
            
        } catch (PDOException $e) {
            echo '<div class="message error">データベース接続に失敗しました: ' . $e->getMessage() . '</div>';
        }
        ?>
    </div>

    <script>
        // フォーム送信後の再読み込み時にメッセージを自動的に消す
        window.onload = function() {
            setTimeout(function() {
                var messages = document.getElementsByClassName('message');
                for (var i = 0; i < messages.length; i++) {
                    messages[i].style.display = 'none';
                }
            }, 3000);
        };
    </script>
</body>
</html>