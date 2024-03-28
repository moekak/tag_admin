<?php
require_once(dirname(__FILE__) . "/../config/conf.php");


$path = PATH;


$error_array = "";
if(isset($_SESSION["error"])){
    $error_array = $_SESSION["error"];

    echo $_SESSION["error"][0];

    

    if($error_array[1] === ERROR_CODE_LOGIN){
        echo "<div>
                <a href='$path'>ログイン画面に戻る</a>
            </div>";
    }
    if($error_array[1] === ERROR_CODE_FETCH){
        echo " <div>
                <a href='{$path}index'>一覧画面に戻る</a>
            </div>";
    }

    if($error_array[1] === ERROR_CODE_INDEX){
        echo "<div>
                <a href='{$path}index'>戻る</a>
            </div>";
    }
}




