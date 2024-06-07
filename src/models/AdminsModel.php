<?php

require_once(dirname(__FILE__) . "/../utils/DatabaseConnection.php");
require_once(dirname(__FILE__) . "/../utils/SystemFeedback.php");



class AdminsModel{
    public $pdo;
    public $feedback;
    public function __construct(){
        $dbConnection = DatabaseConnection::getInstance();
        // PDOインスタンス（データベース接続）を取得
        $this->pdo = $dbConnection->getConnection();
        $this->feedback = new SystemFeedback();
    }


    public function getUserName($username){
        try{
            $statement = $this->pdo->prepare(
                "SELECT 
                        EXISTS
                            (SELECT `encrypted_password` FROM admins WHERE username = :username) 
                    AS UserExists
            ");
            $statement->bindValue(':username', $username);
            $statement->execute();
            return $statement->fetchColumn();

        } catch(PDOException $e){
            $this->feedback->logError($e->getMessage());
            SystemFeedback::redirectToSystemErrorPage(ERROR_TEXT, ERROR_CODE_LOGIN);
            exit;
        }
    }
    public function getAdminName($id){
        try{
            $statement = $this->pdo->prepare(
                "SELECT username
                        FROM admins WHERE id = :id
            ");
            $statement->bindValue(':id', $id);
            $statement->execute();
            return $statement->fetchColumn();

        } catch(PDOException $e){
            $this->feedback->logError($e->getMessage());
            SystemFeedback::redirectToSystemErrorPage(ERROR_TEXT, ERROR_CODE_LOGIN);
            exit;
        }
    }

    public function getAllAdminInfo(){
        try{
            $statement = $this->pdo->prepare(
                "SELECT `username`, `created_at`, `id` FROM admins WHERE `role` IS NULL" );

            $statement->execute();
            return $statement->fetchAll(PDO::FETCH_ASSOC);

        } catch(PDOException $e){
            $this->feedback->logError($e->getMessage());
            SystemFeedback::redirectToSystemErrorPage(ERROR_TEXT, ERROR_CODE_LOGIN);
            exit;
        }
    }
    

    public function getPasswordAndId($username){
        try{
            $statement = $this->pdo->prepare(
                "SELECT 
                    `encrypted_password`, `id`, `role`
                FROM 
                    admins 
                WHERE 
                    username = :username
            ");
            $statement->bindValue(':username', $username);
            $statement->execute();
            return $statement->fetch(PDO::FETCH_ASSOC);

        } catch(PDOException $e){
            $this->feedback->logError($e->getMessage());
            SystemFeedback::redirectToSystemErrorPage(ERROR_TEXT, ERROR_CODE_LOGIN);
            exit;
        }
    }


}