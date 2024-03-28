<?php
require_once(dirname(__FILE__) . "/../models/DomainsModel.php");

class Common{
    
    public function randomString($num){
        $characters = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $str = "";
        for ($i = 0; $i < $num; $i++) {
            $index = rand(0, strlen($characters) - 1);
            $str = $str . $characters[$index];
        }

        if($this->isUniqueString($str)){
            return $this->randomString($num);
        }

        return $str;
    }

    public function isUniqueString($string){
        $domain_model = new DomainsModel();

        $randomDomainId_array = $domain_model->getRandomID();

        foreach($randomDomainId_array as $id){
   
            if($id["random_domain_id"] === $string){
                return true;
            }

        }
    

        return false;
    }


}