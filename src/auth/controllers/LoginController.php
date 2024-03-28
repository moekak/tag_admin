<?php


require_once(dirname(__FILE__) . "/../../models/AdminsModel.php");
require_once(dirname(__FILE__) . "/../../utils/SystemFeedback.php");
require_once(dirname(__FILE__) . "/../../utils/Security.php");
require_once(dirname(__FILE__) . "/../../utils/FormValidation.php");
require_once(dirname(__FILE__) . "/../../utils/DataValidation.php");
require_once(dirname(__FILE__) . "/../../config/conf.php");

// エラーメッセージはconfファイルで管理

class LoginController{
    public $username;
    public $plain_password;
    private $admin_model;
    public $logFile;
    public $feedback;
    public $hashed_password;

    public function __construct(){
        $this->admin_model = new AdminsModel();
        $this->feedback = new SystemFeedback();
    }

    public function get(){
        Security::generateCsrfToken();
        require __DIR__ . '/../views/login.php';
    }

    public function post(){
        $this->handlePostRequest();
    }

    private function formValidator(){
        FormValidation::checkCSRF();
    }


    public function handlePostRequest(){
        // 共通セキュリティとバリデーションチェック
        $this->formValidator();

        $username = DataValidation::sanitizeInput($_POST["username"]);
        $password = $_POST["password"];
       
        // ユーザー名とパスワードが未入力の場合
        if($username == "" || $password == ""){
            $this->renderPageAndShowErrorMsg(ERROR_EMPTY_VALUE);
            return;
        }
       
        // ユーザー名がデータベースの値と一致しない場合
        if(!$this->isMatchedUsername($username)){
            $this->renderPageAndShowErrorMsg(ERROR_INVALID_VALUE);
            return;
        }

        $results = $this->admin_model->getPasswordAndId($username);
    
        // パスワードがデータベースの値と一致しない場合
        if (!$this->isMatchedPassword($password, $results["encrypted_password"])) {
            $this->renderPageAndShowErrorMsg(ERROR_INVALID_VALUE);
            return;
        }

        // ユーザーidを取得して管理画面ファーストページに飛ばす

        // セッション固定攻撃の防止、セッションハイジャックのリスク軽減
        session_regenerate_id(true);
        $_SESSION["user_id"] = $results["id"];  //ユーザーid
        header("Location:" . PATH . "index");
        exit;
      
    }

    // 入力されたユーザ名が登録されてるかのチェック
    public function isMatchedUsername($username){
        return $this->admin_model->getUserName($username);
    }

    // 入力されたパスワードがあってるかチェック
    public function isMatchedPassword($plain_password, $hashed_password){
        return password_verify($plain_password, $hashed_password);
    }

    // エラーが起きた時の処理
    public function renderPageAndShowErrorMsg($error_msg){
        $_SESSION["login_error"] = $error_msg;
        header("Location:" . PATH);
        exit;
    }
}