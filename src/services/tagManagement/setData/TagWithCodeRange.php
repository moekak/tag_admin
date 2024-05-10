<?php

namespace SetData;

require_once(dirname(__FILE__) . "/SetDataBase.php");
require_once(dirname(__FILE__) . "/../../../utils/DataValidation.php");



class TagWithCodeRange extends SetDataBase{
    public function setVariable(): array{

        $tag_info = [
            "domain_id"             => intval($_POST["domain_id"]),
            "tag_head"              => $_POST["tag_head"] ? $this->changeToJsonData($_POST["tag_head"]) : '[]',
            "tag_body"              => $_POST["tag_body"] ? $this->changeToJsonData($_POST["tag_body"]): '[]',
            "tag_code"              => $this->generateAdCode(isset($_POST["ad_code"]) ? \DataValidation::sanitizeInput($_POST["ad_code"]) : ""),
            "trigger"               => \DataValidation::sanitizeInput($_POST["trigger_category"]),
            "parent_tag_id"         => null

        ];

        return $tag_info;
    }

    public function setVariableForDeactivate(){
        $domain_model = new \DomainsModel();
        $tagData    = $domain_model->getTagDataWithRangeAndDomain($_SESSION["user_id"], intval($_POST["id"]));


        $tag_info = [
            "domain_id"             => $tagData["domain_id"],
            "tag_head"              => $tagData["tag_head"],
            "tag_body"              => $tagData["tag_body"],
            "tag_code"              => $tagData["code_range"],
            "excluded_code"         => $tagData["excluded_code"],
            "trigger"               => $tagData["trigger_type"],
            "parent_tag_id"         => null

        ];

        return $tag_info;
    }


    public function generateAdCode($code, $num = ""){
        $codeArray  = [];
        $adNumArray = \DataValidation::generateNumberRange($_POST["ad_num"], $_POST["ad_range"]);

        for($i = 0; $i <= count($adNumArray) -1; $i ++){
            $codeArray[] =  $code !== "" ? $code . $adNumArray[$i] : $adNumArray[$i];
        }
        return json_encode($codeArray, true);
    }

}








   