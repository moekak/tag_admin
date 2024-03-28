<?php

$domainData = "";
if(isset($_SESSION["domainData"])){
    $domainData = $_SESSION["domainData"];
}

$tagData = "";
if(isset($_SESSION["tagData"])){
    $tagData = $_SESSION["tagData"];
}

$input_error = "";
if(isset($_SESSION["input_error"])){
    $input_error = $_SESSION["input_error"];
    unset($_SESSION["input_error"]);
} 


$completeAdCode= "";
if(isset($tagData["ad_code"])){
    $completeAdCode = $tagData["ad_code"];
    preg_match('/[a-zA-Z]+/',$completeAdCode, $lettersMatch);
    preg_match('/\d+/',$completeAdCode, $numbersMatch);
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
    <script src="https://kit.fontawesome.com/49c418fc8e.js" crossorigin="anonymous"></script>
    
    <title>Document</title>
</head>
<body>
    <div class="domain_wrapper relative">
        <div class="bg-gray absolute hidden"></div>
        <div class="domain_top_container domain_show container_paddingRL">
            
            <div class="showTitle">
                <div class="domain_show_title">
                    <p class="white margin_0">ドメイン名</p>
                    <h2 class="bold"><a href="<?=PATH?>show/?id=<?=$domainData["id"]?>" class="textdecoration_none white"><?=$domainData["domain_name"]?></a></h2>
                </div>
                <p>/</p>
                <div class="tag_title">
                    <p class="white margin_0">広告コード</p>
                    <h2 class="bold"><a href="<?=PATH?>tag/?id=<?=$tagData["id"]?>" class="textdecoration_none white"><?=$tagData["ad_code"]?></a></h2>
                </div>
            </div>
            
        </div>
        <div class="main_wrapper">
            <div class="menu_bar sticky">
                <div>
                    <a href="<?=PATH?>showTag/?id=<?=$domainData["id"]?>" class="white textdecoration_none menu">
                        <img src="<?=PATH?>public/img/undo.png" alt="" class="menu-icon">
                        <p class="white margin_0">戻る</p>
                    </a>
                    <!-- <a href="/tag_admin/show/?id=<?=$domainData["id"]?>" class="white textdecoration_none"></a> -->
                </div>
                <div>
                    <a href="<?=PATH?>index" class="white textdecoration_none menu">
                        <img src="<?=PATH?>public/img/undo.png" alt="" class="menu-icon">
                        <p class="white margin_0">ドメイン一覧</p>
                    </a>
                </div>
            </div>
            <!-- タグ追加 -->
            <p id="js_error" class="red"><?= $input_error?></p>
            <div class="tag_index_container js_adtag_site">
                <form class="tag_index_box" action="<?=PATH?>editTag" method="post">
                    <div class="flex">
                        <p class="box_paddingRL">タグ(個別) / 編集</p>
                        <button class="add_btn disabled_btn" id="js_tagCreate_btn" style="margin-right: 5%;">更新</button>
                    </div>
                    
                    <div class="box_paddingRL padding_t20">
                        <div class="domain_name_input_box relative tag_form">
                            <p class="label">配信トリガー　<span class="red label light">*必須</span></p>
                            <div class="trigger_input relative">
                                <input type="text" class="domain_name_input border transparent js_trigger disabledInput"  name="trigger_category" value="<?=$tagData["trigger_type"]?>"  readonly>
                                <!-- <i class="fas fa-chevron-down absolute arrow js_arrowDown_btn pointer"></i>
                                <i class="fas fa-chevron-up absolute arrow js_arrowUp_btn hidden pointer" ></i> -->
                                <div class="trigger_btn absolute trigger_position trigger_msg">
                                    <img src="<?=PATH?>public/img/eye.png" alt="" class="eye">
                                    <p class="label margin_0">
                                        <?php
                                            if($tagData["trigger_type"] !== "all_pages" && strpos($tagData["trigger_type"], "all_") !== false){
                                                $trigger = str_replace("all_", "", $tagData["trigger_type"]);
                                            }else{
                                                $trigger = $tagData["trigger_type"];
                                            }
                                                echo $trigger;
                                        ?>
                                    </p>
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
                                        <input type="text" class="domain_name_input border js_ad_field js_data_field disabledInput" style="width: 160px;" readonly  name="ad_code" placeholder="(例)aaa※半角数字" value="<?=isset($lettersMatch[0]) ? $lettersMatch[0] : "" ?>">
                                        <div class="form-check margin_t10">
                                            <input class="form-check-input" type="checkbox" value="" id="apply_all" <?= $tagData["ad_code"] === "" ? "checked" : ""?> disabled>
                                            <label class="form-check-label label" for="apply_all">
                                                全ページに適用
                                            </label>
                                        </div>
                                        <p class="alert_txt red hidden">半角文字で入力してください</p>
                                    </div>
                                    <div class="input_box" style="margin-right: 2%;">
                                        <p class="label" style="margin-bottom: 0;">スタート番号</p>
                                        <input type="text" class="domain_name_input border js_adNum_field js_data_field disabledInput" style="width: 160px;" readonly name="ad_num" placeholder="(例)001※半角数字" value="<?=isset($numbersMatch[0]) ? $numbersMatch[0] : "" ?>">
                                        <p class="alert_txt red hidden">半角数字で入力してください</p>
                                    </div>
                                    <div class="input_box input_txt" style="width: 60px; margin-right: 2%;">
                                        <p class="label">から</p>
                                    </div>
                                    <div class="input_box" style="margin-right: 5%;">
                                        <p class="label prohibited" style="margin-bottom: 0;">作成数</p>
                                        <input type="text" class="domain_name_input border js_adRange_field prohibited js_data_field disabledInput"  style="width: 160px;"  name="ad_range" placeholder="1～100まで" readonly value="1">
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
                            <div class="tag_textarea" data-id="1">
                                <div id="editor1" class="js_tag_field" data-id="0" style="height: 300px;"></div>
                            </div>
                        </div>
                        <input type="hidden" id="tag_headData" value="<?= htmlspecialchars($tagData["tag_head"]) ?>">
                        <div class="textarea margin_t30">
                            <div class="tag_textarea" data-id="2">
                                <div id="editor2" class="js_tag_field" data-id="0" style="height: 300px;"></div>
                            </div>
                        </div>
                        <input type="hidden" id="tag_bodyData" value="<?= htmlspecialchars($tagData["tag_body"]) ?>">
                        <input type="hidden" name="type" value="" id="js_type">
                        <input type="hidden" name="referrer" value="<?= $_SERVER['REQUEST_URI']?>">
                        <input type="hidden" id="tag_head" name="tag_head">
                        <input type="hidden" id="tag_body" name="tag_body">
                        <input type="hidden" name="domain_id" value="<?=isset($domainData["tag_reference_id"])? $domainData["tag_reference_id"]: $domainData["id"]?>">
                        <input type="hidden" name="tag_id" value="<?=$tagData["id"]?>">
                        <input type="hidden" name="csrf_token" value=<?=$_SESSION['csrf_token']?>>
                    </div>
                </form>
            </div>
        </div>
        
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/ace/1.2.0/ace.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/ace/1.2.0/ext-language_tools.js"></script>
    <script type="module" src="<?=PATH?>dist/domainDetail.js"></script>
    <script type="module" src="<?=PATH?>dist/tagManagement.js"></script>
    <script type="module" src="<?=PATH?>dist/tagDetail.js"></script>
    <script>
        //ユニコードエスケープシーケンスの変換
        function decodeUnicodeEscapeSequence(encodedString) {
            return encodedString.replace(/\\u([0-9a-fA-F]{4})/g, function(match, grp) {
                return String.fromCharCode(parseInt(grp, 16));
            });
        }

        window.onload = function(){
            var editor = ace.edit("editor1");
            // HTMLエンティティに変換をしないとそもそもデータが取得できない
            // HTMLエンティティに変換された文字列(ダブルクオーテーションが変換されてるため、json文字列になっていない)
            const tagDataString = document.getElementById("tag_headData").value


            
            // HTMLエンティティに変換された文字列をHTMLエンティティのデコードし、ユニコードエスケープシーケンスの変換(scriptタグの形にしてjsonの配列の形にする)
            if(tagDataString !== ""){
                const decoded = JSON.parse(decodeUnicodeEscapeSequence(tagDataString))

                console.log(decoded);
                // 配列内の全ての要素を連結
                var combinedScripts = '';
                for (var i = 0; i < decoded.length; i++) {
                    combinedScripts += decoded[i];
                }
                editor.setValue(combinedScripts);
            }
            
           
            var editor2 = ace.edit("editor2");
            // HTMLエンティティに変換をしないとそもそもデータが取得できない
            // HTMLエンティティに変換された文字列(ダブルクオーテーションが変換されてるため、json文字列になっていない)
            const tagDataString2 = document.getElementById("tag_bodyData").value
            // HTMLエンティティに変換された文字列をHTMLエンティティのデコードし、ユニコードエスケープシーケンスの変換(scriptタグの形にしてjsonの配列の形にする)
            if(tagDataString2 !== ""){
                const decoded2 = JSON.parse(decodeUnicodeEscapeSequence(tagDataString2))
                // 配列内の全ての要素を連結
                var combinedScripts2 = '';
                for (var i = 0; i < decoded2.length; i++) {
                    combinedScripts2 += decoded2[i] + '\n'+ '\n' + '\n';
                }
                editor2.setValue(combinedScripts2);
            }  
        }
        

    </script>

</body>
</html>