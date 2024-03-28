<?php

use Random\Engine\Secure;

require_once(dirname(__FILE__) . "/../../services/domainManagemnt/ProcessType/DomainAdd.php");
require_once(dirname(__FILE__) . "/../../services/domainManagemnt/ProcessType/DomainEdit.php");
require_once(dirname(__FILE__) . "/../../services/domainManagemnt/ProcessType/DomainDelete.php");
require_once(dirname(__FILE__) . "/../../services/domainManagemnt/ProcessType/DomainDeactivate.php");

class DomainController{
    public $add;
    public $edit;
    public $delete;
    public $deactivate;


    public function __construct(){
        $this->add  = new DomainAdd(isset($_POST["domain_type"]) ? $_POST["domain_type"] : "",  isset($_POST["tag_type"]) ? $_POST["tag_type"] : "");
        $this->edit = new DomainEdit(isset($_POST["domain_type"]) ? $_POST["domain_type"]: "",  isset($_POST["tag_type"]) ? $_POST["tag_type"] : "");
        $this->delete = new DomainDelete();
        $this->deactivate = new DomainDeactivate();
    }

    public function add(){
        $this->add->submissionProcess();
    }

    public function edit(){
        $this->edit->submissionProcess();
    }

    public function delete(){
        $this->delete->submissionProcess();  
    }
    public function deactivate(){
        $this->deactivate->submissionProcess();
    }
}

