<?php
session_start();

// データベース接続などの設定
$host = 'localhost';
$dbname = 'healthapp'; // データベース名
$username = 'kaima';
$password = 'kt7281';

try {
    $dbh = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // フォームから送信されたログイン情報の取得
    $email = $_POST['email'];
    $password = $_POST['password'];

    // ログイン情報のチェック
    $stmt = $dbh->prepare("SELECT * FROM users WHERE email = :email");
    $stmt->bindParam(':email', $email);
    $stmt->execute();

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        // ログイン成功時の処理
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['email'] = $user['email'];
        $_SESSION['user_name'] = $user['name'];
        $_SESSION['image_name'] = $user['image_name'];

        // ログイン成功後のリダイレクトなどの処理
        header('Location: ./menu.php');
        exit();
    } else {
        // ログイン失敗時の処理
        echo "ログインに失敗しました";
    }
} catch (PDOException $e) {
    echo "データベースに接続できませんでした: " . $e->getMessage();
    die();
}
?>
