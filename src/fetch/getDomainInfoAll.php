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



$original_domain = $domainModel->getDomainInfo($user_id, "");

foreach ($original_domain as &$original) {
    $directory_domain = $domainModel->getDirectoryDataByCategory($user_id, $original["id"], "");
    $copy_domain = $domainModel->getCopyDataByCategory($user_id, $original["id"], "");

    $original["directory"] = $directory_domain;
    $original["copy"] = $copy_domain;

    foreach($original["copy"] as &$copy){
        
        $directory_domain = $domainModel->getDirectoryDataByCategory($user_id, $copy["id"], "");

        $copy["directory"] = $directory_domain;

    }
}


unset($original);
unset($copy);




echo json_encode($original_domain, true);
