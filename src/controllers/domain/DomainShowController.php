<?php

require_once(dirname(__FILE__) . "/../../models/DomainsModel.php");
require_once(dirname(__FILE__) . "/../../models/TagsRangeModel.php");
require_once(dirname(__FILE__) . "/../../models/TagsModel.php");
require_once(dirname(__FILE__) . "/../../utils/Security.php");
require_once(dirname(__FILE__) . "/../../utils/DataValidation.php");
require_once(dirname(__FILE__) . "/../../utils/SystemFeedback.php");
require_once(dirname(__FILE__) . "/../../../src/services/domainManagemnt/DomainDataAccess.php");



class DomainShowController{
    public $domain_model;
    public $domain_access;
    public $tag_modal;
    public $tagRange_modal;
    public $domain_id;

    public function __construct(){
        $this->domain_model     = new DomainsModel();
        $this->tag_modal        = new TagsModel();
        $this->tagRange_modal   = new TagsRangeModel();
        $this->domain_access    = new DomainDataAccess();
    }

    // ##########################################################
    // get method
    //##########################################################
    public function get(){
        

        // 不正IDまたは値が入ってるかのチェック
        if(!isset($_GET["id"]) || !DataValidation::isValidID($_GET["id"]) || !$this->domain_access->isDomainDataExistedGet("id")){
            SystemFeedback::invalidIDErrorForShowPage("id");
        } 


        $is_reference = $this->domain_model->getTagReferenceId(intval($_GET["id"]), $_SESSION["user_id"]);

        if($is_reference){
            $this->domain_id = $this->domain_model->getDomainID($is_reference, $_SESSION["user_id"]);
        }else{
            $this->domain_id = $_GET["id"];
        }

        $specificTagsData               = $this->tag_modal->getSpecificTagsData($_SESSION["user_id"], intval($this->domain_id));
        $specificTagsDataWithCodeRange  = $this->tagRange_modal->getTagDataWithCodeRange($_SESSION["user_id"], intval($this->domain_id));
        $copySite                       = $this->domain_model->getCopyDomainData($_SESSION["user_id"], intval($_GET["id"]), "copy");
        $directorySite                  = $this->domain_model->getCopyDomainData($_SESSION["user_id"], intval($_GET["id"]), "directory");
        $specificDomainData             = $this->domain_model->getSpecificDomainData($_SESSION["user_id"], intval($_GET["id"]));
        $parent_domain = $this->domain_model->getParentDomainData($_SESSION["user_id"], $specificDomainData["parent_domain_id"]);
        $original_domain = $this->domain_model->getOriginalDomainData($_SESSION["user_id"], $specificDomainData["original_parent_id"]);
        $copySiteAll                    = $this->domain_model->getAllCopyDomainData($_SESSION["user_id"], intval($_GET["id"]), "copy");
        
      
        $_SESSION["domainData"]         = $specificDomainData;
        $_SESSION["tagData"]            = $specificTagsData;
        $_SESSION["tagDataWithRange"]   = $specificTagsDataWithCodeRange;
        $_SESSION["copySites"]          = $copySite;
        $_SESSION["sirectorySites"]     = $directorySite;

        $_SESSION["parent_domain"] = $parent_domain;
        $_SESSION["original_domain"] = $original_domain;

        $_SESSION["copySiteAll"] = $copySiteAll;


        Security::generateCsrfToken();
     
        require __DIR__ . '/../../../src/views/domain_showTag.php';
    
    }

    public function error(){
        SystemFeedback::invalidIDErrorForShowPage("id");
    }
}
