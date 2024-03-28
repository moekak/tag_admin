<?php

namespace TriggerCategory;

require_once(dirname(__FILE__) . "/../../../services/domainManagemnt/DomainDataAccess.php");
require_once(dirname(__FILE__) . "/../../../services/tagManagement/TagDataAccess.php");
require_once(dirname(__FILE__) . "/../../../utils/SystemFeedback.php");

abstract class Base{
    abstract public function isTagDataExisted($ad_code);
    abstract public function submissionProcessForAdd($data);
    abstract public function insertDataToDb($data);
    abstract public function updateTagDataDB($data);
    abstract public function submissionProcessForEdit($data);
    abstract public function deactivateTagForShow();

    public function deativateTagForIndex(){
        $tag_access = new \TagDataAccess();

        $tag_access->deactivateTag(intval($_POST["id"]));
        $tag_access->deactivateTagDataWithRange(intval($_POST["id"]));
    }
}
