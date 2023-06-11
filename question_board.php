<?php
session_start();
echo $_SESSION["user_name"] . "さんようこそ<br>";
if (empty($_SESSION)) {
    header("Location: ./login_error.php");
}
echo '<a href="./menu.php" class="btn btn-info">menu</a>';

include(dirname(__FILE__) . '/header.php');







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



?>

<style>
    #container {
        width: 70%;
        margin: auto;
        padding: 1.0em;
    }
</style>

<div id="container">

    <form action="" method="">
        <input type="text" name="search">
        <input type="submit" value="search">
    </form>


    <?php


    try {
        $pdo = new PDO($dsn, $username, $password, $options);

        // レコードを取得
        $stmt = $pdo->query("SELECT * FROM questions");
        $records = $stmt->fetchAll();

        $stmt_answer = $pdo->query("SELECT * FROM answers");
        $answer_records = $stmt_answer->fetchAll();




        foreach ($records as $record) {
            $imageName = $record["user_image"];
            $imagePath = "./images/" . $imageName;

            echo "<div id='question_container' style='background: #ECF9FF'>";
            echo "<h1>質問" . $record["id"] . "</h1>";
            echo "<div>";
            echo "<div id='question_and_profile'>";
            echo "<div id='profile'>";
            echo "<img src='$imagePath' alt='$imageName' width='80px' height='80px'><br>";
            echo "<p>名前:" . $record["user_name"] . "</p>";
            echo "</div>";
            echo "<p id='question' style='font-weight: bold;'>" . $record["question"] . "</p>";
            echo "</div>";
            echo "</div>";
            echo "<form action='answer_check.php' method='post'>";
            echo "<input type='text' name='answer' required>";
            echo "<input type='hidden' name='question_id' value='" . $record["id"] . "'>";
            echo "<input type='submit' value='質問番号" . $record["id"] . "に回答する'></form><br>";

            echo "<div id='answer_space' style='background: #FFFBEB'>";
            // echo "<h1 style='font-size: 1.1em'>回答一覧</h1>";
            foreach ($answer_records as $answer_record) {
                $imageName = $answer_record["user_image"];
                $imagePath = "./images/" . $imageName;
                if ($answer_record["question_id"] == $record["id"]) {
                    // echo "回答ID:".$answer_record["id"]."<br>";
                    echo "<div id='an_answer_container'>";
                    echo "<div id='profile'>";
                    echo "<img src='$imagePath' alt='$imageName' width='80px' height='80px'><br>";
                    echo "<p>名前:" . $answer_record["user_name"] . "</p>";
                    echo "</div>";
                    echo "<p id='answer'>" . $answer_record["answer"] . "</p>";
                    $stmt = $pdo->prepare("SELECT COUNT(*) FROM likes WHERE answer_id = :answer_id");
                    $stmt->bindParam("answer_id", $answer_record["id"]);
                    $stmt->execute();
                    $count = $stmt->fetchColumn();
                    echo "<form action='like.php' method='post'>";
                    echo "<input type='hidden' name='answer_id' value='" . $answer_record['id'] . "'>";
                    echo "<button type='submit'>いいね" . $count . "</button></form>";
                    echo "</div>";
                    echo "<br><br><br><br>";
                }
            }
        }
        echo "</div>";
    } catch (PDOException $e) {
        echo "データベースエラー: " . $e->getMessage();
    }
    ?>

</div>



</html>