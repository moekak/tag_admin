<?php


require_once(dirname(__FILE__) . "/../config/conf.php");

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





?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?=PATH?>public/css/common.css">
    <link rel="stylesheet" href="<?=PATH?>public/css/domain_page.css">
    <link rel="stylesheet" href="<?=PATH?>public/css/script.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <script src="https://kit.fontawesome.com/49c418fc8e.js" crossorigin="anonymous"></script>
    <link rel="shortcut icon" href="<?= PATH ?>favicon.ico">
    <title>タグ管理画面</title>
</head>
<body>
    <input type="hidden" value="<?= $flag?>" id="script_flag">

    <div class="domain_wrapper relative">
        <div class="bg-gray absolute hidden"></div>
        <div class="domain_top_container container_paddingRL">
            <h1 class="bold margin_0">タグ管理画面</h1>
        </div>
        <div class="domain_info_container container_paddingRL">
            <p id="js_success" class="green bold hidden "><?= $success_msg?></p>
            <input type="hidden" value="<?=$error?>" id="js_error2">
    
            <div class="domain_info_area">
                <div class="domain_info_area_top">
                    <div class="category">
                        <p class="category_name js_categories_all js_categories" data-category-id="all">全て</p> 
                        <?php foreach($domain_types as $domain_type) {?>
                            <p class="category_name js_categories" data-category-id="<?=$domain_type["id"]?>"><?=$domain_type["domain_type_name"]?></p>
                        <?php }?>
                    </div>
                    <div class="domain_addBtn">
                        <i class="fas fa-cog text_color" style="font-size: 20px; cursor: pointer;"></i>
                        <button class="domain_btn" id="js_domain_add">ドメイン追加</button>

                    </div>
                </div>
                <div class="domain_info_area_main">
                    <div class="domain_info_area_main_box">
                        <div class="box_paddingRL serach">
                            <p>LP</p>
                            <div class="serach_input_box relative">
                                <input type="text" class="search_input js_search_domain">
                                <i class="fas fa-search absolute serach_icon"></i>
                            </div>
                        </div>
                        <div class="box_paddingRL scrollbar_none js_scroll" style="height: 70vh; overflow-y: scroll;">
                            <table class="table js_table"> 
                                
                            </table>
                            <button class="js_btn label">もっと表示させる</button> 
                        </div>
                       
                    </div>
                </div>

            </div>
        </div>
           <!-- ドメイン追加モーダル -->
        <div class="domain_add_modal absolute" id="js_domain_add_modal">
            <div class="bg-gray2 hidden"></div>
            <div class="domain_add_modal_top box_paddingRL">
                <div class="domain_add_modal_top_title">
                   <h2 class="text_color js_close_btns domain_modal_btn" style="cursor: pointer;">×</h2> 
                   <h4 class="text_color" id="js_domain_top_txt">ドメイン追加</h4>
                </div>
                <button class="disabled_btn add_btn" id="js_create_btn">追加</button>
            </div>
            <div class="domain_add_modal_main_container">
            <p id="js_error" class="red"><?= $input_error?></p>
                <form id="js_create_form" method="post" action = "">
                    <!-- 送信するデータ -->
                    <input type="hidden" name="domain_id" id="js_domain_id" value="">
                    <input type="hidden" name="csrf_token" value=<?=$_SESSION['csrf_token']?>>
                    <input type="hidden" name="domain_name" value="" id="js_domain_name">
                    <input type="hidden" name="domain_type" value="" id="js_domain_type">
                    <input type="hidden" name="domain_category" value="" id="js_domain_category">
                    <input type="hidden" name="tag_type"  value="" id="js_tag_type">
                    <input type="hidden" name="parent_domain_id" value="" id="js_copy_reference_id">
                    <input type="hidden" name="original_parent_id" value="" id="js_original_domain_id">
                    <input type="hidden" name="parent_tag_id" value="" id="js_tag_reference_id">
                    <input type="hidden" name="tag_check" value="false" id="js_tag_check">
                    <!-- END -->
                </form>

                <div class="area domain_add_modal_main relative box_paddingRL">
                    <div class="domain_name_input_box">
                        <p class="text_color bold">ドメイン名　<span class="red label light">*必須</span></p>
                        <input type="text" class="domain_name_input border" id="domain_name" placeholder="例: blue-river.biz">
                    </div>
                    <div class="padding_t30"></div>
                    <div class="domain_name_input_box js_form">
                        <div class="domain_name_input_flex">
                            <p class="text_color bold">ドメイン情報 </p>
                            <p class="bold info_btn" id="js_info_btn" style="cursor: pointer;">i</p>
                        </div>
                        <div class="padding_t10"></div>

                        <p class="label">ドメイン種類　<span class="red label">*必須</span</p>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" id="original" value="original" name="type">
                            <label class="form-check-label" for="original">オリジナルサイト</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio"  id="copy" value="copy" name="type">
                            <label class="form-check-label" for="copy">コピーサイト</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" id="directory" value="directory" name="type">
                            <label class="form-check-label" for="directory">ディレクトリ別</label>
                        </div>


                        <!-- 他社のようにドメインの読み込みファイルを変える場合 -->
                        <!-- <div class="form-check" id="js_checkTag" style="display: none;">
                        <div class="padding_t30"> </div>
                            <input class="form-check-input js_tagCheck" type="checkbox" value="" id="flexCheckDefault">
                            <label class="form-check-label" for="flexCheckDefault">
                                親ドメインタグ参照
                            </label>
                        </div> -->
                        <!-- end -->

                        <div class="cateogory_area" style="display: none;">
                            <div class="padding_t30"></div>
                            <p class="label">カテゴリー　<span class="red label">*必須</span></p>

                            <?php foreach($domain_types as $type) {?>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="category" id="domain_category_<?=$type["id"]?>" value=<?=intval($type["id"])?>>
                                    <label class="form-check-label" for="domain_category_<?=$type["id"]?>"><?=$type["domain_type_name"]?></label>
                                </div>
                            <?php }?>
                        </div>
                      
                        <div class="collapse1 copy_area relative padding_t10">
                                <button class="btn copy_btn active copy_reference"  type="button" data-bs-toggle="collapse" data-bs-target="#collapseExample1" aria-expanded="false" aria-controls="collapseExample1" style="display: flex;" disabled id="js_copy_site">
                                    <img src="<?=PATH?>public/img/copy.png" alt="" class="domain_info_icon">
                                    <p style="margin: 0; width: 74%; overflow: hidden; text-align: left;" class="label">コピーサイト参照先</p>
                                </button>
                                <!-- ドメイン参照 -->
                                <div class="btn copy_btn"  style="display: none;" id="reference">
                                    <img src="<?=PATH?>public/img/copy.png" alt="" class="domain_info_icon">
                                    <p style="margin: 0;" class="label"></p>
                                </div>
                                <!-- END -->
                                
                                <div class="collapse" id="collapseExample1">
                                    <div class="card card-body">
                                        <!-- 検索 -->
                                        <div class="domain_serach_container" id="js_domain_search">
                                            <div class="domain_serch_container_top">
                                                <img src="<?=PATH?>public/img/Vector.png" alt="" class="serach_icon">
                                                <input type="text" class="domain_name_search_input" placeholder="ドメイン検索" id="js_search_domain">
                                            </div>
                                            <div class="domain_search_results_container">
                    
                                            </div>
                                            
                                        </div>
                                    </div>
                                </div>
                            </div>

                        
                        <div class="tagInfo_area">
                            <div class="padding_t30"></div>
                            <p class="label">タグ情報　<span class="red label">*必須</span></p>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" id="tag_new" value="new" name="tag_types">
                                <label class="form-check-label" for="tag_new">タグ新規</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" id="tag" value="reference" name="tag_types">
                                <label class="form-check-label" for="tag">タグ引継ぎ</label>
                            </div>
                            <div class="form-check form-check-inline js_withoutTag">
                                <input class="form-check-input js_tagCheck" type="radio" value="withoutTag" id="flexCheckDefault" name="tag_types">
                                <label class="form-check-label" for="flexCheckDefault">タグ参照</label>
                            </div>
                        </div>
                        
                        <div class="option_btn_container" style="margin-top: 10px;">
                            <div class="collapse1 tag_area relative">
                                <button class="btn copy_btn active tag_reference" type="button" data-bs-toggle="collapse" data-bs-target="#tag_reference_collapse" aria-expanded="false" aria-controls="collapseExample2" style="display: flex;" id="js_copy_tag" disabled>
                                    <img src="<?=PATH?>public/img/copy.png" alt="" class="domain_info_icon">
                                    <p style="margin: 0;width: 74%; overflow: hidden; text-align: left;" class="label">タグ参照先</p>
                                </button> 
                                <div class="collapse" id="tag_reference_collapse">
                                    <div class="card card-body">
                                        <!-- ドメイン表示-->
                                        <div class="domain_serach_container" id="js_domain_search">
                                            <div class="domain_serch_container_top">
                                                <img src="<?=PATH?>public/img/Vector.png" alt="" class="serach_icon">
                                                <input type="text" class="domain_name_search_input" placeholder="ドメイン検索" id="js_search_tag">
                                            </div>
                                            <div class="tag_search_results_container">
                                            
                                            </div>
                                            
                                        </div>
                                    </div>
                                </div>
                            </div>  
                        </div>
                    </div>
                </div>  
            </div>
        </div>
    </div>

    <!-- カテゴリー追加フォーム -->
    <div class="category_add_modal-container hidden js_modals">
        <h3 class="text_color js_close_btns2" style="cursor: pointer; text-align: left;">×</h3> 
        <p class="label">カテゴリー追加</p>
        <form method="post" action="<?=PATH?>addCategory">
            <input type="text" class="domain_name_input border" id="category_name" placeholder="例: 他社" name="category_name">
            <input type="hidden" name="csrf_token" value=<?=$_SESSION['csrf_token']?>>
            <button class="domain_btn">追加</button>
        </form>
        <div class="padding_t30"> </div>
        <div class="border-through"></div>
        <div class="padding_t30"> </div>
        <p class="label">カテゴリー編集</p>
        
        <?php foreach($domain_types as $type) {?>
            <form method="post" action="<?=PATH?>editCategory">
                <input type="text" class="domain_name_input border js_category_form" value="<?=$type["domain_type_name"]?>" id="category_name" placeholder="例: 他社" name="category_name">
                <input type="hidden" value="<?=$type["id"]?>" name="category_id">
                <button class="domain_btn js_edit_category">編集</button>
                <input type="hidden" name="csrf_token" value=<?=$_SESSION['csrf_token']?>>
            </form>
            <div class="padding_t5"></div>
        <?php }?>
        
       
    </div>

    <!-- ドメイン編集、削除などのモーダル -->
    <div class="admin_modal_container fixed hidden js_alert_modal js_modals">
    <h3 class="text_color js_close_btns2" style="cursor: pointer; text-align: left; margin: 0%; padding-left: 8%;">×</h3> 
        <input type="hidden" id="js_selectedID" value="">
        <div class="domains_edit">
            <input type="hidden" class="js_selectedID" value="">
            <img src="<?=PATH?>public/img/edit.svg" alt="" class="icon">
            <button style="margin: 0;" class="bold button_none" type="submit">編集</button>
        </div>
        <form class="modal_item" action="<?=PATH?>deactivate" method="post">
            <input type="hidden" class="js_selectedID" value="" name="domain_id">
            <input type="hidden" class="js_isActive" value="">
            <input type="hidden" id="active_status" value="" name="status">
            <input type="hidden" name="csrf_token" value=<?=$_SESSION['csrf_token']?>>
            <button class="button_none" type="submit">
                <img src="<?=PATH?>public/img/cross-circle.svg" alt="" class="icon">
                <p style="margin: 0;" class="bold" id="active_check">使用しない</p>
            </button>
        </form>
        <div class="modal_item js_delete_tag">
            <img src="<?=PATH?>public/img/trash.png" alt="" class="icon">
            <p style="margin: 0;" class="bold red">タグ削除</p>
        </div>
        <div class="modal_item js_delete_domain">
            <img src="<?=PATH?>public/img/trash.png" alt="" class="icon">
            <p style="margin: 0;" class="bold red">ドメイン削除</p>
        </div>
    </div>
    

    <!-- ドメイン削除モーダル -->
    <div class="domain_delete_modal_container fixed hidden js_alert_modal">
        <h5 class="bold">本当に削除しますか？</h5>
        <div class="padding_t20"></div>
        <p class="modal-font-size"><span class="red bold ">重要</span>: このドメインを削除すると、それに関連するすべてのタグも同時に削除されます。この操作は元に戻すことができません。選択されたドメインを本当に削除してもよろしいですか？</p>
        <div class="padding_t10"></div>
        <div class="modal_btn_container">
        <form class="js_deleteDomain_form" action="<?=PATH?>deleteDomain" method="post">
            <input type="hidden" class="js_selectedID" value="" name="domain_id">
            <input type="hidden" name="csrf_token" value=<?=$_SESSION['csrf_token']?>>
            <button class="modal_btn bg-red bold js_delete" style="color: white;">削除</button> 
        </form>
           
        </div>
        <div class="padding_t20"></div>
        <div class="modal_btn_container">
           <button class="modal_btn bold bg-white border js_delete_btn">いいえ</button> 
        </div>
    </div>

    <img src="<?=PATH?>public/img/loading.gif" alt="" class="loading hidden">

    <!-- タグ削除モーダル -->
    <div class="tag_delete_modal_container fixed hidden js_alert_modal">
        <h5 class="bold">本当に削除しますか？</h5>
        <div class="padding_t20"></div>
        <p class="modal-font-size"><span class="red bold ">重要</span>: このタグを削除すると、このタグを使用しているすべてのドメインからもタグが削除されます。この操作は取り消すことができません。本当にこのタグを削除してよろしいですか？</p>
        <div class=""></div>
        <p class="modal-font-size index_txt" style="margin-bottom: 1%;">(タグ参照ドメイン一覧)</p>
        <div class="domain_index">
            <a href="">example.com</a>
        </div>
        <div class="padding_t10"></div>
        <div class="modal_btn_container">
        <form class="js_deleteTag_form" action="<?=PATH?>deleteTagIndex" method="post">
            <input type="hidden" class="js_selectedID" value="" name="id">
            <input type="hidden" name="csrf_token" value=<?=$_SESSION['csrf_token']?>>
            <input type="hidden" name="referrer" value="<?= $_SERVER['REQUEST_URI']?>">
            <input type="hidden" value="withCode" name="type">
            <button class="modal_btn bg-red bold js_delete" style="color: white;">削除</button> 
        </form>
        </div>
        <div class="padding_t20"></div>
        <div class="modal_btn_container cancel_process">
           <button class="modal_btn bold bg-white border js_delete_btn">いいえ</button> 
        </div> 
    </div>

    <!-- ドメイン情報設定モーダル-->
    <div class="domain_setting_modal fixed hidden" id="js_info_modal">
        <h4 class="bold">ドメイン情報設定について</h4>
        <div class="padding_t10"></div>
        <p style="margin-bottom: 5px;">1. コピーサイトの選択</p>
        <p class="modal-font-size padding_left"><span class="bold">・オリジナルサイトの選択</span>: コピーサイトを設定する際、まずはその基となるオリジナルサイトを選択してください。</p>
        <div class="padding_t10"></div>
        <p style="margin-bottom: 5px;">2. タグ引継ぎ設定</p>
        <p class="modal-font-size padding_left"><span class="bold">・タグ引継ぎの選択</span>: コピーサイトに対して、オリジナルサイトからタグ設定を引き継ぐかどうかを選択できます。引き継がない場合は、新規を選択してください。タグを参照したい場合は、タグ参照を選択してください。</p>
        <div class="padding_t10"></div>
        <p style="margin-bottom: 5px;">3. タグ引継ぎ元の詳細</p>
        <p class="modal-font-size padding_left"><span class="bold">・引継ぎ元の詳細選択</span>: タグを引き継ぐ場合、具体的にどのオリジナルサイト、またはそのオリジナルサイトのコピーサイトからタグを引き継ぐかを選択してください。この選択により、コピーサイトのタグ設定が決定されます。</p>
    </div>

    <!-- script表示 -->
    <div class="script_area fixed hidden" id="js_script_modal">
        <?php include(dirname(__FILE__) . "/scriptTag_show.php")?>
    </div>

    

    <!-- テスト環境 -->
    <script type="module" src="<?=PATH?>dist/sortDomainDataByCategory.js"></script>
    <script type="module" src="<?=PATH?>dist/domainManagement.js"></script>
    <script type="module" src="<?=PATH?>dist/infiniteScroll.js"></script>
   
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js" integrity="sha384-fbbOQedDUMZZ5KreZpsbe1LCZPVmfTnH7ois6mU1QK+m14rQ1l2bGBq41eYeM/fS" crossorigin="anonymous"></script>
</body>
</html>