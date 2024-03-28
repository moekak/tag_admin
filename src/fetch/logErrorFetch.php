<?php

require_once(dirname(__FILE__) . "/../config/conf.php");

session_start();

require_once(dirname(__FILE__) . "/../utils/SystemFeedback.php");

$error = new SystemFeedback();

$raw = file_get_contents('php://input'); // POSTされた生のデータを受け取る
$data = json_decode($raw, true); // json形式をphp変数に変換



$res = $data;
$error->logError($res);

$_SESSION["error"] = [ERROR_TEXT, ERROR_CODE_FETCH];
header("Location:" .PATH . "error");
