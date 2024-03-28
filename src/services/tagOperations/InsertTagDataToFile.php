<?php

use function PHPSTORM_META\type;

require_once dirname(__FILE__) . "/../../config/conf.php";
require_once dirname(__FILE__) . "/../../models/TagsModel.php";
require_once dirname(__FILE__) . "/../../models/TagsRangeModel.php";
require_once dirname(__FILE__) . "/../../models/DomainsModel.php";
require_once dirname(__FILE__) . "/../../utils/SystemFeedback.php";

// error_reporting(0);



class InsertTagDataToFile{
   public $tag_model;
   public $tag_range_model;
   public $domain_model;

    public function __construct(){
        $this->tag_model        = new TagsModel();
        $this->tag_range_model  = new TagsRangeModel;
        $this->domain_model     = new DomainsModel();
    }

    public function genereateFilePath($code, $position, $domain_id, $tagType, $type){

        $codeName = $code && $code !== "" ? $code : "all";

        $directoryPaths = [
            'directory1' => TAG_FILE_PATH . $tagType . "/" . $this->getRandomDomainID($domain_id) . "/",
            'directory2' => TAG_FILE_PATH . $tagType . "/" . $this->getRandomDomainID($domain_id) . "/" . $codeName . "/",
            'directory3' => TAG_FILE_PATH . $tagType . "/" . $this->getRandomDomainID($domain_id) . "/" . $codeName . "/" . $type . "/",
            'directory4' => TAG_FILE_PATH . $tagType . "/" . $this->getRandomDomainID($domain_id) . "/" . $codeName . "/" . $type . "/" . $position . "/"
        ];


        return $directoryPaths;
    }


    public function operateFiles($data, $directoryPath4){ 

        // echo $directoryPath4 . "22";
        // exit;
  
        if (!file_exists($directoryPath4)) {
            mkdir($directoryPath4, 0755, true); // 再帰的に作成
        }

        $filename = $directoryPath4 . "tag". ".txt";
        $text = "";

        // 　データベースから取得したデータを改行を入れて一つの文字列にする
        foreach(json_decode($data) as $item){
            $text .= $item . "\n\n";
        };

        // ファイルに書き込みむ
        file_put_contents($filename, $text);

    }

    public function createTagFilePath($domain_id){

        return [
            "top_directory1" =>  TAG_FILE_PATH . "tags/" . $this->getRandomDomainID($domain_id) . "/",
            "top_directory2" => TAG_FILE_PATH . "tags_range/" . $this->getRandomDomainID($domain_id) . "/",
            "top_directory3" =>TAG_FILE_PATH . "tags_code/" . $this->getRandomDomainID($domain_id) . "/"
        ];
    }

    public function createTagFileForShow($domain_id, $code){
        return [
            "top_directory1" =>  TAG_FILE_PATH . "tags/" . $this->getRandomDomainID($domain_id) . "/" . $code . "/" ,
            "top_directory2" => TAG_FILE_PATH . "tags_range/" . $this->getRandomDomainID($domain_id) . "/". $code . "/" ,
            "top_directory3" =>TAG_FILE_PATH . "tags_code/" . $this->getRandomDomainID($domain_id) . "/" . $code . "/" 
        ];
    }
    public function createTagFileForShow2($domain_id, $code, $type){
        return [
            "top_directory1" =>  TAG_FILE_PATH . "tags/" . $this->getRandomDomainID($domain_id) . "/" . $code . "/" .$type ."/" ,
            "top_directory2" => TAG_FILE_PATH . "tags_range/" . $this->getRandomDomainID($domain_id) . "/". $code . "/" .$type ."/",
            "top_directory3" =>TAG_FILE_PATH . "tags_code/" . $this->getRandomDomainID($domain_id) . "/" . $code . "/" .$type ."/"
        ];
    }


    // ドメインのタグをすべて削除する
    public function deleteTagDataFile($domain_id){
        $top_directory1 = $this->createTagFilePath($domain_id)["top_directory1"];
        $top_directory2 = $this->createTagFilePath($domain_id)["top_directory2"];
        $top_directory3 = $this->createTagFilePath($domain_id)["top_directory3"];
        
        
        if(is_dir($top_directory1)){
            $this->remove_directory($top_directory1);
        }
        if(is_dir($top_directory2)){
            $this->remove_directory($top_directory2);
        }
        if(is_dir($top_directory3)){
            $this->remove_directory($top_directory3);
        }
    }


    // フォルダ権限を無効にする処理　
    // public function disableFolderAccess($domain_id){

    //     $tagFolderPath1 = $this->createTagFilePath($domain_id)["top_directory1"];
    //     $tagFolderPATH_INDEX = $this->createTagFilePath($domain_id)["top_directory2"];
    //     $tagFolderPath3 = $this->createTagFilePath($domain_id)["top_directory3"];

    //     $htaccessContent = "Deny from all";
        
    //     if(is_dir($tagFolderPath1)){
    //         file_put_contents($tagFolderPath1 . "/.htaccess", $htaccessContent);
    //     }
    //     if(is_dir($tagFolderPATH_INDEX)){
    //         file_put_contents($tagFolderPATH_INDEX . "/.htaccess", $htaccessContent);
    //     }
    //     if(is_dir($tagFolderPath3)){
    //         file_put_contents($tagFolderPath3 . "/.htaccess", $htaccessContent);
    //     }
    // }

    // public function enableFolderAccess($domain_id){
    //     $tagFolderPath1 = $this->createTagFilePath($domain_id)["top_directory1"];
    //     $tagFolderPATH_INDEX = $this->createTagFilePath($domain_id)["top_directory2"];
    //     $tagFolderPath3 = $this->createTagFilePath($domain_id)["top_directory3"];

    //     if(file_exists($tagFolderPath1 . "/.htaccess")){
    //         unlink($tagFolderPath1 . "/.htaccess");
    //     }
    //     if(file_exists($tagFolderPATH_INDEX . "/.htaccess")){
    //         unlink($tagFolderPATH_INDEX . "/.htaccess");
    //     }
    //     if(file_exists($tagFolderPath3 . "/.htaccess")){
    //         unlink($tagFolderPath3 . "/.htaccess");
    //     }
    // }

    public function is_dir_empty($dir) {
        if (!is_readable($dir)) return NULL; // ディレクトリが読み取り可能かチェック
        return (count(scandir($dir)) == 2);  // '.' と '..' のみ存在する場合、ディレクトリは空です
    }

    public function remove_directory($dir) {
        if(!is_dir($dir)){
            SystemFeedback::transactionError("directory cannot be found:" . $dir);
        }

        
            //特定のディレクトリ内のファイルとサブディレクトリのリストを取得(配列の形)(現在のディレクトリと親ディレクトリは省く)
            try{
                $files = array_diff(scandir($dir), array('.','..'));
                // もしフォルダの中身が空だったら($filesのデータは空の配列になる)for文は実行されずに、deleteDirectoryWithRetry()の処理が走る
                foreach ($files as $file) {
                    if (is_dir("$dir/$file")) {
                        // ディレクトリなら再度同じ関数を呼び出す
                        $this->remove_directory("$dir/$file");
                    } else {
                        // ファイルなら削除（リトライロジック適用）
                        if (!$this->deleteFileWithRetry("$dir/$file")) {
                            SystemFeedback::fileOperationError("file", $dir/$file);
                        }
                    }
                }
                // 指定したディレクトリを削除（リトライロジック適用）
                if (!$this->deleteDirectoryWithRetry($dir)) {
                    SystemFeedback::fileOperationError("directory", $dir);
                }
            }catch(Exception $e){
                SystemFeedback::transactionError($e->getMessage());
            }
        
        
       
       
    }

    public function deleteDirectoryWithRetry($dirPath, $maxRetries = 3, $delayInSeconds = 1) {
        $currentRetry = 0;
    
        while ($currentRetry < $maxRetries) {
            //@はもしrmdir関数が何らかの理由で失敗しても、エラーメッセージは表示されず、スクリプトの実行は中断されない
            if (@rmdir($dirPath)) {
                return true;
            }
    
            // 1秒待ってからもう一度再実行する（最大３回まで）
            sleep($delayInSeconds);
            $currentRetry++;
        }
    
        return false;
    }

    public function deleteFileWithRetry($filePath, $maxRetries = 3, $delayInSeconds = 1) {
        $currentRetry = 0;
    
        while ($currentRetry < $maxRetries) {
            if (@unlink($filePath)) {
                // ファイルの削除に成功
                return true;
            }
    
            // 削除に失敗した場合、リトライ前に遅延
            sleep($delayInSeconds);
            $currentRetry++;
        }
    
        // 最大リトライ回数に達した場合、失敗とみなす
        return false;
    }
    



    // tagsテーブルから特定のデータを取得
    public function getTagData($position, $type, $id, $code){
        $tagDataWithCode = $this->tag_model->getMatchedTagData($_SESSION["user_id"], intval($id), $position, $type, $code);
        return $this->generateTagArray($tagDataWithCode);
    }

    // tags_rangeテーブルから特定のデータを取得
    public function getTagDataForRange($position, $type, $id, $code, $excluded_code){
        $tagDataWithCodeRange = $this->tag_range_model->getMatchedTagData($_SESSION["user_id"], intval($id), $position, $type, $code, $excluded_code);
        return $this->generateTagArray($tagDataWithCodeRange);
    }

    // tagsテーブルから特定のデータを取得
    public function getTagDataWithoutCode($position, $type, $id){
        $tagDataWithCode = $this->tag_model->getMatchedTagData($_SESSION["user_id"], intval($id), $position, $type, "");
        return $this->generateTagArray($tagDataWithCode);
    }

    public function generateTagArray($tagData){
        $merge_tags = [];

        foreach($tagData as $tag){
            foreach($tag as  $val){
                $data = json_decode($val) ;
                foreach($data as $item){
                    array_push($merge_tags, $item);
                }
            }
        }

        return $merge_tags;

    }
    

    //オリジナルドメインIDを取得する
    public function getRandomDomainID($domain_id){
        $original_id = $this->domain_model->getDomainRandomId($domain_id, $_SESSION["user_id"]);

        return $original_id;
    }
    
}