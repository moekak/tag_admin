<?php
namespace TagFileOperation;

require_once(dirname(__FILE__) . "/../../../services/tagManagement/tagFileOperation/TagWithCode.php");
require_once(dirname(__FILE__) . "/../../../services/tagManagement/tagFileOperation/TagWithCodeRange.php");
require_once(dirname(__FILE__) . "/../../../services/tagManagement/tagFileOperation/TagWithoutCode.php");



class ClassInstanceMapForTagFile{
    public $property;
    public $withCode;
    public $withCodeRange;
    public $withoutCode;
    public $map;

    public function __construct($property){

        $this->property         = $property;
        $this->withCode         = new TagWithCode(); 
        $this->withCodeRange    = new TagWithCodeRange();
        $this->withoutCode      = new TagWithoutCode();

        $this->map = array(
            "withCode"     => $this->withCode,
            "withCodeRange"     => $this->withCodeRange,
            "withoutCode" => $this->withoutCode,
        );
    }

    public function getInstance(){
        return $this->map[$this->property];
    }

}

