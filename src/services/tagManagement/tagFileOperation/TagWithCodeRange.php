<?php
namespace TagFileOperation;

use Exception;

require_once(dirname(__FILE__) . "/../tagFileOperation/TagOperationBase.php");
require_once(dirname(__FILE__) . "/../../tagOperations/InsertTagDataToFile.php");
require_once(dirname(__FILE__) . "/../../../utils/DataValidation.php");


class TagWithCodeRange extends TagOperationBase{
    public $tagOperation;

    public function __construct(){
        $this->tagOperation = new \InsertTagDataToFile();
    }

    // タグデータをファイルに保存する
    public function insertDataToFile($data){
        if($_POST["tag_head"]){
            $this->fileOperationHelperForInsert("tag_head", $data);
        }
            if($_POST["tag_body"]){ 
            $this->fileOperationHelperForInsert("tag_body", $data);
        }
    }


    public function deleteTagFileForShowPage($data){
        $codes = json_decode($data["tag_code"]);

        foreach($codes as $code){
            $trigger = strpos($data["trigger"], "all_") === 0 && $data["trigger"] !== "all_pages" ? str_replace("all_","", $data["trigger"]) : $data["trigger"];
             $filePath = $this->tagOperation->createTagFileForShow2($data["domain_id"], $code, $trigger);
            if(is_dir($filePath["top_directory2"])){
                $this->tagOperation->remove_directory($filePath["top_directory2"]);
            }
            
             $filePATH_INDEX = $this->tagOperation->createTagFileForShow($data["domain_id"], $code);
            if($this->tagOperation->is_dir_empty($filePATH_INDEX["top_directory2"])){
                $this->tagOperation->remove_directory($filePATH_INDEX["top_directory2"]);
            }


        }


       
    }

    // ファイルのタグデータを更新する
    public function updateTagDataFile($data){
        
        // 送られてきた広告コードの配列データ
        $tagCode = json_decode($data["tag_code"]);

        // 送られてきた排除コードの配列データ
        $excludedCode = [];
        if(isset($_POST["excludedCode"])){
            $excludedCode = $_POST["excludedCode"];
        }

        // 広告コードから、排除コードの除いた配列のデータ
        $active_code = array_diff($tagCode, $excludedCode);

        // タグheadのデータが送られてきた場合
        if($data["tag_head"]){
            $this->fileOperationHelperForUpdate("tag_head", $data, $active_code, $tagCode); 
        }

        if($data["tag_body"]){
            $this->fileOperationHelperForUpdate("tag_body", $data, $active_code, $tagCode); 
        }

    }

      // ファイル更新の共通処理
    public function fileOperationHelper($type, $data, $code, $code2){
        $tagData = $this->tagOperation->getTagDataForRange($type, $data["trigger"], $data["domain_id"], $code, $code2);
        // ファイルのパスを生成

        $trigger = strpos($data["trigger"], "all_") === 0 && $data["trigger"] !== "all_pages" ? str_replace("all_","", $data["trigger"]) : $data["trigger"];
        $filePath = $this->tagOperation->genereateFilePath($code, $type, $data["domain_id"], "tags_range", $trigger);
        if($tagData){
            // データがあったらファイルを更新する
            $this->tagOperation->operateFiles(json_encode($tagData), $filePath["directory4"]);
        }else{
            if(is_dir($filePath["directory4"])){
                // データがなかったらそのフォルダを削除する
                $this->tagOperation->remove_directory($filePath["directory4"]);   
            }  
        }
    }
    
    // ######################################################################
    //                    このクラスのみの独自のヘルパー関数
    // ######################################################################

    // ファイルに追加する処理
    public function fileOperationHelperForInsert($type, $data){
        // コードの範囲分の各タグデータをデータベースから取得し、ファイルに書き込む
        foreach(json_decode($data["tag_code"]) as $code){
            $this->fileOperationHelper($type, $data, $code, "");
        }
    }

    // ファイルを更新する処理
    public function fileOperationHelperForUpdate($type, $data, $active_code, $tagCode){
        // 除外コードが送信された場合、除外コードと、それ以外のコード二通りの処理が必要
        $excludedCode = isset($_POST["excludedCode"]) ? $_POST["excludedCode"] : json_decode($data["excluded_code"]);

        if($excludedCode){
            // 除外コードに登録されたコードの各タグデータをデータベースから取得して、ファイルに上書きする
            foreach($excludedCode as $code){
                $this->fileOperationHelper($type, $data, $code, $code);
            }
            // 除外コード以外のコードの各タグデータをデータベースから取得して、ファイルに上書きする
            foreach($active_code as $code){
                $this->fileOperationHelper($type, $data, $code, "");
            }  
        // 除外コードがない場合は、各コードのタグデータをデータベースから取得して、ファイルに上書きする
        }else{
            foreach($tagCode as $code){
                $this->fileOperationHelper($type, $data, $code, "");
            }  
        }

        // フォルダが空だったらそのパスのフォルダをすべて再帰的に消す
        foreach($tagCode as $code){
            $trigger = strpos($data["trigger"], "all_") === 0 && $data["trigger"] !== "all_pages" ? str_replace("all_","", $data["trigger"]) : $data["trigger"];
            $filePath = $this->tagOperation->genereateFilePath($code, $type, $data["domain_id"], "tags_range", $trigger);

            if($this->tagOperation->is_dir_empty($filePath["directory2"]) && is_dir($filePath["directory2"])){
                $this->tagOperation->remove_directory($filePath["directory2"]);
            }
            
        }
    }
}

