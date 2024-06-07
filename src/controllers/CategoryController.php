<?php

require_once(dirname(__FILE__) . "/../models/DomainTypesModel.php");
require_once(dirname(__FILE__) . "/../utils/DataValidation.php");
require_once(dirname(__FILE__) . "/../utils/FormValidation.php");
require_once(dirname(__FILE__) . "/../utils/SystemFeedback.php");
require_once(dirname(__FILE__) . "/../config/conf.php");

class CategoryController{
    public $category_model;
    public $user_id;

    public function __construct(){
        $this->category_model     = new DomainTypesModel();
        $this->user_id = isset($_SESSION["admin_id"]) ? $_SESSION["admin_id"] : $_SESSION["user_id"];
    }

   

    // タグ作成
    public function create(){
        FormValidation::checkCSRF();
        FormValidation::checkEmptyData("category_name", PATH ."index");

        $this->category_model->insertCategory($this->user_id, DataValidation::sanitizeInput($_POST["category_name"]));
        SystemFeedback::showSuccessMsg(SUCCESS_CATEGORY_CREATE, PATH ."index", "index");
        header("Location:" . PATH . "index");
    }

    public function edit(){
        FormValidation::checkCSRF();
        FormValidation::checkEmptyData("category_name", PATH ."index");
        FormValidation::checkEmptyData("category_id", PATH ."index");
        FormValidation::checkValidID("category_id", $this->category_model->checkCategoryID($this->user_id, $_POST["category_id"]));
        
        $this->category_model->updateCategory($this->user_id, DataValidation::sanitizeInput($_POST["category_name"]), intval($_POST["category_id"]));
        SystemFeedback::showSuccessMsg(SUCCESS_CATEGORY_EDIT, PATH ."index", "index");
        header("Location:" . PATH . "index");
    
    }
}