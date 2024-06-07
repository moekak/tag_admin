<?php


require_once(dirname(__FILE__) . "/../config/conf.php");




$admin_info = "";
if(isset($_SESSION["admin_info"])){
    $admin_info = $_SESSION["admin_info"];
}




$domain_types = "";

if(isset($_SESSION["domain_types"])){
    $domain_types = $_SESSION["domain_types"];
}




$input_error = "";
if(isset($_SESSION["input_error"])){
    $input_error = $_SESSION["input_error"];
    unset($_SESSION["input_error"]);
} 



$success_msg = "";
if(isset($_SESSION["sucess_msg_index"])){
    $success_msg = $_SESSION["sucess_msg_index"];
    unset($_SESSION["sucess_msg_index"]);
} 

$error = "";
if(isset($_SESSION["error_code"])){
    $error = $_SESSION["error_code"];
    unset($_SESSION["error_code"]);
} 

$script_index = "";

if(isset($_SESSION["script_index"])){
    $script_index = htmlspecialchars($_SESSION["script_index"]);
}

$script_rd = "";

if(isset($_SESSION["script_rd"])){
    $script_rd = htmlspecialchars($_SESSION["script_rd"]);
}

$flag = "";
if(isset($_SESSION["create_script_flag"])){
    $flag = $_SESSION["create_script_flag"];
    unset($_SESSION["create_script_flag"]);
}

$data = "";
if(isset($_SESSION["sent_data"])){
    $data = $_SESSION["sent_data"];
    unset($_SESSION["sent_data"]);
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?=PATH?>public/css/common.css">
    <link rel="stylesheet" href="<?=PATH?>public/css/domain_page.css">
    <link rel="stylesheet" href="<?=PATH?>public/css/script.css">
    <link rel="stylesheet" href="<?=PATH?>public/css/super_admin.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <script src="https://kit.fontawesome.com/49c418fc8e.js" crossorigin="anonymous"></script>
    <link rel="shortcut icon" href="<?= PATH ?>favicon.ico">
    <title>タグ管理画面</title>
</head>
<body>
    <input type="hidden" value="<?= $flag?>" id="script_flag">

    <div class="domain_wrapper relative">
    <p id="js_success" class="green bold hidden "><?= $success_msg?></p>
        <div class="bg-gray absolute <?php echo $data == "" ? "hidden" : ""?>"></div>
        <div class="domain_top_container container_paddingRL">
            <h1 class="bold margin_0">タグ管理画面</h1>
        </div>
        <div class="domain_info_container container_paddingRL">
            <p id="js_success" class="green bold hidden "><?= $success_msg?></p>
            <input type="hidden" value="<?=$error?>" id="js_error2">
    
            <div class="domain_info_area">
                <div class="domain_info_area_top">
                    <div class="category">
     
                    </div>
                    <div class="domain_addBtn">
                        <button class="domain_btn" id="js_admin_add">管理者追加</button>
                        <span class="search_btn js_search_btn">
                            <img src="<?= PATH?>public/img/icons8-search-50.png" alt="" class="search_icon domain_btn" style="padding: 0;">
                        </span>
                        
                    </div>
        
                </div>
                <div class="domain_info_area_main">
                    <div class="domain_info_area_main_box">
                        <div class="box_paddingRL serach">
                            <p>管理者一覧</p>
                        </div>
                        <div class="box_paddingRL scrollbar_none js_scroll" style="height: 70vh; overflow-y: scroll;">
                            <table class="table table-striped">
                                <thead style="position: sticky; top: 0;" id="js_table">
                                    <tr>
                                        <th scope="col" style="width: 376px;">管理者名</th>
                                        <th scope="col" style="width: 300px;">作成日時</th>
                                        <th>操作</th>
                                    </tr>
                                </thead>
                                <?php foreach($admin_info as $info){?>
                                    <tbody>
                                        <tr>
                                            <th class="align-middle"><?= $info["username"]?></th>
                                            <th class="align-middle">
                                                <?php
                                                    $date = new DateTime($info["created_at"]);

                                                    // 日付を指定の形式にフォーマット
                                                    $formattedDate = $date->format('Y年m月d日');

                                                    echo $formattedDate
                                                
                                                ?>
                                            </th>
                                            <th class="align-middle">
                                                <button type="button" class="btn btn-success js_index" data-id=<?=$info["id"]?>>一覧</button>
                                                <button type="button" class="btn btn-primary js_edit_btn" data-id=<?=$info["id"]?>>編集</button>
                                                <button type="button" class="btn btn-danger js_delete_btn" data-id=<?=$info["id"]?>>削除</button>
                                            </th>
                                        </tr>
                                    </tbody>
                                <?php }?>
                            </table>
                        </div>   
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- serach modal -->
    <div class="modal_container fixed js_alert_modal hidden js_modal">
        <div class="modal_flex">
            <p>ドメイン検索</p>
            <img src="<?=PATH?>public/img/icons8-close-50.png" alt="" class="close-icon js_close_icon">
        </div>
        <div class="search_form relative">
            <input type="text" name="serach" class="search_domain_input js_search_domain" placeholder="検索したいドメインまたはIDを入力してください。">
            <img src="<?= PATH?>public/img/icons8-search-51.png" alt="" class="search_icon2 absolute">
        </div>
        <div class="table_data">
            <table class="table table-striped data_results_container">

            </table>
        </div>
    </div>

    <!-- edit modal -->
    <div class="modal_container_edit fixed js_edit_modal hidden js_modal">
        <div class="modal_flex">
            <p class="bold">管理者編集</p>
            <img src="<?=PATH?>public/img/icons8-close-50.png" alt="" class="close-icon js_close_icon">
        </div>
        <form action="<?= PATH ?>admin/edit" method="post">
            <input type="hidden" name="csrf_token" value=<?=$_SESSION['csrf_token']?>>
            <input type="hidden" name="adminID" class="js_admin_input">
            <div class="search_form relative">
                <label for="" style="font-size: 13px;">ユーザー名</label>
                <input type="text" name="username" class="edit_username_input"><br>
            </div>
            <button type="submit" class="btn btn-primary js_update_btn">更新</button>
        </form>
    </div>

    <!-- ドメイン削除モーダル -->
    <div class="domain_delete_modal_container fixed js_delete_modal hidden">
        <h5 class="bold">本当に削除しますか？</h5>
        <div class="padding_t20"></div>
        <p class="js_username username_check">s</p>
        <div class="padding_t10"></div>
        <p class="modal-font-size"><span class="red bold ">重要</span>: この管理者を削除すると、それに関連するすべてのドメインとタグも同時に削除されます。この操作は元に戻すことができません。選択された管理者を本当に削除してもよろしいですか？</p>
        <div class="padding_t10"></div>
        <div class="modal_btn_container">
        <form class="js_deleteDomain_form" action="<?=PATH?>admin/delete" method="post">
            <input type="hidden" class="js_adminID_delete" value="" name="adminID">
            <input type="hidden" name="csrf_token" value=<?=$_SESSION['csrf_token']?>>
            <button class="modal_btn bg-red bold js_delete" style="color: white;">削除</button> 
        </form>
           
        </div>
        <div class="padding_t20"></div>
        <div class="modal_btn_container">
           <button class="modal_btn bold bg-white border js_cancel_btn">いいえ</button> 
        </div>
    </div>
    <!-- 管理者追加 -->

    <div class="modal_container_edit fixed js_create_modal js_modal <?php echo $data == "" ? "hidden" : ""?>">
        <div class="modal_flex">
            <p class="bold">管理者追加</p>
            <img src="<?=PATH?>public/img/icons8-close-50.png" alt="" class="close-icon js_close_icon">
        </div>
        <form action="<?= PATH ?>admin/create" method="post">
            <input type="hidden" name="csrf_token" value=<?=$_SESSION['csrf_token']?>>
            <div class="search_form relative">
                <label for="" style="font-size: 13px;">ユーザー名</label>
                <input type="text" name="username" class="js_new_username add_username_input" placeholder="10文字以内で入力してください。" value="<?= $data?>"><br>
                <small class="red js_username_alert <?php echo $data == "" ? "hidden" : ""?>" style="font-size: 12px;margin-bottom: 15px;"><?php echo $data == "" ? "10文字以内で入力してください。" : "既にこのユーザー名は使われています"?></small><br>
                <label for="" style="font-size: 13px;">パスワード</label>
                <input type="text" name="password" class="js_new_password add_username_input" placeholder="8文字以上で入力してください。"><br>
                <small class="red js_password_alert hidden" style="margin-bottom: 15px; font-size:12px;">8文字以上で入力してください。</small>
            </div>
            <button type="submit" class="btn btn-primary js_add_btn disable" style="margin-top: 30px;">新規追加</button>
        </form>
    </div>

    




    

    <!-- テスト環境 -->
    <script type="module" src="<?=PATH?>dist/superAdmin.js"></script>

   
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js" integrity="sha384-fbbOQedDUMZZ5KreZpsbe1LCZPVmfTnH7ois6mU1QK+m14rQ1l2bGBq41eYeM/fS" crossorigin="anonymous"></script>
</body>
</html>