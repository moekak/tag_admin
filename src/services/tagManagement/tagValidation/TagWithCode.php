<?php

namespace TagValidation;

require_once(dirname(__FILE__) . "/../tagValidation/TagValidationBase.php");
require_once(dirname(__FILE__) . "/../../../services/tagManagement/TagDataAccess.php");
require_once(dirname(__FILE__) . "/../../../utils/DataValidation.php");
require_once(dirname(__FILE__) . "/../../tagManagement/TagValidation.php");
require_once(dirname(__FILE__) . "/../../../utils/FormValidation.php");


class TagWithCode extends TagValidationBase{
    public $tag_access;

    public function __construct(){
        $this->tag_access = new \TagDataAccess();
        parent::__construct();
    }

    // バリデーションチェック
    public function individualFormValidator(){
        \FormValidation::checkAllNecessaryValues(\TagValidation::hasAllNecessaryValuesForTriggerWithAdCode(), $_POST["referrer"], "");
        if(isset($_POST["ad_code"]) && $_POST["ad_code"] !== ""){
            \DataValidation::isHalfWidthChars($_POST["ad_code"], "ad_code");
        }
        if(isset($_POST["ad_num"]) && $_POST["ad_num"] !== ""){
            \DataValidation::isHalfWidthNumber($_POST["ad_num"], "ad_num"); 
        }
        
        
    }

    public function formValidatorForEdit(){
        \FormValidation::checkEmptyData("tag_id", PATH . "tag/?id=" .$_POST["tag_id"]);
        \FormValidation::checkValidID("tag_id", $this->tag_access->isValidTagID(intval($_POST["tag_id"])));
    }
}





