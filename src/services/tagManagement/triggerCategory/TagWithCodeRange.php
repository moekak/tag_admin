<?php
namespace TriggerCategory;

require_once(dirname(__FILE__) . "/../../../utils/SystemFeedback.php");
require_once(dirname(__FILE__) . "/../../../services/tagManagement/TagDataAccess.php");
require_once(dirname(__FILE__) . "/Base.php");
require_once(dirname(__FILE__) . "/../../../config/conf.php");


class TagWithCodeRange extends Base{
    public $tag_access;

    public function __construct(){
        $this->tag_access       = new \TagDataAccess();
    }

    // タグがすでに存在してるか確認
    public function isTagDataExisted($adCode){
        return $this->tag_access->checkExistedData($adCode, $_POST["trigger_category"], intval($_POST["domain_id"]));
    }

    // データベースに値を保存する
    public function insertDataToDb($data){
        // 既にタグが登録されていた場合はデータベースのデータを更新する
        if($this->isTagDataExisted($data["tag_code"])){
            if($_POST["tag_head"]){
                $this->tag_access->updateTagHeadWithAdCodeRange($data["domain_id"], $data["tag_code"],$data["tag_head"], $data["trigger"]);
            }
            if($_POST["tag_body"]){
            
                $this->tag_access->updateTagBodyWithAdCodeRange($data["domain_id"], $data["tag_code"],$data["tag_body"], $data["trigger"]);
            }
        // まだ登録されてなかった場合は追加する
        }else{
            $this->tag_access->insertTagDataWithRangeToDB($data["domain_id"], $data["tag_head"], $data["tag_body"], $data["tag_code"], $data["trigger"], $data["parent_tag_id"], "[]");
        }
    }

    public function updateTagDataDB($data){
        // タグheadの編集があった場合、データベースを更新する
        if($data["tag_head"]){
            $this->tag_access->updateTagHeadWithRangeForEdit(intval($_POST["domain_id"]), intval($_POST["tag_id"]), $data["tag_head"]);
        }
        // タグbodyの編集があった場合、データベースを更新する
        if($data["tag_body"]){
            $this->tag_access->updateTagBodyWithRangeForEdit(intval($_POST["domain_id"]), intval($_POST["tag_id"]), $data["tag_body"]);
        }
        // 除外コードが送信された場合、除外コードをjsonの形式の配列の形にしてデータベースに保存する
        if(isset($_POST["excludedCode"])){
            $this->tag_access->insertExcludedCode(intval($_POST["domain_id"]), intval($_POST["tag_id"]), json_encode($_POST["excludedCode"], true));
        // 除外コードがなかったら、空のjson型配列にexcluded_codeカラムを更新する
        } else{
            $this->tag_access->deleteExcludedCode($_POST["domain_id"], intval($_POST["tag_id"])); 
        }
    }

    public function submissionProcessForAdd($data){
        // データベースに値を保存
        $this->insertDataToDb($data);
        // ページ遷移させて、成功メッセージを出す
        // \SystemFeedback::showSuccessMsg(SUCCESS_ADD_TAG, $_POST["referrer"], "tagShow");
    }

    public function submissionProcessForEdit($data){
        // データベースの値を更新
        $this->updateTagDataDB($data);
        // ページ遷移させて、成功メッセージを出す
        // \SystemFeedback::showSuccessMsg(SUCCESS_UPDATE_TAG, "/tag_admin/showTag/?id=" .$_POST["domain_id"],  "tagShow");
    }

    public function deactivateTagForShow(){
        $this->tag_access->deactivateTagDataWithRangeIndividually(intval($_POST["id"]));
        // \SystemFeedback::showSuccessMsg(SUCCESS_DEACTIVATE_TAG, $_POST["referrer"], "tagShow");
    }
}

