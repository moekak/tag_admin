<?php
require_once __DIR__ . "/../models/UserTokenMappingModel.php";
class Security{
  
    // CSRFトークンを生成する
    public static function generateCsrfToken() {
        
        if (!isset($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }

        return $_SESSION["csrf_token"];
    }

    // CSRFトークンを検証する
    public static function checkCSRF(){
        return (isset($_POST["csrf_token"]) && $_POST["csrf_token"] === $_SESSION["csrf_token"]);
    }

     // セッションのセキュリティを強化する
     public static function secureSession() {
        ini_set('session.use_only_cookies', 1);
        ini_set('session.cookie_httponly', 1);
        ini_set('session.cookie_secure', 1);
    }

    //ユーザーIDを安全なトークンに置き換える。
    public function tokenizeUserId($user_id){
        $tokenModel = new UserTokenMappingModel();
        $token = Security::generateCsrfToken();

        $tokenModel->storeMapping($token, $user_id);
        return $token;
    }

    // ユーザーIDの一部をマスキング
    public static function maskUserId($user_id){
        return substr($user_id, 0, 2) . "****" . substr($user_id, -2);
    }
}