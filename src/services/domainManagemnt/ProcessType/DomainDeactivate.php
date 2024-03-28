<?php

require_once dirname(__FILE__) . "/../../../models/DomainsModel.php";
require_once dirname(__FILE__) . "/../../../utils/FormValidation.php";
require_once dirname(__FILE__) . "/../../../services/domainManagemnt/DomainDataAccess.php";
require_once dirname(__FILE__) . "/../../../utils/DatabaseConnection.php";
require_once dirname(__FILE__) . "/../../../utils/SystemFeedback.php";
require_once dirname(__FILE__) . "/../../../services/tagOperations/InsertTagDataToFile.php";

class DomainDeactivate implements DomainProcessBase
{
    public $domain_model;
    public $domain_access;
    public $allowedStatuses = ["使用しない", "使用する"];
    public $pdo;

    public function __construct()
    {
        $this->domain_model = new DomainsModel();
        $this->domain_access = new DomainDataAccess();
        $dbConnection = DatabaseConnection::getInstance();
        // PDOインスタンス（データベース接続）を取得
        $this->pdo = $dbConnection->getConnection();
    }

    public function formValidator()
    {
        FormValidation::checkCSRF();
        FormValidation::checkEmptyData("domain_id");
        FormValidation::checkEmptyData("status");
        FormValidation::checkValidID("domain_id", $this->domain_access->isDomainDataExisted("domain_id"));
        FormValidation::checkValidData($this->allowedStatuses, "status");
    }

    public function submissionProcess()
    {
        $this->formValidator();

        // もし何も不正がなかったら走る処理
        if ($_POST["status"] === "使用しない") {
            try {
                // トランザクション開始
                $this->pdo->beginTransaction();
                // データベースの操作
                $this->domain_model->deactivateDomain($_SESSION["user_id"], intval($_POST["domain_id"]));



                $this->pdo->commit();

            } catch (Exception $e) {
                // エラーが発生した場合、トランザクションをロールバック
                $this->pdo->rollBack();
                SystemFeedback::transactionError($e->getMessage());
            }

        } else if ($_POST["status"] === "使用する") {
            try {
                // トランザクション開始
                $this->pdo->beginTransaction();
                // データベースの操作
                $this->domain_model->activateDomain($_SESSION["user_id"], intval($_POST["domain_id"]));

                // フォルダの権限をすべて無効にする
                // $fileOperation = new InsertTagDataToFile();
                // $fileOperation->enableFolderAccess($_POST["domain_id"]);

                $this->pdo->commit();

            } catch (Exception $e) {
                // エラーが発生した場合、トランザクションをロールバック
                $this->pdo->rollBack();
                SystemFeedback::transactionError($e->getMessage());
            }
        }
        SystemFeedback::showSuccessMsg(SUCCESS_DEACTIVATE_DOMAIN, PATH ."index", "index");
    }

}