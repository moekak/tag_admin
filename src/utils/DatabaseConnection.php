<?php

require_once(dirname(__FILE__). "/../utils/SystemFeedback.php");
require_once(dirname(__FILE__). "/../config/conf.php");


class DatabaseConnection{
    private $pdo;
    // なんで$Instanceにstaticつけるの？
    // →クラスのすべてのインスタンスで同じ$Instanceを共有するから。
    private static $instance = null;
    public $error_handler;

    private function __construct(){
        $this->error_handler = new SystemFeedback();
        try {
            //  throw new Exception("テスト例外");
            $dsn = "mysql:host=" . $_ENV["DB_HOST"] . ";dbname=" . $_ENV["DB_NAME"] .  ";charset=utf8";
            $this->pdo = new PDO($dsn, $_ENV["DB_USER"], $_ENV["DB_PASSWORD"]);

        } catch (\PDOException $e) {
            $this->error_handler->logError("Database connection failed: ". $e->getMessage());
            $_SESSION["error"] = [ERROR_TEXT, ERROR_CODE_DATABASE];
            // エラー表示させるページに遷移させる
            header("Location:" .PATH . "error");
            exit;
        }
    }
    // 特定のクラスのインスタンスがアプリケーション全体でただ一つだけ生成されることを保証する
    // 外部からインスタンス化できない
    // なぜ使う？→　データベース接続はリソースを多く消費するため。複数の接続を開く代わりに同じ接続を再利用できる。
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    // PDOインスタンスへのアクセス
    public function getConnection() {
        return $this->pdo;
    }
}