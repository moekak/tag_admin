<?php

$domainData = "";
if(isset($_SESSION["domainData"])){
    $domainData = $_SESSION["domainData"];
}


// print_r($domainData);

$tagData = "";
if(isset($_SESSION["tagData"])){
    $tagData = $_SESSION["tagData"];
}

// print_r($_SESSION["tagData"]);
// exit;

$tagDataWithRange = "";
if(isset($_SESSION["tagDataWithRange"])){
    $tagDataWithRange = $_SESSION["tagDataWithRange"];
}

$copySites = "";
if(isset($_SESSION["copySites"])){
    $copySites = $_SESSION["copySites"];
}

$directorySites = "";
if(isset($_SESSION["sirectorySites"])){
    $directorySites = $_SESSION["sirectorySites"];
}

$input_error = "";
if(isset($_SESSION["input_error"])){
    $input_error = $_SESSION["input_error"];
    unset($_SESSION["input_error"]);
} 

$success_msg = "";
if(isset($_SESSION["sucess_msg_tagShow"])){
    $success_msg = $_SESSION["sucess_msg_tagShow"];
    unset($_SESSION["sucess_msg_tagShow"]);
} 


$parent_domain = "";
if(isset($_SESSION["parent_domain"])){
    $parent_domain = $_SESSION["parent_domain"];
}

$original_domain = "";
if(isset($_SESSION["original_domain"])){
    $original_domain = $_SESSION["original_domain"];
}

$copyAll_domain = "";
if(isset($_SESSION["copySiteAll"])){
    $copyAll_domain = $_SESSION["copySiteAll"];
}




?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <link rel="stylesheet" href="<?=PATH?>public/css/domain_show.css">
    <link rel="stylesheet" href="<?=PATH?>public/css/common.css">
    <link rel="stylesheet" href="<?=PATH?>public/css/script.css">
    <link rel="shortcut icon" href="<?= PATH ?>favicon.ico">
    <script src="https://kit.fontawesome.com/49c418fc8e.js" crossorigin="anonymous"></script>
    
    <title>タグ管理画面</title>
</head>
<body>
    
    <div class="domain_wrapper relative">
        <p id="js_success" class="green bold hidden"><?= $success_msg?></p>
        <input type="hidden" value="<?=$domainData["id"]?>" id="domain_id">
        <div class="bg-gray absolute hidden"></div>
        <div class="domain_top_container domain_show container_paddingRL">
            <div class="domain_show_title">
                <p class="white margin_0">ドメイン名</p>
                <h2 class="bold"><?=$domainData["domain_name"]?></h2>
            </div>
        </div>
        <div class="main_wrapper">

            <div class="menu_bar sticky">
                <div class="menu js_tag_btn js_menu_btn">
                    <img src="<?=PATH?>public/img/tag.png" alt="" class="menu-icon">
                    <p class="white margin_0">タグ一覧</p>
                </div>
                <div class="menu js_copy_btn js_menu_btn">
                    <img src="<?=PATH?>public/img/copy2.png" alt="" class="menu-icon">
                    <p class="white margin_0">コピーサイト</p>
                </div>
                <div class="menu js_directory_btn js_menu_btn">
                    <img src="<?=PATH?>public/img/copy2.png" alt="" class="menu-icon">
                    <p class="white margin_0">ディレクトリ別</p>
                </div>
                <div class="menu js_addTag_btn js_menu_btn">
                    <img src="<?=PATH?>public/img/plus.png" alt="" class="menu-icon">
                    <p class="white margin_0">追加</p>
                </div>
                <div >
                    <a href="<?=PATH?>index" class="white textdecoration_none menu">
                        <img src="<?=PATH?>public/img/undo.png" alt="" class="menu-icon">
                        <p class="white margin_0">ドメイン一覧</p>
                    </a>
                </div>
                <div >
                    <span class="white textdecoration_none menu js_script_tag">
                        <img src="<?=PATH?>public/img/tag.png" alt="" class="menu-icon">
                        <p class="white margin_0" id="js_script_name"><?=  isset($domainData["tag_reference_randomID"]) ? $domainData["tag_reference_randomID"]: $domainData["random_domain_id"] ?></p>
                    </span>
                </div>

                <?php if($domainData["domain_type"] !== "original"){?>
                <div style="margin-top: 103%;">
                     <div class="LPName_container">
                        <p class="lp_title">親ドメインLP</p>
                        <p class="lp_name"><?= $parent_domain?></p>
                    </div>
                    <div class="LPName_container">
                        <p class="lp_title">オリジナルLP</p>
                        <p class="lp_name"><?= $original_domain?></p>
                    </div>
                </div>
                <?php }?>
            </div>
            <div class="ad_area js_ad_code">
                <!-- 広告コード一覧 -->
                <div class="tag_index_container ad_container">
                    <div class="tag_index_box">
                        <p class="box_paddingRL">広告コード一覧</p>
                        <div class="box_paddingRL scrollbar_none">
                            <table class="table">  
                                <thead style="position: sticky; top: 0;">
                                    <tr>
                                        <th scope="col">名前</th>
                                        <th scope="col">配信トリガー</th>
                                        <th scope="col">最終更新</th>
                                        <th scope="col"></th>
                                    </tr>
                                </thead>
                                <tbody class="js_table" id="js_table">
                                    <?php foreach($tagData as $tag) {?>
                                        <tr>
                                            <td class="align-middle"><a href="<?=PATH?>tag/?id=<?=$tag["id"]?>" class="textdecoration_none"><?= ($tag["ad_code"] ? $tag["ad_code"] : $tag["trigger_type"])?></a></td>
                                            <td class="align-middle">
                                                <button class="trigger_btn">
                                                    <img src="<?=PATH?>public/img/eye.png" alt="" class="eye">
                                                    <p class="label margin_0">
                                                        <?php
                                                            if($tag["trigger_type"] !== "all_pages" && strpos($tag["trigger_type"], "all_") !== false){
                                                                $trigger = str_replace("all_", "", $tag["trigger_type"]);
                                                            }else{
                                                                $trigger = $tag["trigger_type"];
                                                            }
                                                             echo $trigger;
                                                        
                                                        ?>
                                                    </p>
                                                </button>
                                            </td>
                                            <td class="align-middle">
                                                <?php
                                                    $date = new DateTime($tag["updated_at"]);
                                                    $formattedDate = $date->format('Y年m月d日');
                                                    echo $formattedDate
                                                ?>
                                            </td>
                                            <td class="align-middle pointer edit_modal_btns" data-id="<?=$tag["id"]?>" data-type="<?= ($tag["ad_code"] ? "withCode" : "withoutCode")?>">
                                                <i class="fas fa-ellipsis-v text_color"></i>
                                                <input type="hidden" data-tag-id = "<?=$tag["id"]?>">
                                            </td>
                                        </tr>
                                    <?php }?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <!-- 広告コード範囲一覧 -->
                <div class="tag_index_container">
                    <div class="tag_index_box">
                        <p class="box_paddingRL">広告コード範囲一覧</p>
                        <div class="box_paddingRL scrollbar_none">
                            <table class="table">  
                                <thead style="position: sticky; top: 0;">
                                    <tr>
                                        <th scope="col">名前</th>
                                        <th scope="col">配信トリガー</th>
                                        <th scope="col">最終更新</th>
                                        <th scope="col"></th>
                                    </tr>
                                </thead>
                                <tbody class="js_table" id="js_table2">
                                    <?php foreach($tagDataWithRange as $tag) {?>
                                        <tr>
                                            <td class="align-middle">
                                                <a href="<?=PATH?>tagRange/?id=<?=$tag["id"]?>" class="textdecoration_none">
                                                    <?php 
                                                        $json = json_decode($tag["code_range"]);
                                                        $start = reset($json);
                                                        $end = end($json);
                                                        $exclude = "";

                                                        if(json_decode($tag["excluded_code"])){
                                                            echo $start . "～" . $end . "(除外コードあり)";
                                                        } else{
                                                            echo $start . "～" . $end;
                                                        }
                                                    ?>
                                                </a>
                                            </td>
                                            <td class="align-middle">
                                                <button class="trigger_btn">
                                                    <img src="<?=PATH?>public/img/eye.png" alt="" class="eye">
                                                    <p class="label margin_0">
                                                        <?php
                                                            if(strpos($tag["trigger_type"], "all_") !== false){
                                                                $trigger = str_replace("all_", "", $tag["trigger_type"]);
                                                            }else{
                                                                $trigger = $tag["trigger_type"];
                                                            }
                                                             echo $trigger;
                                                        
                                                        ?>
                                                        
                                                    </p>
                                                </button>
                                            </td>
                                            <td class="align-middle">
                                                <?php
                                                    $date = new DateTime($tag["updated_at"]);
                                                    $formattedDate = $date->format('Y年m月d日');
                                                    echo $formattedDate
                                                ?>
                                            </td>
                                            <td class="align-middle pointer edit_modal_btns" data-id="<?=$tag["id"]?>" data-type="withCodeRange">
                                                <i class="fas fa-ellipsis-v text_color"></i>
                                                <input type="hidden" data-tag-id = "<?=$tag["id"]?>" >
                                            </td>
                                        </tr>
                                    <?php }?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <!-- コピーサイト一覧 -->
            <div class="tag_index_container js_copy_site hidden">
                <div class="tag_index_box">
                    <p class="box_paddingRL">コピーサイト</p>
                    <div class="box_paddingRL scrollbar_none">
                        <table class="table">  
                            <thead style="position: sticky; top: 0;" id="js_table">
                                <tr>
                                    <th scope="col">ドメイン名</th>
                                    <th scope="col">ステータス</th>
                                    <th scope="col">ドメインID</th>
                                </tr>
                            </thead>
                            <tbody class="js_table">
                           
                                <?php foreach($domainData["domain_type"] === "original" ? $copyAll_domain : $copySites as $copySite) {?>
                                    <tr>
                                        <td class="domain_name align-middle">
                                            <a href="<?=PATH?>showTag/?id=<?=$copySite["id"]?>" class="textdecoration_none"><?= $copySite["domain_name"]?></a>
                                        </td>
                                        <td class="original_check align-middle <?= $copySite["is_active"] === "1" ? "green" : "red"?>">
                                            <?= $copySite["is_active"] === "1" ? "使用" : "未使用"?>
                                        </td>
                                        <td class="text_color align-middle"><?= $copySite["random_domain_id"] !== "0" ? $copySite["random_domain_id"] : ""?></td>
                                    </tr>
                                <?php }?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!-- ディレクトリ別一覧 -->
            <div class="tag_index_container js_directory_site hidden">
                <div class="tag_index_box">
                    <p class="box_paddingRL">ディレクトリ別</p>
                    <div class="box_paddingRL scrollbar_none">
                        <table class="table">  
                            <thead style="position: sticky; top: 0;" id="js_table">
                                <tr>
                                    <th scope="col">ドメイン名</th>
                                    <th scope="col">ステータス</th>
                                    <th scope="col">ドメインID</th>
                                </tr>
                            </thead>
                            <tbody class="js_table">
                                <?php foreach($directorySites as $directorySite) {?>
                                    <tr>
                                        <td class="domain_name align-middle">
                                            <a href="<?=PATH?>showTag/?id=<?=$directorySite["id"]?>" class="textdecoration_none"><?= $directorySite["domain_name"]?></a>
                                        </td>
                                        <td class="original_check align-middle <?= $directorySite["is_active"] === "1" ? "green" : "red"?>">
                                            <?= $directorySite["is_active"] === "1" ? "使用" : "未使用"?>
                                        </td>
                                        <td class="text_color align-middle"><?= $directorySite["random_domain_id"] !== "0" ? $directorySite["random_domain_id"] : ""?></td>
                                    </tr>
                                <?php }?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!-- タグ追加 -->
            <p id="js_error" class="red"><?= $input_error?></p>
            <div class="tag_index_container js_adtag_site hidden">
                <form class="tag_index_box myForm" action="<?=PATH?>addTag" method="post">
                    <div class="flex">
                        <p class="box_paddingRL">タグ追加</p>
                        <button class="add_btn disabled_btn" id="js_tagCreate_btn" style="margin-right: 5%;">追加</button>
                    </div>
                    
                    <div class="box_paddingRL padding_t20">
                        <div class="domain_name_input_box relative tag_form">
                            <p class="label">配信トリガー　<span class="red label light">*必須</span></p>
                            <div class="trigger_input relative">
                                <div class="dummy_up absolute hidden"></div>
                                <div class="dummy_down absolute"></div>
                                <input type="text" class="domain_name_input border transparent  js_trigger js_trigger_btn"  name="trigger_category" value=""  readonly>
                                <i class="fas fa-chevron-down absolute arrow js_arrowDown_btn pointer"></i>
                                <i class="fas fa-chevron-up absolute arrow js_arrowUp_btn hidden pointer" ></i>
                                <div class="trigger_btn absolute trigger_position trigger_msg">
                                    <img src="<?=PATH?>public/img/eye.png" alt="" class="eye">
                                    <p class="label margin_0">選択してください</p>
                                </div>
                            </div>
                            
                            <div class="trigger_category border js_trigger_modal">
                                <div class="trigger_btn pointer js_trigger_btns" data-type="index">
                                    <img src="<?=PATH?>public/img/eye.png" alt="" class="eye">
                                    <p class="label margin_0">index</p>
                                </div>
                                <div class="trigger_btn margin_t10 pointer js_trigger_btns" data-type="rd">
                                    <img src="<?=PATH?>public/img/eye.png" alt="" class="eye">
                                    <p class="label margin_0">rd</p>
                                </div>

                                <div class="trigger_btn margin_t10 pointer js_trigger_btns" data-type="all_pages">
                                    <img src="<?=PATH?>public/img/eye.png" alt="" class="eye">
                                    <p class="label margin_0">all_pages</p>
                                </div>
                            </div>
                        </div>
                        <div class="flex_trigger">
                            <div class="domain_name_input_box relative margin_t30 js_adCode" style="width: 100%;">
                                <p class="label">広告コード</p>
                                <div class="input_container">
                                    <div class="input_box" style="margin-right: 5%;">
                                        <p class="label" style="margin-bottom: 0;">コード</p>
                                        <input type="text" class="domain_name_input border js_ad_field js_data_field js_input" style="width: 160px;"  name="ad_code" placeholder="(例)aaa※半角数字">
                                        <div class="form-check margin_t10">
                                            <input class="form-check-input" type="checkbox" value="" id="apply_all">
                                            <label class="form-check-label label" for="apply_all">
                                                全ページに適用
                                            </label>
                                        </div>
                                        <p class="alert_txt red hidden">半角文字で入力してください</p>
                                    </div>
                                    <div class="input_box" style="margin-right: 2%;">
                                        <p class="label" style="margin-bottom: 0;">スタート番号</p>
                                        <input type="text" class="domain_name_input border js_adNum_field js_data_field js_input" style="width: 160px;"  name="ad_num" placeholder="(例)001※半角数字">
                                        <p class="alert_txt red hidden">半角数字で入力してください</p>
                                    </div>
                                    <div class="input_box input_txt" style="width: 60px; margin-right: 2%;">
                                        <p class="label">から</p>
                                    </div>
                                    <div class="input_box" style="margin-right: 5%;">
                                        <p class="label" style="margin-bottom: 0;">作成数</p>
                                        <input type="text" class="domain_name_input border js_adRange_field js_data_field" js_input style="width: 160px;"  name="ad_range" placeholder="1～100まで">
                                        <p class="alert_txt red hidden">半角数字, 1～100までの数字で入力してください</p>
                                    </div>
                                </div>
                                
                                
                            </div>
                        </div>
                        <!-- <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault" name="delivered_trigger">
                            <label class="form-check-label label" for="flexCheckDefault">
                                離脱クリック
                            </label>
                        </div> -->
                        
                        <p class="label margin_t30">タグ</p>
                        <div class="textarea">
                            <p class="label">&lt;head&gt;&lt;/head&gt;内に貼るタグ</p>
                            <div class="tag_textarea" data-id="1">
                                <div id="editor1" class="js_tag_field" data-id="0" style="height: 300px;"></div>
                            </div>
                        </div>
                        <div class="textarea margin_t30">
                            <p class="label">&lt;body&gt;&lt;/body&gt;内に貼るタグ</p>
                            <div class="tag_textarea" data-id="2">
                                <div id="editor2" class="js_tag_field" data-id="0" style="height: 300px;"></div>
                            </div>
                        </div>
                        <input type="hidden" name="type" value="" id="js_type">
                        <input type="hidden" name="referrer" value="<?= $_SERVER['REQUEST_URI']?>">
                        <input type="hidden" id="tag_head" name="tag_head">
                        <input type="hidden" id="tag_body" name="tag_body">
                        <input type="hidden" name="domain_id" value="<?=isset($domainData["tag_reference_id"]) ? $domainData["tag_reference_id"]: $domainData["id"]?>">
                        <input type="hidden" name="csrf_token" value=<?=$_SESSION['csrf_token']?>>
                    </div>
                </form>
            </div>
        </div>
         <!-- ドメイン編集、削除などのモーダル -->
        <div class="admin_modal_container hidden fixed js_alert_modal js_modals">
            <h3 class="text_color js_close_btns2" style="cursor: pointer; text-align: left; margin: 0%; padding-left: 8%;">×</h3> 
            <input type="hidden" id="js_selectedID" value="">
            <div class="modal_item js_delete_tag pointer">
                <input type="hidden" class="js_selectedID" value="">
                <img src="<?=PATH?>public/img/trash.png" alt="" class="icon">
                <p style="margin: 0;" class="bold red">削除</p>
            </div>
        </div>
        <!-- タグ削除モーダル -->
        <div class="tag_delete_modal_container hidden fixed js_alert_modal hidden">
            <h5 class="bold">本当に削除しますか？</h5>
            <div class="padding_t20"></div>
            <p class="modal-font-size"><span class="red bold ">重要</span>: 選択した広告コードを削除すると、このコードに関連づけられている全てのタグも同時に削除されます。この操作は元に戻すことができません。広告コードを本当に削除しますか？</p>
            <div class="padding_t10"></div>
            <div class="modal_btn_container">
            <form class="js_deleteTag_form" action="<?=PATH?>deleteTagShow" method="post">
                <input type="hidden" class="js_selectedID" value="" name="id">
                <input type="hidden" class="js_range" value="" name="type">
                <input type="hidden" name="csrf_token" value=<?=$_SESSION['csrf_token']?>>
                <input type="hidden" name="referrer" value="<?= $_SERVER['REQUEST_URI']?>">
                <button class="modal_btn bg-red bold js_delete" style="color: white;">削除</button> 
            </form>
            </div>
            <div class="padding_t20"></div>
            <div class="modal_btn_container">
            <button class="modal_btn bold bg-white border js_delete_btn">いいえ</button> 
            </div> 
        </div>

            <!-- script表示 -->
        <div class="script_area fixed hidden" id="js_script_modal">
            <?php include(dirname(__FILE__) . "/scriptTag_show.php")?>
        </div>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/ace/1.2.0/ace.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/ace/1.2.0/ext-language_tools.js"></script>
    <script type="module" src="<?=PATH?>dist/domainDetail.js"></script>
    <script type="module" src="<?=PATH?>dist/tagManagement.js"></script>
    <script>
        document.querySelector('.myForm').addEventListener('keypress', function(event) {
            if (event.key === 'Enter') {
            event.preventDefault();  // エンターキーでのフォーム送信を防止
            }
        });
    </script>



</body>
</html>