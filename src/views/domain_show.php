<?php

$domainData = "";
if(isset($_SESSION["domainData"])){
    $domainData = $_SESSION["domainData"];
}


$copySites = "";
if(isset($_SESSION["copySites"])){
    $copySites = $_SESSION["copySites"];
}

$directorySites = "";
if(isset($_SESSION["sirectorySites"])){
    $directorySites = $_SESSION["sirectorySites"];
}

$parent_domain = "";
if(isset($_SESSION["parent_domain"])){
    $parent_domain = $_SESSION["parent_domain"];
}

$original_domain = "";
if(isset($_SESSION["original_domain"])){
    $original_domain = $_SESSION["original_domain"];
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
    <link rel="shortcut icon" href="<?= PATH ?>favicon.ico">
    <script src="https://kit.fontawesome.com/49c418fc8e.js" crossorigin="anonymous"></script>
    
    <title>タグ管理画面</title>
</head>
<body>
    <div class="domain_wrapper relative">
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
                <div class="menu js_copy_btn js_menu_btn">
                    <img src="<?=PATH?>public/img/copy2.png" alt="" class="menu-icon">
                    <p class="white margin_0">コピーサイト</p>
                </div>
                <div class="menu js_directory_btn js_menu_btn">
                    <img src="<?=PATH?>public/img/copy2.png" alt="" class="menu-icon">
                    <p class="white margin_0">ディレクトリ別</p>
                </div>
                <div>
                    <a href="<?=PATH?>index" class="white textdecoration_none menu">
                        <img src="<?=PATH?>public/img/undo.png" alt="" class="menu-icon">
                        <p class="white margin_0">ドメイン一覧</p>
                    </a>
                </div>
                <?php if($parent_domain !== "" && $original_domain !== ""){?>
                <div style="margin-top: 200%;">
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
            <!-- コピーサイト一覧 -->
            <div class="tag_index_container js_copy_site ">
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
                                <?php foreach($copySites as $copySite) {?>
                                    <tr>
                                        <td class="domain_name align-middle"><a href="<?=PATH?>show/?id=<?=$copySite["id"]?>" class="textdecoration_none"><?= $copySite["domain_name"]?></a></td>
                                        <td class="original_check align-middle <?= $copySite["is_active"] === "1" ? "green" : "red"?>">
                                            <?= $copySite["is_active"] === "1" ? "使用" : "未使用"?>
                                        </td>
                                        <td class="text_color align-middle"><?= $copySite["random_domain_id"]?></td>
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
                                            <?php if($directorySite["random_domain_id"] === "0"){?>
                                                <a href="<?=PATH?>show/?id=<?=$directorySite["id"]?>" class="textdecoration_none"><?= $directorySite["domain_name"]?></a>
                                            <?php } else {?>
                                                <a href="<?=PATH?>showTag/?id=<?=$directorySite["id"]?>" class="textdecoration_none"><?= $directorySite["domain_name"]?></a>
                                            <?php }?>
                                            
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
        </div>
    </div>
    <script src="<?=PATH?>dist/domainShow.js"></script>
</body>
</html>