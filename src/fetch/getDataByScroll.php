<?php


session_start();

require_once(dirname(__FILE__) . "/../models/DomainsModel.php");
require_once(dirname(__FILE__) . "/../../vendor/autoload.php");

$dotenv = Dotenv\Dotenv::createImmutable(dirname(__FILE__) . "/../../");
$dotenv->load();

$user_id = "";
if(isset($_SESSION["user_id"])){
    $user_id = $_SESSION["user_id"];
}

$domainModel = new DomainsModel();


$raw = file_get_contents('php://input'); // POSTされた生のデータを受け取る
$data = json_decode($raw, true); // json形式をphp変数に変換

$res = $data;
$start = ($res["click"]) * 20;
$end = $start;

$category = $res["category"] !== "all" ? $res["category"]: "";

$original_domain = $domainModel->getDomainInfoByScroll($user_id, $start, $end, $category);

foreach ($original_domain as &$original) {
    $directory_domain = $domainModel->getDirectoryDataByCategory($user_id, $original["id"], $category);
    $copy_domain = $domainModel->getCopyDataByCategory($user_id, $original["id"], $category);

    $original["directory"] = $directory_domain;
    $original["copy"] = $copy_domain;

    foreach($original["copy"] as &$copy){

        
        $directory_domain = $domainModel->getDirectoryDataByCategory($user_id, $copy["id"], $category);

        $copy["directory"] = $directory_domain;

    }
}


echo json_encode($original_domain, true);
