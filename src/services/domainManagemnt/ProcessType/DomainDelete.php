<?php


require_once(dirname(__FILE__) . "/../../../utils/SystemFeedback.php");
require_once(dirname(__FILE__) . "/../../../config/conf.php");
require_once(dirname(__FILE__) . "/../../../utils/FormValidation.php");
require_once(dirname(__FILE__) . "/../ProcessType/DomainProcessBase.php");
require_once(dirname(__FILE__) . "/../../../services/domainManagemnt/DomainDataAccess.php");
require_once(dirname(__FILE__) . "/../../../services/tagManagement/TagDataAccess.php");
require_once(dirname(__FILE__) . "/../../../utils/DatabaseConnection.php");
require_once(dirname(__FILE__) . "/../../../services/tagOperations/InsertTagDataToFile.php");



class DomainDelete implements DomainProcessBase{
    protected $domain_access;
    protected $tag_access;
    public $pdo;


    public function __construct(){
        $this->domain_access = new DomainDataAccess();
        $this->tag_access = new TagDataAccess();
        $dbConnection = DatabaseConnection::getInstance();
        // PDOインスタンス（データベース接続）を取得
        $this->pdo = $dbConnection->getConnection();
    }

    public function formValidator(){
        FormValidation::checkCSRF();
        FormValidation::checkEmptyData("domain_id", PATH . "index");
        FormValidation::checkValidID("domain_id", $this->domain_access->isDomainDataExisted("domain_id"));
    }

    public function submissionProcess(){
        $this->formValidator();

        try{
            $this->pdo->beginTransaction();

             // タグファイルの操作
             $fileOperation= new InsertTagDataToFile();
             $fileOperation->deleteTagDataFile(intval($_POST["domain_id"]));

            // データベース操作
            $this->domain_access->deleteDomain("domain_id");
            $this->tag_access->deactivateTag(intval($_POST["domain_id"]));
            $this->tag_access->deactivateTagDataWithRange(intval($_POST["domain_id"]));

            $this->pdo->commit();

        }catch(Exception $e){
            // エラーが発生した場合、トランザクションをロールバック
            $this->pdo->rollBack();
            SystemFeedback::transactionError($e->getMessage());

        }

        SystemFeedback::showSuccessMsg(SUCCESS_DELETE_DOMAIN, PATH . "index", "index");

    }

}

