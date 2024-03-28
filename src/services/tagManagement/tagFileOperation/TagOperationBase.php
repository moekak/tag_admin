<?php
namespace TagFileOperation;

abstract class TagOperationBase{
    abstract public function insertDataToFile($data);
    abstract public function updateTagDataFile($data);
    abstract public function fileOperationHelper($type, $data, $code, $code2);
    abstract public function deleteTagFileForShowPage($data);
}
