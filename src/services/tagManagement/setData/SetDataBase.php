<?php
namespace SetData;

abstract class SetDataBase{

    abstract public function setVariable();
    abstract public function setVariableForDeactivate();
    abstract public function generateAdCode($code, $num);

    // 新しいタグのjsonデータを作成
    protected function changeToJsonData($key)
    {
        $data_array[] = $key;
        return json_encode($data_array, true);
    }
}
