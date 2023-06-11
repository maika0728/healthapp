<?php
echo '<a href="./login.php" class="btn btn-info">ログイン</a>';
include(dirname(__FILE__) . '/header.php');

?>

<style>
    h1 {
        font-size: 18px;
    }
</style>

<div class="container">
    <h1>新規登録</h1>
    <form action="./sign_up_check.php" method="post" enctype="multipart/form-data">
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
        <input type="submit" value="登録する" name="submit" />
    </form>
</div>
</body>

</html>