<?php
require_once(dirname(__FILE__) . "/../../../src/services/adminManagement/AdminValidation.php");
require_once(dirname(__FILE__) . "/../../../src/models/AdminsModel.php");
require_once(dirname(__FILE__) . "/../../../src/models/DomainsModel.php");
require_once(dirname(__FILE__) . "/../../../src/models/TagsModel.php");
require_once(dirname(__FILE__) . "/../../../src/models/TagsRangeModel.php");
require_once(dirname(__FILE__) . "/../../../src/models/DomainTypesModel.php");
require_once(dirname(__FILE__) . "/../../../src/utils/DataValidation.php");
require_once(dirname(__FILE__) . "/../../../src/utils/FormValidation.php");
require_once(dirname(__FILE__) . "/../../../src/config/conf.php");

class AdminController{
      public $validation;
      public $admin_model;
      public $domain_model;
      public $tags_model;
      public $tagsRange_model;
      public $domain_types_model;

      public function __construct()
      {
            $this->validation = new AdminValidation();
            $this->admin_model = new AdminsModel();
            $this->domain_model = new DomainsModel();
            $this->tags_model = new TagsModel();
            $this->tagsRange_model = new TagsRangeModel();
            $this->domain_types_model = new DomainTypesModel();
      }
      
      public function edit(){
            $this->validation->validation($_POST["adminID"]);
            // 管理者情報更新
            $this->admin_model->updateAdminUsername($_POST["adminID"], DataValidation::sanitizeInput($_POST["username"]));

            SystemFeedback::showSuccessMsg(SUCCESS_UPDATE_ADMIN, PATH . "admin", "index");
            return;
      }

      public function delete(){
            $this->validation->validation($_POST["adminID"]);
            // 管理者削除
            $this->admin_model->deleteAdmin($_POST["adminID"]);
            $this->domain_model->deleteDomains($_POST["adminID"]);
            $this->tags_model->deleteTags($_POST["adminID"]);
            $this->tagsRange_model->deleteTagsRange($_POST["adminID"]);
            $this->domain_types_model->deleteDomainTypes($_POST["adminID"]);

            SystemFeedback::showSuccessMsg(SUCCESS_UPDATE_ADMIN, PATH . "admin", "index");
            return;
      }

      public function create(){
            FormValidation::checkCSRF();
            $username = DataValidation::sanitizeInput($_POST["username"]);
            $password = DataValidation::sanitizeInput($_POST["password"]);
            $hashedPassword =password_hash($password, PASSWORD_DEFAULT);

            FormValidation::isAdminExisted($username, "create");
            $this->admin_model->insertAdmin($username, $hashedPassword);

            SystemFeedback::showSuccessMsg(SUCCESS_CREATE_ADMIN, PATH . "admin", "index");
            return;
      }
}