<?php
require_once(dirname(__FILE__) . "/../../models/DomainsModel.php");

class DomainValidation{

    //  ドメインがすでに登録されてるかのチェック
    public function isDomainNameAlreadyExisted($domain_id){
        $user_id = isset($_SESSION["admin_id"]) ? $_SESSION["admin_id"] : $_SESSION["user_id"];
        $domain_model  = new DomainsModel();
        return $domain_model->getDomainName(DataValidation::sanitizeInput($_POST["domain_name"]), $user_id, $domain_id);
    }
    
    // ##############################################################################
    // ドメインタイプがオリジナルの場合
    // ##############################################################################
    public static function hasAllNecessaryValuesForOriginalSite(){
        $requiredKeys = ["domain_name", "domain_category", "domain_type"];
        return DataValidation::hasAllNecessaryValues($requiredKeys, null);
    }

    // ##############################################################################
    // ドメインタイプがコピーサイトまたはディレクトリ別の場合
    // ##############################################################################
    public static function hasAllNecessaryValuesForCopyOrDirectorySite(){
        $requiredKeys = ["domain_name", "domain_category", "domain_type", "tag_type"];
        return DataValidation::hasAllNecessaryValues($requiredKeys, null);
     }

    // ##############################################################################
    // タグを参照する場合
    // ##############################################################################
     public static function hasAllNecessaryValuesForCopySiteWhenReferenceTag(){
        $requiredKeys = ["domain_name", "domain_category", "domain_type", "tag_type", "parent_tag_id"];
        return DataValidation::hasAllNecessaryValues($requiredKeys, null);
     }




}