<style>
    #container {
        width: 60%;
        margin: auto;
        padding: 1.0em;
        display: flex;
        justify-content: space-between;
    }

    a {
        display: inline-block;
    }
</style>


<?php
session_start();
// echo $_SESSION["user_id"];

include(dirname(__FILE__) . '/header.php');

echo $_SESSION["user_name"] . "さんでログイン中<br>";
echo "<div id='container'>";
echo "<a href='./calender.php' class='btn btn-info'>calender</a>";
echo "<a href='./question.php' class='btn btn-danger'>掲示板に質問する</a>";
echo "<a href='./question_board.php'class='btn btn-warning'>掲示板</a>";
echo "<a href='./user_update_confirm.php' class='btn btn-outline-success'>ユーザー情報変更へ</a>";
echo "<a href='./logout.php' class='btn btn-outline-primary'>ログアウト</a>";
echo "</div>";


?>