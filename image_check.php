<?php

session_start();
$userId = $_SESSION["user_id"];

// データベース接続情報
$dbHost = "localhost";
$dbUser = "kaima";
$dbPass = "kt7281";
$dbName = "healthapp";

// データベースに接続
$conn = new mysqli($dbHost, $dbUser, $dbPass, $dbName);
if ($conn->connect_error) {
  die("データベース接続エラー: " . $conn->connect_error);
}

// 画像のアップロード処理
if (isset($_POST["submit"])) {
  $imageName = $_FILES["image"]["name"];
  $imageTmp = $_FILES["image"]["tmp_name"];

  // 画像をデータベースに保存
  $sql = "UPDATE users SET image_name = '$imageName' WHERE id = $userId";
  if ($conn->query($sql) === TRUE) {
    // ファイルをサーバーに移動
    move_uploaded_file($imageTmp,"./image" . $imageName);
    echo "画像が正常にアップロードされました。";
    echo "<a href='./question_board.php'>画像一覧へ</a>";
  } else {
    echo "エラー: " . $sql . "<br>" . $conn->error;
  }
}

// データベース接続をクローズ
$conn->close();
?>



