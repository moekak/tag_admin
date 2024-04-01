<?php

// ドメイン処理共通(追加と編集)

interface TagTypeBase{
    public function formValidator($error);
    public function operateDatabaseWithAdd();
    public function operateDatabaseWithEdit();
    public function setDataWithAdd();
    public function setDataWithEdit();
}

