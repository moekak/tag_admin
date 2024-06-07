<?php

require_once(dirname(__FILE__) . "/../../models/AdminsModel.php");

class SuperAdminController{
      protected $admin_model;

      public function __construct(){
            $this->admin_model = new AdminsModel();
      }

      public function get(){
            Security::generateCsrfToken();
            $this->getAdminsInfo();
            require __DIR__ . '/../../views/super_admin_index.php';
      }

      public function getAdminsInfo(){
            $_SESSION["admin_info"] =  $this->admin_model->getAllAdminInfo();
      }


}