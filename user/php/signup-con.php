<?php
session_start();
require 'db-connect.php';

// 入力データのサニタイズ
$user_name = htmlspecialchars($_POST['name']);
$user_pass = htmlspecialchars($_POST['password']);
$user_mail = htmlspecialchars($_POST['email']);

try {
    // データベース接続
    $pdo = new PDO($connect, USER, PASS);

    // メールアドレスで既存のユーザーを検索（BAN状態も含めて確認）
    $sql = $pdo->prepare('SELECT * FROM users WHERE user_id = ?');
    $sql->execute([$user_id]);
    $user = $sql->fetch();
    $bansql = $pdo->prepare('SELECT * FROM ban WHERE user_id = ?');
    $bansql->execute([$user_id]);
    $ban = $bansql->fetch();

    // ユーザーが既に存在し、かつBANされているか確認
    if ($user == $ban) {
        // BANされている場合の処理
        echo "<h1>このアカウントはBANされています。</h1>";

        echo '<script type="text/javascript">
                setTimeout(function() {
                    window.location.href = "login-input.php";
                }, 3000);
              </script>';

        echo "<p>3秒後にログインページに自動的に遷移します...</p>";
    } else {
        // ユーザーが存在せず、BANされていない場合、新規登録を行う
        $sql = $pdo->prepare('INSERT INTO users (user_name, user_pass, user_mail) VALUES (?, ?, ?)');
        $sql->execute([$user_name, password_hash($user_pass, PASSWORD_DEFAULT), $user_mail]);

        echo "<h1>登録が完了しました</h1>";
        // 3秒後にログインページに自動的に遷移
        echo '<script type="text/javascript">
                setTimeout(function() {
                    window.location.href = "home.php";
                }, 3000);
              </script>';
        echo "<p>3秒後にホームページに自動的に遷移します...</p>";
    }
} catch (PDOException $e) {
    echo "エラーが発生しました: " . $e->getMessage();
}
?>