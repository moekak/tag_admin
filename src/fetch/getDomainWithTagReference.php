<?php




session_start();

require_once(dirname(__FILE__) . "/../models/DomainsModel.php");
require_once(dirname(__FILE__) . "/../../vendor/autoload.php");

$dotenv = Dotenv\Dotenv::createImmutable(dirname(__FILE__) . "/../../");
$dotenv->load();

$user_id = isset($_SESSION["admin_id"]) ? $_SESSION["admin_id"] : $_SESSION["user_id"];

$domainModel = new DomainsModel();


$raw = file_get_contents('php://input'); // POSTされた生のデータを受け取る
$data = json_decode($raw, true); // json形式をphp変数に変換



$res = $data;

$results = $domainModel->getDomainNameWithTagReference($res, $user_id);

// $results2 = $domainModel->getDomainNameWithTagReferenceDirectory($res, $user_id);

// $domain_name;

// foreach($results2 as &$result){
//     $domain_name = $domainModel->getDomainNameForDirectory($user_id, $result["parent_domain_id"]);
//     $directory = $result["domain_name"];
//     $result["domain_name"] = $domain_name . "/" . $directory;
// }





// echo json_encode(array_merge($results, $results2), true);
echo json_encode($results);
