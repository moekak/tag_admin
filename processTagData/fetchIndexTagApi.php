<?php
require_once dirname(__FILE__) . "/TagDataProcess.php";


header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Content-Type');

$raw = file_get_contents('php://input'); // POSTされた生のデータを受け取る
$data = json_decode($raw, true); // json形式をphp変数に変換

$res = $data;
$results_array= [];

$process = new TagDataProcess();

$result = $process->getIndexTagHead($process->generateFilePath($res["id"]), $res["code"]);
$result2 = $process->getIndexTagBody($process->generateFilePath($res["id"]), $res["code"]);

if($result !== "" || $result2 !== ""){
    $results_array = [$result, $result2];
}

echo json_encode($results_array);
