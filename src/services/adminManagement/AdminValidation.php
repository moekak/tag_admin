<?php

require_once(dirname(__FILE__) . "/../../../src/utils/FormValidation.php");
require_once(dirname(__FILE__) . "/../../../src/utils/DataValidation.php");
require_once(dirname(__FILE__) . "/../../../src/models/AdminsModel.php");

class AdminValidation{


      public $admin_model;

      public function __construct()

      {
            $this->admin_model = new AdminsModel();
      }

      public function validation($id){

            return FormValidation::checkValidID("adminID", $this->admin_model->isUserExisted($id)) && FormValidation::checkCSRF();
      }
}