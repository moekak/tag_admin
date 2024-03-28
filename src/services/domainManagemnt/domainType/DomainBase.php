<?php

use Random\Engine\Secure;


interface  DomainBase{
    public function domainFormValidator();
    public function submissionProcessWithAdd();
    public function submissionProcessWithEdit();
    // public function setDataWithAdd();
    // public function setDataWithEdit();
}

