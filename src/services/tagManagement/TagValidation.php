<?php

use Dotenv\Validator;

require_once(dirname(__FILE__) . "/../../config/conf.php");
require_once(dirname(__FILE__) . "/../../models/TagsModel.php");
require_once(dirname(__FILE__) . "/../../utils/DataValidation.php");
require_once(dirname(__FILE__) . "/../../utils/SystemFeedback.php");

class TagValidation{


    // ######################################################################################
    //                          必要なデータがあるかのチェック処理
    //#######################################################################################

    // 配信トリガーがallの場合
    public static function hasAllNecessaryValuesForTriggerAll(){
        $requiredKeys = ["trigger_category"];
        $optionalKeys = ["tag_head", "tag_body"];
        return DataValidation::hasAllNecessaryValues($requiredKeys, $optionalKeys);
    }

    // 配信トリガーがall以外の場合(広告コードの指定あり)
    public static function hasAllNecessaryValuesForTriggerWithAdCode(){
        $requiredKeys = ["trigger_category", "ad_code", "ad_num"];
        $optionalKeys = ["tag_head", "tag_body"];
        return DataValidation::hasAllNecessaryValues($requiredKeys, $optionalKeys);
    }

    // 配信トリガーがallかつ、範囲指定がある場合
    public static function hasAllNecessaryValuesForTriggerwithAdCodeAndRange(){
        $requiredKeys = ["trigger_category", "ad_code", "ad_num", "ad_range"];
        $optionalKeys = ["tag_head", "tag_body"];
        return DataValidation::hasAllNecessaryValues($requiredKeys, $optionalKeys);
    }

}