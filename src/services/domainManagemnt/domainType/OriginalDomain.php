<?php

use Random\Engine\Secure;


require_once(dirname(__FILE__) . "/../../../utils/FormValidation.php");
require_once(dirname(__FILE__) . "/../../../utils/DataValidation.php");
require_once(dirname(__FILE__) . "/../../../services/domainManagemnt/DomainDataAccess.php");
require_once(dirname(__FILE__) . "/../../../services/tagManagement/TagDataAccess.php");
require_once(dirname(__FILE__) . "/../../../services/domainManagemnt/domainType/DomainBase.php");
require_once(dirname(__FILE__) . "/../../../utils/Common.php");
require_once(dirname(__FILE__) . "/../../../services/domainManagemnt/tagType/TagClassInstanceMap.php");




// ドメイン処理共通(追加と編集)

class OriginalDomain implements DomainBase{
    protected $domain_access;
    protected $tag_access;
    protected $allowedDomainTypes = ["original", "copy", "directory"];
    protected $allowedTagType = ["reference", "new", "withoutTag"];
    public $instancce;
    public $property;


    public function __construct($property_tag){
        $this->property      = $property_tag;
        $this->domain_access = new DomainDataAccess();
        $this->tag_access    = new TagDataAccess();
        $this->instancce     = new TagClassInstanceMap($property_tag);
    }


    public function  domainFormValidator(){
        FormValidation::checkValidData($this->allowedTagType, "tag_type");
    }
    public function submissionProcessWithAdd(){
        $this->domainFormValidator();
        //  必要なデータがすべてあった場合、データーベースに保存する
        $this->instancce->getInstance()->operateDatabaseWithAdd();
    }

    // 編集時の処理
    public function submissionProcessWithEdit(){
        $this->domainFormValidator();
        $this->instancce->getInstance()->operateDatabaseWithEdit();
    }
}

