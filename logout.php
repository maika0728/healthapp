<?php
// セッションを開始または既存のセッションを再開します
session_start();

// セッション変数を空にします
$_SESSION = array();

// セッションを破棄します
session_destroy();

// ログアウト後にリダイレクトする場合は、リダイレクト先のURLに変更してください
header("Location: ./login.php");
exit();
?>
