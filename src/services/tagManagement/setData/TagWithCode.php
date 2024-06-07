<?php

namespace SetData;


require_once(dirname(__FILE__) . "/SetDataBase.php");
require_once(dirname(__FILE__) . "/../../../utils/DataValidation.php");
require_once(dirname(__FILE__) . "/../../../models/DomainsModel.php");



class TagWithCode extends SetDataBase{

    // 値を配列にセット
    public function setVariable(): array{

        $tag_info = [
            "domain_id"             => intval($_POST["domain_id"]),
            "tag_head"              => $_POST["tag_head"] ? $this->changeToJsonData($_POST["tag_head"]) : '[]',
            "tag_body"              => $_POST["tag_body"] ? $this->changeToJsonData($_POST["tag_body"]): '[]',
            "tag_code"              => $this->generateAdCode(isset($_POST["ad_code"]) ?\DataValidation::sanitizeInput($_POST["ad_code"]) : "", isset($_POST["ad_num"]) ? \DataValidation::sanitizeInput($_POST["ad_num"]) : ""),
            "trigger"               => \DataValidation::sanitizeInput($_POST["trigger_category"]),
            "parent_tag_id"         => null

        ];

        return $tag_info;
    }

    public function setVariableForDeactivate()
    {
        $domain_model   = new \DomainsModel();
        $user_id = isset($_SESSION["admin_id"]) ? $_SESSION["admin_id"] : $_SESSION["user_id"];
        $tagData        = $domain_model->getTagDataAndDomain($user_id, intval($_POST["id"]));

    
        $tag_info = [
            "domain_id"             => intval($tagData["domain_id"]),
            "tag_head"              => $tagData["tag_head"],
            "tag_body"              => $tagData["tag_body"],
            "tag_code"              => $tagData["ad_code"],
            "trigger"               => $tagData["trigger_type"],
            "parent_tag_id"         => null
        ];
        

        return $tag_info;
    }

    // 広告コードを作成
    public function generateAdCode($code, $num){
        if($code !== "" && $num !== ""){
            return $code . $num;
        } else if($code == "" && $num !== ""){
            return $num;
        }else if($code !== "" && $num == ""){
            return $code;
        }
        
    }

}








   