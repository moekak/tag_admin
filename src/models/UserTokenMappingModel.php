<?php

require_once(dirname(__FILE__) . "/../utils/DatabaseConnection.php");
require_once(dirname(__FILE__) . "/../utils/SystemFeedback.php");
require_once(dirname(__FILE__) . "/../config/conf.php");



class UserTokenMappingModel{
    public $pdo;
    public $feedback;

    public function __construct(){
        $dbConnection = DatabaseConnection::getInstance();
        // PDOインスタンス（データベース接続）を取得
        $this->pdo = $dbConnection->getConnection();
        $this->feedback = new SystemFeedback();
    }

    public function storeMapping($token, $user_id){
        try{
            $statement = $this->pdo->prepare("INSERT INTO user_token_mapping (token, user_id) VALUES (:token, :user_id)");
            $statement->bindValue(':token', $token, PDO::PARAM_STR);
            $statement->bindValue(':user_id', $user_id, PDO::PARAM_INT);
            $statement->execute();

        }catch(PDOException $e){
            $this->feedback->logError($e->getMessage());
            SystemFeedback::redirectToSystemErrorPage(ERROR_TEXT, ERROR_CODE_LOGIN);
            exit;
        }
    }

    public function deleteMapping(){
        
    }

}