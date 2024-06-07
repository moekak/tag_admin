<?php
require_once(dirname(__FILE__) . "/../../models/TagsModel.php");
require_once(dirname(__FILE__) . "/../../models/TagsRangeModel.php");



class TagDataAccess{
    public $tag_model;
    public $tagRange_model;
    public $user_id;

    public function __construct(){
        $this->tag_model            = new TagsModel();
        $this->tagRange_model       = new TagsRangeModel();
        $this->user_id = isset($_SESSION["admin_id"]) ? $_SESSION["admin_id"] : $_SESSION["user_id"];
    }


      // タグ,コード情報を取得
    public function getTagsInfo($id){
        return $this->tag_model->getAdCodeAndTags($this->user_id, $id);
    }
      // タグ,コード情報を取得(範囲指定あり)
    public function getTagsRangeInfo($id){
        return $this->tagRange_model->getAdCodeAndTagsWithRange($this->user_id, $id);
    }
    public function getTagsInfoWithParentTagId($id){
        return $this->tag_model->getAdCodeAndTagsWithParentTag($this->user_id, $id);
    }
      // タグ,コード情報を取得(範囲指定あり)
    public function getTagsRangeInfoWithParentTagId($id){
        return $this->tagRange_model->getAdCodeAndTagsWithRangeAndParentTag($this->user_id, $id);
    }
      // タグ情報を取得（配信トリガーがallの場合）
    public function getTagsDataWhenTriggerAll($id, $trigger){
        return $this->tag_model->getTagsWhenTriggerAll($this->user_id, $id, $trigger);
    }
      // タグ情報を取得(広告コード指定ありの場合)
    public function getTagsDataWithAdCode($id, $code, $trigger){
        return $this->tag_model->getTagsWithAdCode($this->user_id, $id, $code, $trigger);
    }

    // タグ情報をデータベースに保存
    public function InsertTagDataToFile($tagHeadData, $tagBodyData, $tag_code, $trigger,  $domain_id, $parent_tag_id){
        $this->tag_model->insertTagInfo($domain_id, $this->user_id, $tagHeadData, $tagBodyData, $tag_code, $trigger, $parent_tag_id); 
    }
    // タグ情報(範囲指定あり)をデータベースに保存
    public function insertTagDataWithRangeToDB($domain_id, $tag_head, $tag_body, $code_range, $trigger, $parent_tag_id, $excluded_code){
        $this->tagRange_model->insertTagInfo($domain_id, $this->user_id, $tag_head, $tag_body, $code_range, $trigger, $parent_tag_id, $excluded_code); 
    }
    // 範囲指定でタグが存在するか確認
    public function checkExistedData($code_range, $trigger_type, $domain_id){
        return $this->tagRange_model->checkExistedData($this->user_id, $code_range, $trigger_type, $domain_id);
    }
    
    // タグhead情報を更新
    public function updateTagHead($domain_id, $trigger, $tag_head){
        $this->tag_model->updateTagHead($this->user_id, $domain_id, $trigger, $tag_head);
       
    }
    // タグbody情報を更新
    public function updateTagBody($domain_id, $trigger, $tag_body){
        $this->tag_model->updateTagBody($this->user_id, $domain_id, $trigger, $tag_body);
    }
       
    // タグhead情報を更新
    public function updateTagHeadWithAdCode($domain_id, $code, $tag_head, $trigger){
        $this->tag_model->updateTagHeadWithAdCode($this->user_id, $domain_id, $code, $tag_head, $trigger);
       
    }
    // タグbody情報を更新
    public function updateTagBodyWithAdCode($domain_id, $code, $tag_body, $trigger){
        $this->tag_model->updateTagBodyWithAdCode($this->user_id, $domain_id, $code, $tag_body, $trigger);
       
    }
    // タグhead情報（範囲指定あり）を更新
    public function updateTagHeadWithAdCodeRange($domain_id, $code, $tag_head, $trigger){
        $this->tagRange_model->updateTagHeadWithAdCode($this->user_id, $domain_id, $code, $tag_head, $trigger);
       
    }
    // タグbody情報（範囲指定あり）を更新
    public function updateTagBodyWithAdCodeRange($domain_id, $code, $tag_body, $trigger){
        $this->tagRange_model->updateTagBodyWithAdCode($this->user_id, $domain_id, $code, $tag_body, $trigger);
       
    }

    // タグ削除(ドメインに紐づいてるタグをすべて削除する場合)
    // (タグ参照や、コピーサイトからオリジナルに変更するときのみ使用)
    // ドメイン追加、編集画処理のとき
    public function deleteTagData(){
        $this->tag_model->deleteTagInfo($_POST["domain_id"], $this->user_id);
    }
   

    // タグ削除(ドメインに紐づいてるタグをすべて削除する場合)
    // (タグ参照や、コピーサイトからオリジナルに変更するときのみ使用)
    // 範囲指定あり
    public function deleteTagDataWithRange(){
        $this->tagRange_model->deleteTagInfoWithRange($_POST["domain_id"], $this->user_id);
    }



    // タグのデータが入ってるか(boolen型で返す)
    public function isTagDataExisted($key){
        return $this->tag_model->checkTagID($this->user_id, $key);
    }
    // タグのデータが入ってるか(boolen型で返す)
    public function isTagDataWithRangeExisted($key){
        return $this->tagRange_model->checkTagActive($this->user_id, $key);
    }

    // タグのIDが存在するか(boolen型で返す)
    public function isValidTagID($key){
        return $this->tag_model->checkValidTagID($this->user_id, $key);
    }
    // タグのIDが存在するか(boolen型で返す)(範囲指定あり)
    public function isValidTagIDWithRange($key){
        return $this->tagRange_model->checkValidTagIDWithRange($this->user_id, $key);
    }

    // タグ削除(ドメインに紐づいてるタグすべて削除する場合)
    public function deactivateTag($key){
        $this->tag_model->deactivateTag($this->user_id, $key);
    }

     // タグ削除(広告コードごとに分けて削除する場合)
     public function deactivateTagDataForCode($key){
        $this->tag_model->deactivateTagForCode($this->user_id, $key);
    }

     // タグ削除(範囲指定ありの場合)
    public function deactivateTagDataWithRange($key){
        $this->tagRange_model->deactivateTag($this->user_id, $key);
    }

       // タグ削除(範囲指定ありの場合)
       public function deactivateTagDataWithRangeIndividually($key){
        $this->tagRange_model->deactivateIndividualTag($this->user_id, $key);
    }

    // 範囲指定なし
    // 編集の際の更新
    public function updateTagDataForEdit($domain_id, $id, $parent_tag_id, $ad_code, $trigger_type){
        $this->tag_model->updateTagData($this->user_id, $domain_id, $id, $parent_tag_id, $ad_code, $trigger_type);
    }
    // 編集の際の更新(tag head)
    public function updateTagHeadForEdit($domain_id, $id, $tag_head){
        $this->tag_model->updateTagHeadForEdit($this->user_id, $domain_id, $id, $tag_head);
    }
    // 編集の際の更新(tag body)
    public function updateTagBodyForEdit($domain_id, $id, $tag_body){
        $this->tag_model->updateTagBodyForEdit($this->user_id, $domain_id, $id, $tag_body);
    }

    // 範囲指定あり
    // 編集の際の更新
    public function updateTagDataWithRangeForEdit($domain_id, $id, $parent_tag_id, $code_range, $trigger_type){
        $this->tagRange_model->updateTagData($this->user_id, $domain_id, $id, $parent_tag_id, $code_range, $trigger_type);
    }
    // 編集の際の更新(tag head)
    public function updateTagHeadWithRangeForEdit($domain_id, $id, $tag_head){
        $this->tagRange_model->updateTagHeadForEdit($this->user_id, $domain_id, $id, $tag_head);
    }
    // 編集の際の更新(tag body)
    public function updateTagBodyWithRangeForEdit($domain_id, $id, $tag_body){
        $this->tagRange_model->updateTagBodyForEdit($this->user_id, $domain_id, $id, $tag_body);
    }

    public function insertExcludedCode($domain_id, $id, $code){
        $this->tagRange_model->insertExcludedCode($domain_id, $this->user_id, $id, $code);
    }

    public function getExcludedCode($domain_id, $id){
        return $this->tagRange_model->getExcludedCode($this->user_id, $domain_id, $id);
    }

    public function deleteExcludedCode($domain_id,$id){
        $this->tagRange_model->DeleteExcludedCode($domain_id, $this->user_id, $id);
    }
}