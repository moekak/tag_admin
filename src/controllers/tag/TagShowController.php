<?php
require_once(dirname(__FILE__) . "/../../models/TagsModel.php");
require_once(dirname(__FILE__) . "/../../models/DomainsModel.php");
require_once(dirname(__FILE__) . "/../../models/TagsRangeModel.php");
require_once(dirname(__FILE__) . "/../../utils/Security.php");
require_once(dirname(__FILE__) . "/../../utils/DataValidation.php");
require_once(dirname(__FILE__) . "/../../utils/SystemFeedback.php");
require_once(dirname(__FILE__) . "/../../../src/services/tagManagement/TagDataAccess.php");



class TagShowController{
    public $tag_access;
    public $tag_model;
    public $domain_model;
    public $tagRange_model;
    public $domain_id;

    public function __construct(){
        $this->tag_model  = new TagsModel();
        $this->domain_model = new DomainsModel();
        $this->tagRange_model = new TagsRangeModel();
        $this->tag_access = new TagDataAccess();
    }

    // ##########################################################
    // get method
    //##########################################################
    public function get(){
        // // 不正IDまたは値が入ってるかのチェック
        $checkId = boolval(isset($_GET["id"]) && DataValidation::isValidID($_GET["id"]) && $this->tag_access->isValidTagID($_GET["id"]));
        if(!$checkId){
            SystemFeedback::invalidIDErrorForShowPage("id");
            exit;
        }


   
        $specificTagData     = $this->tag_model->getTagsInfo($_SESSION["user_id"], intval($_GET["id"]));
        $_SESSION["tagData"] = $specificTagData;
    

        Security::generateCsrfToken();
     
        require __DIR__ . '/../../../src/views/tag_show.php';
    
    }
    public function getRange(){
        // // 不正IDまたは値が入ってるかのチェック
        $checkId = boolval(isset($_GET["id"]) && DataValidation::isValidID($_GET["id"]) && $this->tag_access->isValidTagIDWithRange($_GET["id"]));

        if(!$checkId){
            SystemFeedback::invalidIDErrorForShowPage("id");
            exit;
        }
   
        $specificTagData     = $this->tagRange_model->getTagsRangeData($_SESSION["user_id"], intval($_GET["id"]));
        $_SESSION["tagData"] = $specificTagData;
    
        Security::generateCsrfToken();
     
        require __DIR__ . '/../../../src/views/tagRange_show.php';
    
    }

    public function error(){
        SystemFeedback::invalidIDErrorForShowPage("id");
    }

}
