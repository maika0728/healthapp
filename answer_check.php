<?php

session_start();
$user_image = $_SESSION["image_name"];

// データベースへの接続
$host = 'localhost';
$dbname = 'healthapp'; // データベース名
$username = 'kaima';
$password = 'kt7281';

var_dump($_POST);



try {
    $dbh = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "データベースに接続しました。";
} catch (PDOException $e) {
    echo "データベースに接続できませんでした。エラー: " . $e->getMessage();
}

// フォームから送信されたデータの取得
$question_id = $_POST['question_id'];
var_dump($question_id);
$answer = $_POST['answer'];
$user_name = $_SESSION['user_name'];
$user_id = $_SESSION['user_id'];



// ユーザーの登録
try {
    $stmt = $dbh->prepare("INSERT INTO answers (question_id, user_name, answer,user_image,user_id) VALUES (:question_id, :user_name, :answer,:user_image,:user_id)");
    $stmt->bindParam(':question_id', $question_id);
    $stmt->bindParam(':user_name', $user_name);
    $stmt->bindParam(':answer', $answer);
    $stmt->bindParam(":user_image",$user_image);
    $stmt->bindParam("user_id",$user_id);
    $stmt->execute();

    echo "新しい解答が登録されました。";
    echo "<a href='./question_board.php'>質問一覧へ</a>";
} catch (PDOException $e) {
    echo "新規登録中にエラーが発生しました。エラー: " . $e->getMessage();
}

// データベースへの接続を閉じる
$dbh = null;



?>