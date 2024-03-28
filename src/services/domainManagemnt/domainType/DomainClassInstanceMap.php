<?php
require_once(dirname(__FILE__) . "/../../../services/domainManagemnt/domainType/OriginalDomain.php");
require_once(dirname(__FILE__) . "/../../../services/domainManagemnt/domainType/CopyDomain.php");


class DomainClassInstanceMap{
    public $property;
    public $originalDomain;
    public $copyDomain;
    public $map;

    public function __construct($property_domain, $property_tag){

        echo $property_domain;

        $this->property         = $property_domain;
        $this->originalDomain   = new OriginalDomain($property_tag); 
        $this->copyDomain       = new CopyDomain($property_tag);


        $this->map = array(
            "original"  => $this->originalDomain,
            "copy"      => $this->copyDomain,
            "directory" => $this->copyDomain,
        );
    }

    public function getInstance(){
        return $this->map[$this->property];
    }

}