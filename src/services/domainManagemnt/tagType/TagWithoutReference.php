<?php

use Random\Engine\Secure;


require_once(dirname(__FILE__) . "/../../../utils/SystemFeedback.php");
require_once(dirname(__FILE__) . "/../../../config/conf.php");
require_once(dirname(__FILE__) . "/../../../utils/FormValidation.php");
require_once(dirname(__FILE__) . "/../../../services/domainManagemnt/DomainDataAccess.php");
require_once(dirname(__FILE__) . "/../../../services/tagManagement/TagDataAccess.php");
require_once(dirname(__FILE__) . "/../../../services/domainManagemnt/tagType/TagTypeBase.php");
require_once(dirname(__FILE__) . "/../../../utils/Common.php");


// ドメイン処理共通(追加と編集)

class TagWithoutReference implements TagTypeBase{
    protected $domain_access;
    protected $tag_access;
    protected $common;
    protected $feedback;
    protected $allowedDomainTypes = ["original", "copy", "directory"];
    protected $allowedTagType = ["reference", "new", "withoutTag"];

    public function __construct(){
        $this->domain_access        = new DomainDataAccess();
        $this->tag_access           = new TagDataAccess();
        $this->common               = new Common();
    }

    public function formValidator(){
        FormValidation::checkAllNecessaryValues(DomainValidation::hasAllNecessaryValuesForCopyOrDirectorySite(), PATH . "index");
    }

    public function operateDatabaseWithAdd(){
        $this->formValidator();
        
        //  必要なデータがすべてあった場合、データーベースに保存する
        $id = $this->domain_access->insertDomainDataToDB($this->setDataWithAdd());
     
        $random_id = $this->domain_access->getRandomDomainIDForTagReferene($id);
    
   
        $_SESSION["script_index"] = "<script src='" . PATH_INDEX . $random_id . "'></script>";
        $_SESSION["script_rd"] = "<script src='" . PATH_RD . $random_id . "'></script>";
        $_SESSION["create_script_flag"] = 1;
    }

    public function operateDatabaseWithEdit(){
        $this->formValidator();
    
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
            "domain_category_id"    => intval($_POST["domain_category"]),
            "admin_id"              => $_SESSION["user_id"],
            "domain_name"           => DataValidation::sanitizeInput($_POST["domain_name"]),
            "domain_id"             => intval($_POST["domain_id"])
        ];

        return $domain_info;
    }

}

