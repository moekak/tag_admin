<?php
require_once(dirname(__FILE__) . "/SystemFeedback.php");
require_once(dirname(__FILE__) . "/Security.php");
require_once(dirname(__FILE__) . "/DataValidation.php");
require_once(dirname(__FILE__) . "/../services/domainManagemnt/DomainValidation.php");
require_once(dirname(__FILE__) . "/../models/DomainTypesModel.php");

class FormValidation{

    // CSRFトークンの検証
    public static function checkCSRF(){
        if(!Security::checkCSRF()){
            SystemFeedback::csfrError();
            exit;
        }
    }

    // 主要なデータが入ってるかチェック
    public static function checkEmptyData($key){
        if(empty($_POST[$key])){
            SystemFeedback::invalidIDError($key);
            exit;
        }
    }

    // 不正なデータかチェック
    public static function checkValidData($allowedTypes, $key){
        if(!in_array($_POST[$key], $allowedTypes) || !isset($_POST[$key])){
            SystemFeedback::invalidDataError($key);
            exit;
        }
    }

     // 必要なデータがすべてあるかチェック
     public static function checkAllNecessaryValues($fn, $value){
        if($fn !== []){
            SystemFeedback::missingDataError($fn, $value);
            exit;
        }
    }

     // 不正IDかの検証
    public static function checkValidID($key, $fn){
        if(!DataValidation::isValidID($_POST[$key]) || !$fn){
            SystemFeedback::invalidIDError($key);
            exit;;
        }
    }


    // すでにドメインが登録されてるかチェック
    public static function isDomainExisted($value, $error){
        $domain_validation = new DomainValidation();
        if($domain_validation->isDomainNameAlreadyExisted($value)){
            $_SESSION["error_code"] = $error;
            SystemFeedback::redirectToIndexWithError(DOMAIN_ALREADY_EXISTED, PATH . "index");
            
            return;
        }
    }

}