<?php

namespace TagValidation;

require_once(dirname(__FILE__) . "/../../../utils/FormValidation.php");
require_once(dirname(__FILE__) . "/../../../services/domainManagemnt/DomainDataAccess.php");
require_once(dirname(__FILE__) . "/../../../services/tagManagement/TagDataAccess.php");


abstract class TagValidationBase{
    public $domain_access;
    public $tag_access;
    private $allowedAdCodes = ["index", "rd", "all_index", "all_rd", "all_pages"];

    public function __construct(){
        $this->domain_access    = new \DomainDataAccess();
        $this->tag_access       = new \TagDataAccess();
    }

    abstract public function individualFormValidator();
    abstract public function formValidatorForEdit();

     // 基本的な共通するバリデーションチェックとセキュリティチェック
     public function commonFormValidator(){
        \FormValidation::checkCSRF();
        \FormValidation::checkEmptyData("domain_id", PATH ."index");
        \FormValidation::checkValidID("domain_id", $this->domain_access->isDomainDataExisted("domain_id"));
        \FormValidation::checkValidData($this->allowedAdCodes, "trigger_category");
    } 

    public function commonFormValidatorForDeactivate(){
        \FormValidation::checkCSRF();
        \FormValidation::checkEmptyData("id", PATH ."index");
    }

    public function commonFormValidatorForDeactivateIndex(){
        \FormValidation::checkValidID("id", $this->domain_access->isDomainDataExisted("id"));  
        \FormValidation::checkValidID("id", $this->tag_access->isTagDataExisted(intval($_POST["id"])) || $this->tag_access->isTagDataWithRangeExisted(intval($_POST["id"])));  
        \FormValidation::checkEmptyData("referrer", PATH . "index");
    }

    public function commonFormValidatorForDeactivateShow(){
        \FormValidation::checkValidID("id", $this->tag_access->isValidTagID(intval($_POST["id"])) || $this->tag_access->isValidTagIDWithRange(intval($_POST["id"])));     
        \FormValidation::checkEmptyData("referrer", PATH . "index");
    }

}
