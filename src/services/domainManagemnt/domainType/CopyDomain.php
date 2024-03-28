<?php

use Random\Engine\Secure;
require_once(dirname(__FILE__) . "/../../../utils/FormValidation.php");
require_once(dirname(__FILE__) . "/../../../services/domainManagemnt/DomainDataAccess.php");
require_once(dirname(__FILE__) . "/../../../services/domainManagemnt/tagType/TagClassInstanceMap.php");
require_once(dirname(__FILE__) . "/../../../services/domainManagemnt/domainType/DomainBase.php");



// ドメイン処理共通(追加と編集)

class CopyDomain implements DomainBase{
    protected $domain_access;
    public $instancce;
    protected $allowedTagType = ["reference", "new", "withoutTag"];
    public $property;
  

    public function __construct($property_tag){
        $this->property      = $property_tag;
        $this->domain_access = new DomainDataAccess();
        $this->instancce     = new TagClassInstanceMap($property_tag);
    }

  

    public function domainFormValidator(){
        //  parent_domain_idがあるかチェック（有効なIDか後ほどyチェックするためここで空かチェックしてる）
        FormValidation::checkEmptyData("parent_domain_id");
        FormValidation::checkEmptyData("original_parent_id");
        // 不正データ処理
        FormValidation::checkValidData($this->allowedTagType, "tag_type");
        // 不正ドメインIDかの検証(このIDでドメインテーブルにデータが保存されてるか)
        FormValidation::checkValidID("parent_domain_id", $this->domain_access->isDomainDataExisted("parent_domain_id"));
        FormValidation::checkValidID("original_parent_id", $this->domain_access->isDomainDataExisted("original_parent_id"));
    }


    // 追加時の処理
    public function submissionProcessWithAdd(){
        $this->domainFormValidator();
        $this->instancce->getInstance()->operateDatabaseWithAdd();

    }

    // 編集時の処理
    public function submissionProcessWithEdit(){
        $this->domainFormValidator();
        $this->instancce->getInstance()->operateDatabaseWithEdit();
    }

}

