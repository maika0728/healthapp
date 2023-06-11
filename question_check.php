
<?php
session_start();

// データベースへの接続情報を設定
$host = "localhost"; // ホスト名
$dbname = "healthapp"; // データベース名
$username = "kaima"; // ユーザー名
$password = "kt7281"; // パスワード

// セッションからユーザーIDとユーザー名を取得
$user_id = $_SESSION["user_id"];
$user_name = $_SESSION["user_name"];
$user_image = $_SESSION["image_name"];

// POST データの取得
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $question = $_POST["question"];

    // データベースに接続
    $dsn = "mysql:host=$host;dbname=$dbname;charset=utf8mb4";
    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ];

    try {
        $pdo = new PDO($dsn, $username, $password, $options);

        // 質問をデータベースに保存
        $stmt = $pdo->prepare("INSERT INTO questions (user_id, user_name, question,user_image) VALUES (?, ?, ?,?)");
        $stmt->execute([$user_id, $user_name, $question,$user_image]);

        // 質問の保存が成功した場合にメッセージを表示する
        $message = "質問が送信されました！";
    } catch (PDOException $e) {
        // データベースエラーが発生した場合にエラーメッセージを表示する
        $message = "質問の送信中にエラーが発生しました: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <h1>質問</h1>
    <p>ユーザーID: <?php echo $user_id; ?></p>
    <p>ユーザー名: <?php echo $user_name; ?></p>

    <?php if (isset($message)) { ?>
        <p><?php echo $message; ?></p>
    <?php } ?>

    <a href="./question_board.php">質問一覧</a>
    <a href="./question.php">まだ質問する</a>
</body>
</html>
