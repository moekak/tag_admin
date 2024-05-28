<?php

require_once(dirname(__FILE__) . "/../../services/tagManagement/triggerCategory/ClassInstanceMapForTrigger.php");
require_once(dirname(__FILE__) . "/../../services/tagManagement/tagFileOperation/ClassInstanceMapForTagFile.php");
require_once(dirname(__FILE__) . "/../../services/tagManagement/setData/ClassInstanceMapForData.php");
require_once(dirname(__FILE__) . "/../../services/tagManagement/tagValidation/ClassInstanceMapForTagValidation.php");
require_once(dirname(__FILE__) . "/../../services/tagOperations/InsertTagDataToFile.php");
require_once(dirname(__FILE__) . "/../../utils/DatabaseConnection.php");
require_once(dirname(__FILE__) . "/../../utils/SystemFeedback.php");



class TagController{

    public $instance_trigger;
    public $instance_tagFile;
    public $instance_data;
    public $instance_validation;
    public $pdo;

    public function __construct(){
        $this->instance_trigger     = new TriggerCategory\ClassInstanceMapForTrigger($_POST["type"]);
        $this->instance_tagFile     = new TagFileOperation\ClassInstanceMapForTagFile($_POST["type"]);
        $this->instance_data        = new SetData\ClassInstanceMapForData($_POST["type"]);
        $this->instance_validation  = new TagValidation\ClassInstanceMapForTagValidation($_POST["type"]);

        $dbConnection = DatabaseConnection::getInstance();
        // PDOインスタンス（データベース接続）を取得
        $this->pdo = $dbConnection->getConnection();
    }

    // タグ作成
    public function create(){
        // バリデーションチェック
        $this->instance_validation->getInstance()->commonFormValidator();
        $this->instance_validation->getInstance()->individualFormValidator();
        try{
            // トランザクション開始
            $this->pdo->beginTransaction();
            // データベースにデータを保存する
            $this->instance_trigger->getInstance()->submissionProcessForAdd($this->instance_data->getInstance()->setVariable()); 
            // ファイルにタグ情報を保存する
             $this->instance_tagFile->getInstance()->insertDataToFile($this->instance_data->getInstance()->setVariable());
             // トランザクションをコミット
            $this->pdo->commit();
            // ページ遷移させて、成功メッセージを出す
            SystemFeedback::showSuccessMsg(SUCCESS_ADD_TAG, $_POST["referrer"], "tagShow");

        }catch(Exception $e){
            // エラーが発生した場合、トランザクションをロールバック
            $this->pdo->rollBack();
            SystemFeedback::transactionError($e->getMessage());
        } 
    }

    public function edit(){
         // バリデーションチェック
         $this->instance_validation->getInstance()->commonFormValidator();
         $this->instance_validation->getInstance()->individualFormValidator();
         $this->instance_validation->getInstance()->formValidatorForEdit();
         // データベースのデータを更新する
         $data = $this->instance_data->getInstance()->setVariable();

         try{
            // トランザクション開始
            $this->pdo->beginTransaction();
            $this->instance_trigger->getInstance()->submissionProcessForEdit($data); 
            // ファイルのデータを更新する
            $this->instance_tagFile->getInstance()->updateTagDataFile($data);
            // トランザクションをコミット
            $this->pdo->commit();
            // ページ遷移させて、成功メッセージを出す
            SystemFeedback::showSuccessMsg(SUCCESS_UPDATE_TAG, PATH . "showTag/?id=" .$_POST["domain_id"],  "tagShow");
         }catch(Exception $e){
            // エラーが発生した場合、トランザクションをロールバック
            $this->pdo->rollBack();
            SystemFeedback::transactionError($e->getMessage());
         }
    }


    // この処理はタグタイプ全て同じ処理
    public function deleteIndex(){
       
        $this->instance_validation->getInstance()->commonFormValidatorForDeactivate();
        $this->instance_validation->getInstance()->commonFormValidatorForDeactivateIndex();

        try{
          
            // トランザクション開始
            $this->pdo->beginTransaction();
            
            $this->instance_trigger->getInstance()->deativateTagForIndex();


            $fileOperation= new InsertTagDataToFile();
            $fileOperation->deleteTagDataFile(intval($_POST["id"]));
            // トランザクションをコミット
            $this->pdo->commit();

            \SystemFeedback::showSuccessMsg(SUCCESS_DEACTIVATE_TAG, PATH ."index", "index");

        }catch(Exception $e){
            // エラーが発生した場合、トランザクションをロールバック
            $this->pdo->rollBack();
            SystemFeedback::transactionError($e->getMessage());
        }
    }

    public function deleteShow(){
        $this->instance_validation->getInstance()->commonFormValidatorForDeactivate();
        $this->instance_validation->getInstance()->commonFormValidatorForDeactivateShow();

        $data = $this->instance_data->getInstance()->setVariableForDeactivate();

        try{
            // トランザクション開始
            $this->pdo->beginTransaction();
            $this->instance_trigger->getInstance()->deactivateTagForShow();
            $this->instance_tagFile->getInstance()->deleteTagFileForShowPage($data);
            // トランザクションをコミット
            $this->pdo->commit();
            SystemFeedback::showSuccessMsg(SUCCESS_DEACTIVATE_TAG, $_POST["referrer"], "tagShow");
        }catch(Exception $e){
            // エラーが発生した場合、トランザクションをロールバック
            $this->pdo->rollBack();
            SystemFeedback::transactionError($e->getMessage());
        }
    }   
}