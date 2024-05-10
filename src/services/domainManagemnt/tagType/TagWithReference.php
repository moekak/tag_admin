<?php

use Random\Engine\Secure;


require_once(dirname(__FILE__) . "/../../../utils/SystemFeedback.php");
require_once(dirname(__FILE__) . "/../../../config/conf.php");
require_once(dirname(__FILE__) . "/../../../utils/FormValidation.php");
require_once(dirname(__FILE__) . "/../../../services/domainManagemnt/DomainDataAccess.php");
require_once(dirname(__FILE__) . "/../../../services/tagManagement/TagDataAccess.php");
require_once(dirname(__FILE__) . "/../../../services/domainManagemnt/tagType/TagTypeBase.php");
require_once(dirname(__FILE__) . "/../../../utils/Common.php");
require_once(dirname(__FILE__) . "/../../../models/TagsRangeModel.php");
require_once(dirname(__FILE__) . "/../../../services/tagOperations/InsertTagDataToFile.php");



// ドメイン処理共通(追加と編集)

class TagWithReference implements TagTypeBase{
    protected $domain_access;
    protected $tag_access;
    protected $common;
    protected $tagsRange_model;
    protected $tag_operation;
    protected $allowedDomainTypes = ["original", "copy", "directory"];
    protected $allowedTagType = ["reference", "new", "withoutTag"];

    public function __construct(){
        $this->domain_access        = new DomainDataAccess();
        $this->tag_access           = new TagDataAccess();
        $this->common               = new Common();
        $this->tagsRange_model      = new TagsRangeModel();
        $this->tag_operation        = new InsertTagDataToFile();
    }

    public function formValidator($error){
       // 必要なデータがすべてあるか
        FormValidation::checkAllNecessaryValues(DomainValidation::hasAllNecessaryValuesForCopySiteWhenReferenceTag(), PATH . "index", $error);
        //不正ドメインIDかの検証(このIDでタグテーブルにデータが保存されてるか)
        FormValidation::checkValidID("parent_tag_id", ($this->tag_access->isTagDataExisted(intval($_POST["parent_tag_id"]))) || $this->tag_access->isTagDataWithRangeExisted(intval($_POST["parent_tag_id"])));
    }

    public function operateDatabaseWithAdd(){

        $this->formValidator("create");
    // タグ参照するドメインに紐づいたタグデータを取得する
        $tag_results = $this->tag_access->getTagsInfo(intval($_POST["parent_tag_id"]));
        $tagRange_results = $this->tag_access->getTagsRangeInfo(intval($_POST["parent_tag_id"]));
        $id = "";

        //  // 保存したドメインのIDをlastInsertId()で取得してる(タグ情報をタグテーブルに保存する際に使用)
        $domain_id = $this->domain_access->insertDomainDataToDB($this->setDataWithAdd());
        $id = $domain_id;

        // スクリプトタグ生成
        $random_id = $this->domain_access->getRandomDomainIDForTagReferene($id);
    
        
        $_SESSION["script_index"] = "<script src='" . PATH_INDEX . $random_id . "' data-config='KD_tagadmin'></script>";
        $_SESSION["script_rd"] = "<script src='" . PATH_RD . $random_id . "' data-config='KD_tagadmin'></script>";
        $_SESSION["create_script_flag"] = 1;

        foreach($tag_results as $key => $value){
            $this->tag_access->InsertTagDataToFile($value["tag_head"], $value["tag_body"], $value["ad_code"], $value["trigger_type"], $id, intval($_POST["parent_tag_id"]));
        }

        foreach($tagRange_results as $key => $value){
            $this->tag_access->insertTagDataWithRangeToDB($id, $value["tag_head"], $value["tag_body"], $value["code_range"], $value["trigger_type"], intval($_POST["parent_tag_id"]), '[]');
        }

        $this->fileOperationHelperForInsert($tagRange_results, $domain_id);
        $this->fileOperationWithCode($tag_results, $domain_id);


    }

    public function operateDatabaseWithEdit(){
        $this->formValidator("edit");
        // ドメイン更新
        $this->domain_access->updateDomainDataToDB($this->setDataWithEdit()); 
    }

    public function setDataWithAdd(){
        $domain_info = [
            "domain_category_id"    => intval($_POST["domain_category"]),
            "admin_id"              => $_SESSION["user_id"],
            "random_domain_id"      => $this->common->randomString(10),
            "parent_domain_id"      => $_POST["parent_domain_id"] ? intval($_POST["parent_domain_id"]): null,
            "original_parent_id"    => $_POST["original_parent_id"] ? intval($_POST["original_parent_id"]): null,
            "domain_name"           => DataValidation::sanitizeInput($_POST["domain_name"]),
            "domain_type"           => DataValidation::sanitizeInput($_POST["domain_type"]),
            "tag_reference_id"      => null,
            "tag_reference_randomID" => null
        ];

        return $domain_info;
    }

    public function setDataWithEdit(){
        $domain_info = [
            "admin_id"              => $_SESSION["user_id"],
            "domain_name"           => DataValidation::sanitizeInput($_POST["domain_name"]),
            "domain_id"             => intval($_POST["domain_id"])
        ];

        return $domain_info;
    }



    // ##################################################################################
    //                            タグを参照したときのファイル操作
    // ##################################################################################m

    // ファイルに追加する処理
    public function fileOperationHelperForInsert($tagData, $domain_id){
        foreach($tagData as $data){
            $tagCodeRange_array = json_decode($data["code_range"]);
        
            foreach($tagCodeRange_array as $code){
                if(count(json_decode($data["tag_head"])) > 0){
                    $filePath = $this->tag_operation->genereateFilePath($code, "tag_head", $domain_id, "tags_range", $data["trigger_type"]);

                    $this->tag_operation->operateFiles($data["tag_head"], $filePath["directory4"]);
                }
                if(count(json_decode($data["tag_body"])) > 0){
                    $filePath = $this->tag_operation->genereateFilePath($code, "tag_body", $domain_id, "tags_range", $data["trigger_type"]);
                    $this->tag_operation->operateFiles($data["tag_body"], $filePath["directory4"]);
                } 
            }
        }
    }

    public function fileOperationWithCode($tagData, $domain_id){
        foreach($tagData as $tag){
            if($tag["ad_code"] && $tag["ad_code"] !== ""){
                if(count(json_decode($tag["tag_head"])) > 0){
                    $filePath = $this->tag_operation->genereateFilePath($tag["ad_code"], "tag_head", $domain_id, "tags_code", $tag["trigger_type"]);
                    $this->tag_operation->operateFiles($tag["tag_head"], $filePath["directory4"]);
                }
                if(count(json_decode($tag["tag_body"])) > 0){
                    $filePath = $this->tag_operation->genereateFilePath($tag["ad_code"], "tag_body", $domain_id, "tags_code", $tag["trigger_type"]);
                    $this->tag_operation->operateFiles($tag["tag_body"], $filePath["directory4"]);
                }
            }else{
                if(count(json_decode($tag["tag_head"])) > 0){
                    $filePath = $this->tag_operation->genereateFilePath("", "tag_head", $domain_id, "tags", $tag["trigger_type"]);
                    $this->tag_operation->operateFiles($tag["tag_head"], $filePath["directory4"]);
                }
                if(count(json_decode($tag["tag_body"])) > 0){
                    $filePath = $this->tag_operation->genereateFilePath("", "tag_body", $domain_id, "tags", $tag["trigger_type"]);
                    $this->tag_operation->operateFiles($tag["tag_body"], $filePath["directory4"]);
                }
            }
        }
    }

  

}

