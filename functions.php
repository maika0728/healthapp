<?php
// データベースに接続
function connectDB() {
    $param = 'mysql:dbname=healthapp;host=localhost';
    try {
        $pdo = new PDO($param, 'kaima', 'kt7281');
        return $pdo;

    } catch (PDOException $e) {
        exit($e->getMessage());
    }
}
?>