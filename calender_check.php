<?php
session_start();

// データベースへの接続
$host = 'localhost';
$dbname = 'healthapp'; // データベース名
$username = 'kaima';
$password = 'kt7281';

var_dump($_POST);

$user_id = $_SESSION["user_id"];

try {
    $dbh = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "データベースに接続しました。";
} catch (PDOException $e) {
    echo "データベースに接続できませんでした。エラー: " . $e->getMessage();
}

// フォームから送信されたデータの取得
    $year = $_POST['year'];
    $month = $_POST['month'];
    $day = $_POST['day'];
    $date = $year . "-" . $month ."-". $day;
    echo($date);
    $condition = (int)$_POST['condition'];
    $text = $_POST['text'];

    try{

        $stmt = $dbh->prepare("SELECT COUNT(*) FROM calendar_data WHERE user_id = :user_id AND date = :date");
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':date', $date);
        $stmt->execute();

        $count = $stmt->fetchColumn();

        if ($count > 0) {
            // レコードが存在する場合の処理
            echo "すでにレコードが存在します。";
            // ここで必要な処理を追加してください。
            $stmt = $dbh->prepare("UPDATE calendar_data SET condition_value = :condition_value, text = :text WHERE user_id = :user_id AND date = :date");
            $stmt->bindParam(':condition_value', $condition);
            $stmt->bindParam(':text', $text);
            $stmt->bindParam(':user_id', $user_id);
            $stmt->bindParam(':date', $date);
            $stmt->execute();
            echo "データが更新されました";
            echo "<a href='./calender.php'>戻る</a>";
        } else {
            $stmt = $dbh->prepare("INSERT INTO calendar_data (user_id, date, condition_value, text) VALUES (:user_id, :date, :condition_value, :text)");
          $stmt->bindParam(':user_id', $user_id);
          $stmt->bindParam(":date",$date);
          $stmt->bindParam(':condition_value',$condition);
          $stmt->bindParam(':text',$text);
          $stmt->execute();
          echo "データは登録されました。";
          echo "<a href='./calender.php'>戻る</a>";
        }


         
    }catch(PDOException $e){
        echo "新規登録中にエラーが発生しました。エラー: " . $e->getMessage();
    }








// if ($_SERVER['REQUEST_METHOD'] === 'POST') {
//     // フォームから送信されたデータを取得
//     $year = $_POST['year'];
//     $month = $_POST['month'];
//     $day = $_POST['day'];
//     $condition = $_POST['condition'];
//     $text = $_POST['text'];

//     // 取得したデータを利用して必要な処理を行う
//     // ...

//     // 例: データの表示
//     echo "<h1>フォームデータ</h1>";
//     echo "<p>年: $year</p>";
//     echo "<p>月: $month</p>";
//     echo "<p>日: $day</p>";
//     echo "<p>条件: $condition</p>";
//     echo "<p>入力値: </p>";
//     echo "<p>テキスト: $text</p>";
//     // echo "<ul>";
//     // foreach ($dynamicInput as $input) {
//     //     echo "<li>$input</li>";
//     // }
//     // echo "</ul>";
// } else {
//     echo "Invalid request method. Please submit the form.";
// }