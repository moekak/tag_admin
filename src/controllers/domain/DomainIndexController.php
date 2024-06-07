<?php

require_once(dirname(__FILE__) . "/../../models/DomainsModel.php");
require_once(dirname(__FILE__) . "/../../models/AdminsModel.php");
require_once(dirname(__FILE__) . "/../../models/DomainTypesModel.php");
require_once(dirname(__FILE__) . "/../../utils/Security.php");
require_once(dirname(__FILE__) . "/../../config/conf.php");



class DomainIndexController{
    public $domain_model;
    public $amdin_model;
    public $domain_types_model;


    public function __construct(){
        $this->domain_model         = new DomainsModel();
        $this->domain_types_model   = new DomainTypesModel();
        $this->amdin_model = new AdminsModel();
        
    }

    // ##########################################################
    // get method
    //##########################################################
    public function get(){

        $user_id = isset($_SESSION["admin_id"]) ? $_SESSION["admin_id"] : $_SESSION["user_id"];
        if(isset($user_id)){
            // ドメイン種類をデータベースから取得
            $_SESSION["domain_types"] = $this->domain_types_model->getDomainType($user_id);
            $_SESSION["admin_info"] = $this->amdin_model->getAdminName($user_id);


            Security::generateCsrfToken();

            require __DIR__ . '/../../../src/views/domain_page.php';
        } else{
            header("Location:". PATH);
        }
    }

}

