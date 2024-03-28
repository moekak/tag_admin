<?php
namespace TriggerCategory;

require_once(dirname(__FILE__) . "/../../../utils/SystemFeedback.php");
require_once(dirname(__FILE__) . "/../../../services/tagManagement/TagDataAccess.php");
require_once(dirname(__FILE__) . "/Base.php");
require_once(dirname(__FILE__) . "/../../../config/conf.php");
require_once(dirname(__FILE__) . "/../../../services/tagOperations/InsertTagDataToFile.php");


class TagWithCode extends Base{
    public $tag_access;

    public function __construct(){
        $this->tag_access       = new \TagDataAccess();
    }

    // タグがすでにぞんざいしてるか確認
    public function isTagDataExisted($adCode){
        return $this->tag_access->getTagsDataWithAdCode(intval($_POST["domain_id"]), ($_POST["ad_code"]. $_POST["ad_num"]), $_POST["trigger_category"]);
    }

    // データベースに値を保存する
    public function insertDataToDb($data){
        // 既にタグが登録されていた場合はデータベースのデータを更新する
        if($this->isTagDataExisted("")){
            if($_POST["tag_head"]){
                $this->tag_access->updateTagHeadWithAdCode($data["domain_id"], $data["tag_code"],$data["tag_head"], $data["trigger"]);
            }
            if($_POST["tag_body"]){
            
                $this->tag_access->updateTagBodyWithAdCode($data["domain_id"], $data["tag_code"],$data["tag_body"], $data["trigger"]);
            }
        // まだ登録されてなかった場合は追加する
        }else{
            $this->tag_access->InsertTagDataToFile($data["tag_head"], $data["tag_body"], $data["tag_code"], $data["trigger"], $data["domain_id"], $data["parent_tag_id"]);
        }
    }

    public function updateTagDataDB($data){
        // タグheadの編集があった場合、データベースを更新する
        if($data["tag_head"]){
            $this->tag_access->updateTagHeadForEdit(intval($_POST["domain_id"]), intval($_POST["tag_id"]), $data["tag_head"]);
        }
        // タグbodyの編集があった場合、データベースを更新する

        // print_r($data["tag_body"]);
        // exit;
        if($data["tag_body"]){
            $this->tag_access->updateTagBodyForEdit(intval($_POST["domain_id"]), intval($_POST["tag_id"]), $data["tag_body"]);
        }
    }

    public function submissionProcessForAdd($data){
        // データベースに値を保存
        $this->insertDataToDb($data);
        // // ページ遷移させて、成功メッセージを出す
        // \SystemFeedback::showSuccessMsg(SUCCESS_ADD_TAG, $_POST["referrer"], "tagShow");
    }

    public function submissionProcessForEdit($data){
        // データベースの値を更新
        $this->updateTagDataDB($data);
        // // ページ遷移させて、成功メッセージを出す
        // \SystemFeedback::showSuccessMsg(SUCCESS_UPDATE_TAG, "/tag_admin/showTag/?id=" .$_POST["domain_id"],  "tagShow");
    }

    public function deactivateTagForShow(){
        // データーベースを更新する
        $this->tag_access->deactivateTagDataForCode(intval($_POST["id"]));
    }
}








   