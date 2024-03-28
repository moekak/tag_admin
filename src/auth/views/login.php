<?php

require_once(dirname(__FILE__) . "/../../config/conf.php");




$error = "";
if(isset($_SESSION["login_error"])){
    $error = $_SESSION["login_error"];
    unset($_SESSION["login_error"]);
}

?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?= PATH ?>src/auth/style/auth.css">
    <link rel="stylesheet" href="<?= PATH ?>public/css/common.css">
    <title>タグ管理画面</title>
</head>
<body>
    <div class="auth_wrapper">
        <h1 class="white relative">ログイン</h1>
        <div class="error_container" style="color: red;"><?=$error?></div>
        <form action="<?=PATH ?>" method="post">
            <input type="hidden" name="csrf_token" value=<?=$_SESSION['csrf_token']?>>
            <div class="auth_input_container">
                <label for="" class="white">ユーザ名</label>
                <input type="text" class="input_style" name="username" id="js_input_name">
            </div>
            <div class="auth_input_container">
                <label for="" class="white">パスワード</label>
                <input type="text" class="input_style"  name="password" id="js_input_password">
            </div>
            <div class="auth_btn_container">
                <button type="submit" class="c auth_btn disabled_btn" id="js_login_btn">ログイン</button>
            </div>
        </form>
    </div>
    <script src="<?= PATH ?>src/auth/js/auth.js"></script>
</body>
</html>