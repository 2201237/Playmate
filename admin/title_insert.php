<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>新規ゲームタイトル</title>
    <link rel="stylesheet" href="css/title_insert.css">
</head>
<body>
    <div class="container">
        <a href="previous_page.php" class="back">← 戻る</a>
        <h1>新規ゲームタイトル <a href="logout.php" class="logout">ログアウト</a></h1>

        <form action="upload_game.php" method="post" enctype="multipart/form-data">
            <!-- ゲームタイトル入力 -->
            <div class="form-group">
                <label for="game_title">ゲームタイトル</label>
                <input type="text" id="game_title" name="game_title" required>
            </div>

            <!-- ゲームアイコンアップロード -->
            <div class="form-group">
                <label for="game_icon">ゲームアイコン</label>
                <input type="file" id="game_icon" name="game_icon" accept="image/*" onchange="previewIcon()" required>
            </div>

            <!-- アイコンサンプル -->
            <div class="form-group">
                <label>アイコンサンプル</label>
                <div class="icon-preview">
                    <img id="icon_sample" src="placeholder.png" alt="アイコンサンプル">
                </div>
            </div>

            <!-- 完了ボタン -->
            <button type="submit" class="submit-button">完了</button>
        </form>
    </div>

    <script>
        // アイコンのプレビューを表示する関数
        function previewIcon() {
            const fileInput = document.getElementById('game_icon');
            const preview = document.getElementById('icon_sample');
            const file = fileInput.files[0];

            if (file) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    preview.src = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        }
    </script>
</body>
</html>