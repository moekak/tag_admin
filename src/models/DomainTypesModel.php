<?php

require_once(dirname(__FILE__) . "/../utils/DatabaseConnection.php");
require_once(dirname(__FILE__) . "/../utils/SystemFeedback.php");
require_once(dirname(__FILE__) . "/../config/conf.php");



class DomainTypesModel{
    public $pdo;
    public $feedback;
    public function __construct(){
        $dbConnection = DatabaseConnection::getInstance();
        // PDOインスタンス（データベース接続）を取得
        $this->pdo = $dbConnection->getConnection();
        $this->feedback = new SystemFeedback();
    }

    public function getDomainType($admin_id){
        try{
            $statement = $this->pdo->prepare("SELECT * FROM `domain_types` WHERE admin_id = :admin_id");
            $statement->bindValue(':admin_id', $admin_id);
            $statement->execute();
            return $statement->fetchAll(PDO::FETCH_ASSOC);

        }catch(PDOException $e){
            $this->feedback->logError($e->getMessage());
            SystemFeedback::redirectToSystemErrorPage(ERROR_TEXT, ERROR_CODE_LOGIN);
            exit;
        }
    }

    public function checkCategoryID($admin_id, $category_id){
        try{
            $statement = $this->pdo->prepare("SELECT id FROM `domain_types` WHERE admin_id = :admin_id AND id = :id");
            $statement->bindValue(':admin_id', $admin_id);
            $statement->bindValue(':id', $category_id);
            $statement->execute();
            return $statement->fetchColumn();

        }catch(PDOException $e){
            $this->feedback->logError($e->getMessage());
            SystemFeedback::redirectToSystemErrorPage(ERROR_TEXT, ERROR_CODE_LOGIN);
            exit;
        }
    }  
    public function insertCategory($admin_id, $name){
        try{
            $statement = $this->pdo->prepare("INSERT INTO `domain_types` (`domain_type_name`, `admin_id`) VALUES (:domain_type_name, :admin_id)");
            $statement->bindValue(':admin_id', $admin_id);
            $statement->bindValue(':domain_type_name', $name);
            $statement->execute();

        }catch(PDOException $e){
            $this->feedback->logError($e->getMessage());
            SystemFeedback::redirectToSystemErrorPage(ERROR_TEXT, ERROR_CODE_LOGIN);
            exit;
        }
    }  
    public function updateCategory($admin_id, $name, $category_id){
        try{
            $statement = $this->pdo->prepare("UPDATE domain_types SET domain_type_name = :domain_type_name WHERE id = :id AND admin_id = :admin_id");
            $statement->bindValue(':admin_id', $admin_id);
            $statement->bindValue(':id', $category_id);
            $statement->bindValue(':domain_type_name', $name);
            $statement->execute();

        }catch(PDOException $e){
            $this->feedback->logError($e->getMessage());
            SystemFeedback::redirectToSystemErrorPage(ERROR_TEXT, ERROR_CODE_LOGIN);
            exit;
        }
    }  



}