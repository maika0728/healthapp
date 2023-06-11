<?php
session_start();

if(empty($_SESSION)){
    header("Location: ./login_error.php");
}


?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Document</title>
  </head>
  <body>
    <form action="./image_check.php" method="post" enctype="multipart/form-data">
      <input type="file" name="image" id="image" />
      <input type="submit" value="Upload Image" name="submit" />
    </form>
    <a href="./output.php">画像一覧へ</a>
  </body>
</html>
