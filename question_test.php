<?php
session_start();
echo $_SESSION["user_name"] . "さんようこそ<br>";
if (empty($_SESSION)) {
    header("Location: ./login_error.php");
}

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



<form action="" method="get">
    <input type="text" name="search">
    <input type="submit" value="search">
</form>
<div style="margin-bottom: 1em;">
    <a href="./question_test.php">検索をやめる</a>
</div>


<?php



if (isset($_GET["search"])) {
    echo $_GET["search"];
    $search = $_GET["search"];
    $pdo = new PDO($dsn, $username, $password, $options);

    // プリペアドステートメントを作成
    $stmt = $pdo->prepare("SELECT * FROM questions WHERE question LIKE :searchKeyword");
    $searchKeyword = '%' . $search . '%';
    $stmt->bindParam(':searchKeyword', $searchKeyword);

    // プリペアドステートメントを実行
    $stmt->execute();

    // 結果を取得
    $records = $stmt->fetchAll();

    $already_counteds = [];  //すでに検索されているか否かを受け入れるための箱。

    // 結果を表示
    foreach ($records as $record) {
        // レコードの処理
        $imageName = $record["user_image"];
        $imagePath = "./images/" . $imageName;

        $already_counteds[] = $record["id"]; //questionのすでに登録されているidを配列としていれる
        echo "<div id='question_container' style='background: #ECF9FF'>";
        echo "<h1>質問" . $record["id"] . "</h1>";
        echo "<div>";
        echo "<div id='question_and_profile'>";
        echo "<div id='profile'>";
        echo "<img src='$imagePath' alt='$imageName' width='80px' height='80px'><br>";
        echo "<p>名前:" . $record["user_name"] . "</p>";
        echo "</div>";
        echo "<p id='question' style='font-weight: bold;'>" . highlightKeyword($record["question"], $search) . "</p>";
        echo "</div>";
        echo "</div>";
        echo "<form action='answer_check.php' method='post'>";
        echo "<input type='text' name='answer' required>";
        echo "<input type='hidden' name='question_id' value='" . $record["id"] . "'>";
        echo "<input type='submit' value='質問番号" . $record["id"] . "に回答する'></form><br>";

        echo "<div id='answer_space' style='background: #FFFBEB'>";
        // echo "<h1 style='font-size: 1.1em'>回答一覧</h1>";

        $stmt_answer = $pdo->prepare("SELECT * FROM answers WHERE question_id = :questionId");
        $stmt_answer->bindValue(':questionId', $record["id"]);
        $stmt_answer->execute();
        $answer_records = $stmt_answer->fetchAll();

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
                echo "<p id='question'>" . highlightKeyword($answer_record["answer"], $search) . "</p>";
                echo "</div>";
                echo "<br><br><br><br>";
            }
        }
    }




    // answerに検索用語がある場合
    // プリペアドステートメントを作成
    $stmt = $pdo->prepare("SELECT * FROM answers WHERE answer LIKE :searchKeyword");
    $searchKeyword = '%' . $search . '%';
    $stmt->bindValue(':searchKeyword', $searchKeyword);

    // プリペアドステートメントを実行
    $stmt->execute();

    // 結果を取得
    $answer_records = $stmt->fetchAll();

    foreach ($answer_records as $answer_record) {

        //すでに質問のテーブルで検索をかけた際に、配列でカウントされているものを順々に数え、それがanswer[id]と一致するかどうか
        foreach ($already_counteds as $already_counted) {
            if ($answer_record["question_id"] != $already_counted) {


                $stmt = $pdo->prepare("SELECT id, user_name, question, user_image FROM questions WHERE id = :id");
                $stmt->bindValue(':id', $answer_record["question_id"]);
                $stmt->execute();

                $record = $stmt->fetch(PDO::FETCH_ASSOC);

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
                echo "<p id='question' style='font-weight: bold;'>" . highlightKeyword($record["question"], $search) . "</p>";
                echo "</div>";
                echo "</div>";
                echo "<form action='answer_check.php' method='post'>";
                echo "<input type='text' name='answer' required>";
                echo "<input type='hidden' name='question_id' value='" . $record["id"] . "'>";
                echo "<input type='submit' value='質問番号" . $record["id"] . "に回答する'></form><br>";

                echo "<div id='answer_space' style='background: #FFFBEB'>";

                // ここからanswer
                $imageName = $answer_record["user_image"];
                $imagePath = "./images/" . $imageName;

                echo "<div id='an_answer_container'>";
                echo "<div id='profile'>";
                echo "<img src='$imagePath' alt='$imageName' width='80px' height='80px'><br>";
                echo "<p>名前:" . $answer_record["user_name"] . "</p>";
                echo "</div>";
                echo "<p id='answer'>" . highlightKeyword($answer_record["answer"], $search) . "</p>";
                echo "</div>";
                echo "<br><br><br><br>";
            }
        }
    }
} else {
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
            echo "<h1 style='font-size:1.5em;'>質問" . $record["id"] . "</h1>";
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
                    echo "<p id='question'>" . $answer_record["answer"] . "</p>";
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
}

// 検索キーワードを太字にハイライトする関数
function highlightKeyword($text, $keyword)
{
    $highlightedText = preg_replace("/($keyword)/i", "<span style='font-weight: bold; color: red;'>$1</span>", $text);
    return $highlightedText;
}


// $key = "messi";
// $sentence = "messi comeback to barcelona.";
// $change = preg_replace("/($key)/i","<span style='color: red;'>$1</span>",$sentence);
// echo $change;


?>

</body>

</html>