<?php

// データベースへの接続
$host = 'localhost';
$dbname = 'healthapp'; // データベース名
$username = 'kaima';
$password = 'kt7281';

// var_dump($_POST);

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
if(empty($_POST["name"])){
    echo "名前が入力されていません。";
    echo "<a href='./sign_up.php'>戻る</a>";
}elseif(empty($_POST["email"])){
    echo "emailが入力されていません。";
    echo "<a href='./sign_up.php'>戻る</a>";
}elseif(empty($_POST["password"])){
    echo "パスワードが入力されていません";
    echo "<a href='./sign_up.php'>戻る</a>";
}elseif($_POST["password"] != $_POST["password2"]){
    echo "入力されたパスワードが不一致です";
    echo "<a href='./sign_up.php'>戻る</a>";
}else{

    try {
    $imageName = $_FILES["image"]["name"];
    $imageTmp = $_FILES["image"]["tmp_name"];

    $stmt = $dbh->prepare("INSERT INTO users (name, email, password, image_name) VALUES (:name, :email, :password, :image_name)");
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':password', $hashed_password);
    $stmt->bindParam(':image_name', $imageName);

    if ($stmt->execute()) {
        // ファイルをサーバーに移動
        move_uploaded_file($imageTmp, "./images/" . $imageName);
        echo "画像が正常にアップロードされました。";
    } else {
        echo "エラー: ユーザー登録に失敗しました。";
    }

    echo "新しいユーザーが登録されました。";
    echo "<a href='./login.php'>login</a>";
} catch (PDOException $e) {
    echo "新規登録中にエラーが発生しました。エラー: " . $e->getMessage();
}
}

?>




