<?php

session_start();

// データベースへの接続
$host = 'localhost';
$dbname = 'healthapp'; // データベース名
$username = 'kaima';
$password = 'kt7281';

$answer_id = $_POST["answer_id"];
$user_id = $_SESSION["user_id"];
var_dump($user_id);


try {
    $dbh = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "データベースに接続しました。";
} catch (PDOException $e) {
    echo "データベースに接続できませんでした。エラー: " . $e->getMessage();
}

//登録されているか調べる
        $stmt = $dbh->prepare("SELECT COUNT(*) FROM likes WHERE user_id = :user_id AND answer_id = :answer_id");
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':answer_id', $answer_id);
        $stmt->execute();

        $count = $stmt->fetchColumn();

if($count = 0){
    $stmt = $dbh->prepare("INSERT INTO likes (answer_id,user_id) value (:answer_id,:user_id)");
    $stmt->bindParam(":answer_id", $answer_id);
    $stmt->bindParam(":user_id", $user_id);
    $stmt->execute();

    header("Location: ./question_board.php");
    exit;

}else{
    echo "すでにいいねがおされとる";
    echo "<a href='.question_board.php'>戻る</a>";
}




?>