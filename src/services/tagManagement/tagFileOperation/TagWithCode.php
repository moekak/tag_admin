<?php
namespace TagFileOperation;

require_once(dirname(__FILE__) . "/../tagFileOperation/TagOperationBase.php");
require_once(dirname(__FILE__) . "/../../tagOperations/InsertTagDataToFile.php");
require_once(dirname(__FILE__) . "/../../../utils/DataValidation.php");


class TagWithCode extends TagOperationBase{
    public $tagOperation ;

    public function __construct(){
        $this->tagOperation = new \InsertTagDataToFile();
    }

    public function insertDataToFile($data){
        if($_POST["tag_head"]){
            $this->fileOperationHelper("tag_head", $data);
        }
        if($_POST["tag_body"]){
            $this->fileOperationHelper("tag_body", $data);
        }
    }

    public function updateTagDataFile($data){
        if((isset($_POST["tag_head"]) && $_POST["tag_head"] !== "") || $data["tag_head"] == '[]'){
      
            $this->fileOperationHelper("tag_head", $data);
        }
        if((isset($_POST["tag_body"]) && $_POST["tag_body"] !== "") || $data["tag_body"] == '[]'){
            $this->fileOperationHelper("tag_body", $data);
        }
    }

    public function deleteTagFileForShowPage($data){
        $trigger = strpos($data["trigger"], "all_") === 0 && $data["trigger"] !== "all_pages" ? str_replace("all_","", $data["trigger"]) : $data["trigger"];
        $filePath = $this->tagOperation->createTagFileForShow2($data["domain_id"], $data["tag_code"], $trigger);
        if(is_dir($filePath["top_directory3"])){
            $this->tagOperation->remove_directory($filePath["top_directory3"]);
        }

        $filePATH_INDEX = $this->tagOperation->createTagFileForShow($data["domain_id"], $data["tag_code"]);
        if($this->tagOperation->is_dir_empty($filePATH_INDEX["top_directory3"])){
            $this->tagOperation->remove_directory($filePATH_INDEX["top_directory3"]);
        }
    }
    
    public function fileOperationHelper($type, $data, $code = "", $code2 = ""){
        // コードのタグデータをデータベースから取得し、ファイルに書き込む
        $tagData = $this->tagOperation->getTagData($type, $data["trigger"], $data["domain_id"], $data["tag_code"], "");

        $trigger = strpos($data["trigger"], "all_") === 0 && $data["trigger"] !== "all_pages" ? str_replace("all_","", $data["trigger"]) : $data["trigger"];
        $filePath = $this->tagOperation->genereateFilePath($data["tag_code"], $type, $data["domain_id"], "tags_code", $trigger);

        if($tagData){
            $this->tagOperation->operateFiles(json_encode($tagData), $filePath["directory4"]);
        }else{
            if(is_dir($filePath["directory4"])){
                $this->tagOperation->remove_directory($filePath["directory4"]);
            }
            
        }

        $trigger = strpos($data["trigger"], "all_") === 0 && $data["trigger"] !== "all_pages" ? str_replace("all_","", $data["trigger"]) : $data["trigger"];
        $filePath = $this->tagOperation->genereateFilePath($data["tag_code"], $type, $data["domain_id"], "tags_code", $trigger);

        if($this->tagOperation->is_dir_empty($filePath["directory2"]) && is_dir($filePath["directory2"])){
            $this->tagOperation->remove_directory($filePath["directory2"]);
        }
    }
}

