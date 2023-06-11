<?php
session_start();

if(empty($_SESSION)){
    header("Location: ./login_error.php");
}


$user_id = $_SESSION["user_id"];
$user_name = $_SESSION["user_name"];

include(dirname(__FILE__) . '/header.php');

?>
<style>
   .container{
    text-align: center;
   }

   #question_button{
    width: 50%;
    height: 5.0em;
    margin: 2.0em;
    font-weight: bold;
   }


</style>


    <?php echo $_SESSION["user_name"]."さんでログイン中<br>"?>
    <div class="container">
    <h1>質問を入力</h1>
    <form id="form" action="./question_check.php" method="post">
        <textarea type="text" name="question" id="textarea" required></textarea>
        <input type="submit" value="質問する" id="question_button">
    </form>
    <a href="./menu.php" class="btn btn-primary">menuへ</a>
    </div>

</body>
</html>
