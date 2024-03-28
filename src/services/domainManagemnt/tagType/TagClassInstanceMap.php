<?php
require_once(dirname(__FILE__) . "/../../../services/domainManagemnt/tagType/TagWithoutReference.php");
require_once(dirname(__FILE__) . "/../../../services/domainManagemnt/tagType/TagWithReference.php");
require_once(dirname(__FILE__) . "/../../../services/domainManagemnt/tagType/WithoutTag.php");




class TagClassInstanceMap{
    public $property;
    public $tagReference;
    public $tagNew;
    public $withoutTag;
    public $map;

    public function __construct($property_tag){

        $this->property       = $property_tag;
        $this->tagReference   = new TagWithReference(); 
        $this->tagNew         = new TagWithoutReference(); 
        $this->withoutTag     = new withoutTag();

        $this->map = array(
            "reference" => $this->tagReference,
            "new"       => $this->tagNew,
            "withoutTag" => $this->withoutTag
        );
    }

    public function getInstance(){
        return $this->map[$this->property];
    }
}