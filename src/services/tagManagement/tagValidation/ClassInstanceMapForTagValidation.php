<?php
namespace TagValidation;

require_once(dirname(__FILE__) . "/../../../services/tagManagement/tagValidation/TagWithCode.php");
require_once(dirname(__FILE__) . "/../../../services/tagManagement/tagValidation/TagWithCodeRange.php");
require_once(dirname(__FILE__) . "/../../../services/tagManagement/tagValidation/TagWithoutCode.php");



class ClassInstanceMapForTagValidation{
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
            "withCode"      => $this->withCode,
            "withCodeRange" => $this->withCodeRange,
            "withoutCode"   => $this->withoutCode,
        );
    }

    public function getInstance(){
        return $this->map[$this->property];
    }

}

