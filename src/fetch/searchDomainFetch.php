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
$keyword = "%" . $res["keyword"] . "%";



if(!empty($res["domain_id"])){
    $results = $domainModel->searchDomainNameForEdit($keyword, $user_id, $res["domain_id"]);
} else{
    $results = $domainModel->searchDomainName($keyword, $user_id);
}



echo json_encode($results, true);
