<?php


session_start();

require_once(dirname(__FILE__) . "/../models/AdminsModel.php");
require_once(dirname(__FILE__) . "/../../vendor/autoload.php");

$dotenv = Dotenv\Dotenv::createImmutable(dirname(__FILE__) . "/../../");
$dotenv->load();


$amdin_model = new AdminsModel();

$raw = file_get_contents('php://input'); // POSTされた生のデータを受け取る
$data = json_decode($raw, true); // json形式をphp変数に変換

$res = $data;

$username = $amdin_model->getAdminName($res["id"]);




echo json_encode($username, true);
