<?php

namespace TagValidation;

require_once(dirname(__FILE__) . "/../tagValidation/TagValidationBase.php");
require_once(dirname(__FILE__) . "/../../../services/tagManagement/TagDataAccess.php");
require_once(dirname(__FILE__) . "/../../../utils/DataValidation.php");
require_once(dirname(__FILE__) . "/../../tagManagement/TagValidation.php");
require_once(dirname(__FILE__) . "/../../../utils/FormValidation.php");


class TagWithCodeRange extends TagValidationBase{
    public $tag_access;

    public function __construct(){
        $this->tag_access = new \TagDataAccess();
        parent::__construct();
    }

    // バリデーションチェック
    public function individualFormValidator(){
        \FormValidation::checkAllNecessaryValues(\TagValidation::hasAllNecessaryValuesForTriggerwithAdCodeAndRange(), $_POST["referrer"]);
        \DataValidation::isHalfWidthChars($_POST["ad_code"], "ad_code");
        \DataValidation::isHalfWidthNumber($_POST["ad_num"], "ad_num");
        \DataValidation::isValidNumRange(100, 1, $_POST["ad_range"], "ad_range");
    }

    public function formValidatorForEdit(){
        \FormValidation::checkEmptyData("tag_id", PATH . "tag/?id=" .$_POST["tag_id"]);
        \FormValidation::checkValidID("tag_id", $this->tag_access->isValidTagIDWithRange(intval($_POST["tag_id"])));
    }

   
}





