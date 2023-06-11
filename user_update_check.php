<?php
session_start();
var_dump($_SESSION["user_id"]);

$user_id = $_SESSION["user_id"];

// データベースへの接続情報を設定
$host = "localhost"; // ホスト名
$dbname = "healthapp"; // データベース名
$username = "kaima"; // ユーザー名
$password = "kt7281"; // パスワード

try {
    $dbh = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "データベースに接続しました。";
} catch (PDOException $e) {
    echo "データベースに接続できませんでした。エラー: " . $e->getMessage();
}

// フォームから送信されたデータの取得
$name = $_POST['name'];
$email = $_POST['email'];
$password = $_POST['password'];

// パスワードのハッシュ化
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// ユーザーの登録
if (empty($_POST["name"])) {
    echo "名前が入力されていません。";
    echo "<a href='./user_update.php'>戻る</a>";
} elseif (empty($_POST["email"])) {
    echo "emailが入力されていません。";
    echo "<a href='./user_update.php'>戻る</a>";
} elseif (empty($_POST["password"])) {
    echo "パスワードが入力されていません";
    echo "<a href='./user_update.php'>戻る</a>";
} elseif ($_POST["password"] != $_POST["password2"]) {
    echo "入力されたパスワードが不一致です";
    echo "<a href='./user_update.php'>戻る</a>";
} else {

    try {
        $imageName = $_FILES["image"]["name"];
        $imageTmp = $_FILES["image"]["tmp_name"];

        $stmt = $dbh->prepare("UPDATE users SET name = :name, email = :email, password = :password, image_name = :image_name WHERE id = :id");
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $hashed_password);
        $stmt->bindParam(':image_name', $imageName);
        $stmt->bindParam(":id", $_SESSION["user_id"]);



        if ($stmt->execute()) {
            // ファイルをサーバーに移動
            move_uploaded_file($imageTmp, "./images/" . $imageName);
            echo "画像が正常にアップロードされました。";
        } else {
            echo "エラー: ユーザー情報が更新されました。";
        }

        echo "ユーザー情報が更新されました。";
        echo "<a href='./menu.php'>menu</a>";
    } catch (PDOException $e) {
        echo "新規登録中にエラーが発生しました。エラー: " . $e->getMessage();
    }
}
