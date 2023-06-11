<?php
session_start();
var_dump($_SESSION["user_id"]);

$user_id = $_SESSION["user_id"];

// データベースへの接続情報を設定
$host = "localhost"; // ホスト名
$dbname = "healthapp"; // データベース名
$username = "kaima"; // ユーザー名
$password = "kt7281"; // パスワード

// データベースに接続
$dsn = "mysql:host=$host;dbname=$dbname;charset=utf8mb4";
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false,
];

try {
    $pdo = new PDO($dsn, $username, $password, $options);

    if ($_POST) {
        if (empty($_POST["email"])) {
            echo "emailが設定入力されていません";
        } elseif (empty($_POST["password"])) {
            echo "パスワードが設定されていません";
        } else {
            $stmt = $pdo->prepare("SELECT email, password FROM users WHERE id = :user_id");
            $stmt->bindParam(":user_id", $user_id);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            var_dump($result);
            if (($result["email"] == $_POST["email"]) &&
                (password_verify($_POST["password"], $result['password']))
            ) {
                header("Location: ./user_update.php");
                exit;
            }
        }
    }
} catch (PDOException $e) {
    echo "エラー";
}
?>

<form action="" method="post">
    <label for="email">email</label>
    <input type="email" name="email">
    <label for="password">password</label>
    <input type="password" name="password">
    <input type="submit" value="登録情報を変更する">
</form>