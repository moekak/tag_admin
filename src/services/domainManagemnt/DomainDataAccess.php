<?php
require_once(dirname(__FILE__) . "/../../models/DomainsModel.php");
require_once(dirname(__FILE__) . "/../../models/DomainTypesModel.php");
require_once(dirname(__FILE__) . "/../../models/TagsModel.php");
require_once(dirname(__FILE__) . "/../../utils/SystemFeedback.php");
require_once(dirname(__FILE__) . "/../../config/conf.php");
require_once(dirname(__FILE__) . "/../../services/domainManagemnt/DomainValidation.php");
require_once(dirname(__FILE__) . "/../../utils/SystemFeedback.php");


class DomainDataAccess{
    public $domain_validation;
    public $domain_model;
    public $feedback;
    public $types_model;

    public function __construct(){
        $this->domain_validation = new DomainValidation();
        $this->domain_model      = new DomainsModel();
        $this->feedback          = new SystemFeedback();
        $this->types_model       = new DomainTypesModel();
    }


    // ドメイン情報をデータベースに保存
    public function insertDomainDataToDB($data){
        return $this->domain_model->insertDomainInfo($data);
  
    }

    public function getRandomDomainIDForTagReferene($domain_id){
        return $this->domain_model->getDomainRandomId($domain_id, $_SESSION["user_id"]);
    }

    // public function getDirectoryDomainData($parent_domain_id){
    //     return $this->domain_model->getDirectoryDomainData($_SESSION["user_id"], $parent_domain_id);
    // }

    public function hasTagReference($domain_id){
        return $this->domain_model->getTagReferenceId($domain_id, $_SESSION["user_id"]);
    }

    public function hasTagReferenceID($domain_id){
        return $this->domain_model->getTagReferenceId2($domain_id, $_SESSION["user_id"]);
    }

    // ドメイン更新
    public function updateDomainDataToDB($data){
        $this->domain_model->updateDomainInfo($data);
     
    }

    // ドメインのデータが入ってるか(boolen型で返す)
    public function isDomainDataExisted($key){
        return $this->domain_model->checkDomainID($_SESSION["user_id"], intval($_POST[$key]));
    }
    // ドメインのデータが入ってるか(boolen型で返す)
    public function isDomainDataExistedGet($key){
        return $this->domain_model->checkDomainID($_SESSION["user_id"], intval($_GET[$key]));
    }

    // カテゴリーデータがあるか(boolen型で返す)
    public function isCategoryIDExisted(){
        return $this->types_model->checkCategoryID($_SESSION["user_id"], $_POST["domain_category"]);
    }
    // ドメイン削除
    public function deleteDomain($key){
        return $this->domain_model->deleteDomain($_SESSION["user_id"], intval($_POST[$key]));
    }
}