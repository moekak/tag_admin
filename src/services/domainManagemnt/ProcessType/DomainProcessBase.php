<?php

// ドメイン処理共通(追加と編集)
interface DomainProcessBase{
    public function formValidator();
    public function submissionProcess();
}

