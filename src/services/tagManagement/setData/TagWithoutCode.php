<?php
namespace SetData;

require_once(dirname(__FILE__) . "/SetDataBase.php");
require_once(dirname(__FILE__) . "/../../../utils/DataValidation.php");



class TagWithoutCode extends SetDataBase{

    // 値を配列にセット
    public function setVariable(): array{

        $tag_info = [
            "domain_id"             => intval($_POST["domain_id"]),
            "tag_head"              => $_POST["tag_head"] ? $this->changeToJsonData($_POST["tag_head"]): '[]',
            "tag_body"              => $_POST["tag_body"] ? $this->changeToJsonData($_POST["tag_body"]) : '[]',
            "tag_code"              =>  "",
            "trigger"               => ($_POST["trigger_category"] !== "all_pages" && $_POST["trigger_category"] !== "all_index" && $_POST["trigger_category"] !== "all_rd" ? "all_" . \DataValidation::sanitizeInput($_POST["trigger_category"]) :\DataValidation::sanitizeInput($_POST["trigger_category"])),
            "parent_tag_id"         => null

        ];

        return $tag_info;
    }

    public function setVariableForDeactivate(){
        $domain_model   = new \DomainsModel();
        $tagData        = $domain_model->getTagDataAndDomain($_SESSION["user_id"], intval($_POST["id"]));

        $tag_info = [
            "domain_id"             => $tagData["domain_id"],
            "tag_head"              => $tagData["tag_head"],
            "tag_body"              => $tagData["tag_body"],
            "tag_code"              =>  "",
            "trigger"               => $tagData["trigger_type"],
            "parent_tag_id"         => null

        ];

        return $tag_info;
    }

    // 広告コード作成いらないので空にする
    public function generateAdCode($code, $num){}

}







   