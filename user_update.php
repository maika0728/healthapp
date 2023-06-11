<?php

include(dirname(__FILE__) . '/header.php');

session_start();


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <div class="container">
        <h1>更新情報</h1>
        <form action="./user_update_check.php" method="post" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="name" class="form-label">name</label>
                <input type="text" name="name" class="form-control">
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">email</label>
                <input type="email" name="email" class="form-control">
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">password</label>
                <input type="password" name="password" class="form-control">
            </div>
            <div class="mb-3">
                <label for="password2" class="form-label">password</label>
                <input type="password" name="password2" class="form-control">
            </div>
            <div class="mb-3">
                <label for="image" name='image' class="form-label">image</label>
                <input type="file" name="image" id="image" class='form-control' />
            </div>
            <input type="submit" value="更新する" name="submit" />
        </form>
    </div>
</body>

</html>