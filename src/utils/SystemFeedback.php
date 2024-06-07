<?php

require_once dirname(__FILE__) . "/../config/conf.php";
require_once dirname(__FILE__) . "/../utils/Security.php";

class SystemFeedback{
   public $logFile = LOG_FILE_PATH;
   public $user_id;

 

   // ログファイルに書き込む処理
   public static function writeErrorLog($error_msg){
      // 現在の日付を取得
      $timestamp = date("Y-m-d H:i:s");
      $date = date("Y-m-d");
      // ファイル名を日付で設定
      $filename = LOG_FILE_PATH . "log_" . $date . ".log";
      $logMsg = "[$timestamp]" . $error_msg . PHP_EOL;
      file_put_contents($filename, $logMsg, FILE_APPEND);
   }

   // システムエラーが起きた時
   public function logError($error_msg){

      // 呼び出し元の情報を取得
      $backtrace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1);
      $line = isset($backtrace[0]['line']) ? $backtrace[0]['line'] : 'N/A';
      $file = isset($backtrace[0]['file']) ? $backtrace[0]['file'] : 'N/A';

      $msg = "Error in $file on line $line: $error_msg" ;

      SystemFeedback::writeErrorLog($msg);
   }

   public static function handleSecurityBreach($error_txt, $error_code){
      unset($_SESSION["csrf_token"]);
      session_regenerate_id(true);
      SystemFeedback::redirectToSystemErrorPage($error_txt, $error_code);
   }

   // バリデーションエラーが起きた場合の処理(必須項目の値が欠けていた場合など)
   // エラーメッセージをセッションに入れて、ユーザーにページで表示させる
   public static function redirectToIndexWithError($error_msg, $location){
      if (ob_get_level() > 0) {
         ob_clean();
      }
      $_SESSION["input_error"] = $error_msg;
      header("Location: " .$location);
      exit;
   }

  // ドメイン処理が成功した場合の処理
   public static function showSuccessMsg($msg, $url, $type){
      if (ob_get_level() > 0) {
         ob_clean();
      }

      $_SESSION["sucess_msg_" . $type] = $msg;
      header("Location: " . $url);
   }

   // システム側でエラーが起きた時のユーザー側の遷移先
    public static function redirectToSystemErrorPage($error_txt, $error_code){
        if (ob_get_level() > 0) {
            ob_clean();
        }
        $_SESSION['error'] = [$error_txt, $error_code];
        header("Location:" .  PATH . "error");
        exit;
    }

   // 不正なIDが検出された場合の処理
   public static function invalidIDError($data) {
      $security = new Security();

      // スタックトレースから呼び出し元の情報を取得
      $backtrace = debug_backtrace();
      $caller = $backtrace[1]; // 0番目の要素が現在の関数呼び出し
      $currentFile = $caller['file'];
      $currentLine = $caller['line'];

      $receivedId = ($_POST[$data] !== "") ? $_POST[$data] : 'Not Provided';
      $user_id = isset($_SESSION["admin_id"]) ? $_SESSION["admin_id"] : $_SESSION["user_id"];
      $userId     = isset($user_id) ? Security::maskUserId($security->tokenizeUserId($user_id)): 'Not Logged In';
      $requestURL = $_SERVER["REQUEST_URI"];
      $httpMethod = $_SERVER["REQUEST_METHOD"];
      $scriptName = $_SERVER["SCRIPT_NAME"];
      $postData = json_encode(array_diff_key($_POST, ["csrf_token" => ""]), JSON_UNESCAPED_UNICODE);

      $logMessage = sprintf("Invalid ID detected in %s at line %s. \n Received ID: %s,\n User ID: %s,\n REQUEST URL: %s,\n HTTP Method: %s,\n Script: %s,\n POST Data: %s\n", 
      $currentFile, $currentLine, $receivedId, $userId, $requestURL, $httpMethod, $scriptName, $postData);
      SystemFeedback::writeErrorLog($logMessage);
      SystemFeedback::handleSecurityBreach(SECURITY_ERROR, ERROR_CODE_LOGIN);
  }

  public static function transactionError($error){
   // スタックトレースから呼び出し元の情報を取得
      $backtrace = debug_backtrace();
      $caller = $backtrace[1]; // 0番目の要素が現在の関数呼び出し
      $currentFile = $caller['file'];
      $currentLine = $caller['line'];

      $logMessage = sprintf("transaction error in %s at line %s. \n details: %s", 
      $currentFile, $currentLine, $error);

      SystemFeedback::writeErrorLog($logMessage);
      SystemFeedback::handleSecurityBreach(ERROR_TEXT, ERROR_CODE_INDEX);
  }
  public static function fileOperationError($type, $name){
   // スタックトレースから呼び出し元の情報を取得
      $backtrace = debug_backtrace();
      $caller = $backtrace[1]; // 0番目の要素が現在の関数呼び出し
      $currentFile = $caller['file'];
      $currentLine = $caller['line'];

      $logMessage = sprintf("File operation error in %s at line %s. \n details: Failed to delete %s (%s)", 
      $currentFile, $currentLine, $type, $name);

      SystemFeedback::writeErrorLog($logMessage);
      SystemFeedback::handleSecurityBreach(ERROR_TEXT, ERROR_CODE_INDEX);
  }

   // 不正なIDが検出された場合の処理(詳細画面)
   public static function invalidIDErrorForShowPage($data) {
      $security = new Security();

      // スタックトレースから呼び出し元の情報を取得
      $backtrace = debug_backtrace();
      $caller = $backtrace[0]; // 0番目の要素が現在の関数呼び出し
      $currentFile = $caller['file'];
      $currentLine = $caller['line'];

      $receivedId = $_GET[$data];
      $user_id = isset($_SESSION["admin_id"]) ? $_SESSION["admin_id"] : $_SESSION["user_id"];
      $userId     = isset($user_id) ? Security::maskUserId($security->tokenizeUserId($user_id)): 'Not Logged In';
      $requestURL = $_SERVER["REQUEST_URI"];
      $httpMethod = $_SERVER["REQUEST_METHOD"];
      $scriptName = $_SERVER["SCRIPT_NAME"];

      if(isset($_GET[$data])){
         $logMessage = sprintf("Invalid ID detected in %s at line %s. \n Received ID: %s,\n User ID: %s,\n REQUEST URL: %s,\n HTTP Method: %s,\n Script: %s,\n", 
         $currentFile, $currentLine, $receivedId, $userId, $requestURL, $httpMethod, $scriptName);
      }else{
         $logMessage = sprintf("ID is empty. \n User ID: %s,\n REQUEST URL: %s,\n HTTP Method: %s,\n Script: %s,\n", 
         $userId, $requestURL, $httpMethod, $scriptName);
      }
      
      SystemFeedback::writeErrorLog($logMessage);
      SystemFeedback::handleSecurityBreach(SECURITY_ERROR, ERROR_CODE_LOGIN);
  }

   // 不正なデータが検出された場合の処理
   public static function invalidDataError($data) {
      $security = new Security();

      // スタックトレースから呼び出し元の情報を取得
      $backtrace = debug_backtrace();
      $caller = $backtrace[1]; // 0番目の要素が現在の関数呼び出し
      $currentFile = $caller['file'];
      $currentLine = $caller['line'];
  
      $receivedData =  ($_POST[$data] !== "") ? $_POST[$data] : 'Not Provided data';
      $user_id = isset($_SESSION["admin_id"]) ? $_SESSION["admin_id"] : $_SESSION["user_id"];
      $userId     = isset($user_id) ? Security::maskUserId($security->tokenizeUserId($user_id)): 'Not Logged In';
      $requestURL = $_SERVER["REQUEST_URI"];
      $httpMethod = $_SERVER["REQUEST_METHOD"];
      $scriptName = $_SERVER["SCRIPT_NAME"];
      $postData = json_encode(array_diff_key($_POST, ["csrf_token" => ""]), JSON_UNESCAPED_UNICODE);

      $logMessage = sprintf("Invalid Data detected in %s at line %s. \n Received data: %s,\n User ID: %s,\n REQUEST URL: %s,\n HTTP Method: %s,\n Script: %s,\n POST Data: %s\n", 
      $currentFile, $currentLine, $receivedData, $userId, $requestURL, $httpMethod, $scriptName, $postData);
      SystemFeedback::writeErrorLog($logMessage);
      SystemFeedback::handleSecurityBreach(SECURITY_ERROR_DATA, ERROR_CODE_LOGIN);
  }

   // データが空の場合の処理
   public static function missingDataError($data, $location) {
      $emptyFields = [];
      foreach($data as $key => $value){
            $emptyFields[] = $value;
      }
   
      $security = new Security();

      // スタックトレースから呼び出し元の情報を取得
      $backtrace = debug_backtrace();
      $caller = $backtrace[1]; // 0番目の要素が現在の関数呼び出し
      $currentFile = $caller['file'];
      $currentLine = $caller['line'];

      $userId        = isset($_SESSION["user_id"]) ? Security::maskUserId($security->tokenizeUserId($_SESSION["user_id"])): 'Not Logged In';
      $requestURL    = $_SERVER["REQUEST_URI"];
      $httpMethod    = $_SERVER["REQUEST_METHOD"];
      $scriptName    = $_SERVER["SCRIPT_NAME"];
      $emptyFieldsString = implode(", ", $emptyFields);
      

      $logMessage = sprintf("ERROR: Required data missing in %s at line %s. \n Details: Missing field: %s,\n User ID: %s,\n REQUEST URL: %s,\n HTTP Method: %s,\n Script: %s\n", 
      $currentFile, $currentLine, $emptyFieldsString, $userId, $requestURL, $httpMethod, $scriptName);

      SystemFeedback::writeErrorLog($logMessage);
      
      SystemFeedback::redirectToIndexWithError(ERROR_INPUT_DOMAIN_ERROR, $location);
  }

   // CSRFエラー
   public static function csfrError(){
      $security = new Security();

      // スタックトレースから呼び出し元の情報を取得
      $backtrace = debug_backtrace();
      $caller = $backtrace[1]; // 1番目の要素が現在の関数呼び出し
      $currentFile = $caller['file'];
      $currentLine = $caller['line'];

      $userId     = isset($_SESSION["user_id"]) ? Security::maskUserId($security->tokenizeUserId($_SESSION["user_id"])): 'Not Logged In';;
      $requestURL = "Request URL: " . $_SERVER["REQUEST_URI"];
      $httpMethod = "HTTP Method: " . $_SERVER["REQUEST_METHOD"];
      $scriptName = $_SERVER["SCRIPT_NAME"];

      // ログに記録
      $logMessage = "post: " . $_POST["csrf_token"]. "session". $_SESSION["csrf_token"];
      // $logMessage = sprintf("CSRF Token Verification Error in %s at line %s: Invalid CSRF Token Detected.\n User ID: %s,\n REQUEST URL: %s,\n HTTP Method: %s,\n Script: %s\n", 
      // $currentFile, $currentLine, $userId, $requestURL, $httpMethod, $scriptName);
      SystemFeedback::writeErrorLog($logMessage);
      SystemFeedback::handleSecurityBreach(SECURITY_ERROR, ERROR_CODE_LOGIN);
   }
}