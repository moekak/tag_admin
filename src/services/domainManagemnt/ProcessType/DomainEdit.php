<?php


require_once(dirname(__FILE__) . "/../../../utils/SystemFeedback.php");
require_once(dirname(__FILE__) . "/../../../config/conf.php");
require_once(dirname(__FILE__) . "/../../../utils/FormValidation.php");
require_once(dirname(__FILE__) . "/../ProcessType/DomainProcessBase.php");
require_once(dirname(__FILE__) . "/../../domainManagemnt/domainType/DomainClassInstanceMap.php");


// ドメイン処理共通(追加と編集)

class DomainEdit implements DomainProcessBase{
    public $property_domain;
    public $map;
    public $original_domain;
    public $copy_domain;
    public $instancce;
    protected $domain_access;
    protected $allowedDomainTypes = ["original", "copy", "directory"];

    public function __construct($property_domain, $property_tag){
        $this->domain_access = new DomainDataAccess();
        $this->instancce     = new DomainClassInstanceMap($property_domain, $property_tag);
    }

 
    public function formValidator(){
        FormValidation::checkCSRF();
        FormValidation::checkEmptyData("domain_category", PATH . "index");
        FormValidation::checkValidID("domain_category", $this->domain_access->isCategoryIDExisted());
        FormValidation::checkValidData($this->allowedDomainTypes, "domain_type");
        if($_POST["domain_type"] !== "directory"){
            FormValidation::isDomainExisted(intval($_POST["domain_id"]), "edit");
        }

    }

    public function submissionProcess(){
        $this->formValidator();
        // print_r($_POST);
        // exit;
        $this->instancce->getInstance()->submissionProcessWithEdit();

        SystemFeedback::showSuccessMsg(SUCCESS_UPDATE_DOMAIN, PATH . "index", "index");
        return;
    }

}

