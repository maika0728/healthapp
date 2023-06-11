<?php
session_start();
include(dirname(__FILE__) . '/header.php');
echo '<a href="./sign_up.php" class="btn btn-info">新規登録</a>';
?>
<div class="container">
    <h1>ログイン</h1>
    <form action="./login_check.php" method="post">
        <div class="input">
            <label for="email" class="form-label">email</label>
            <input type="email" name="email" class="form-control">
        </div>
        <div class="input">
            <label for="password" class="form-label">password</label>
            <input type="password" name="password" class="form-control">
        </div>
        <input type="submit" value="submit" class="btn btn-secondary">
    </form>
</div>
</body>

</html>