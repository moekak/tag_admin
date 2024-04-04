import {displaySettingModal, tag_data, enableAddBtn } from "@index/index.js";


// ドメイン編集モーダルの初期化
const initializeTagEditModal = (event)=>{
    const input_selectedID  = document.querySelectorAll(".js_selectedID")
    const selectedID  = document.getElementById("js_selectedID")
    const input_checkType  = document.querySelector(".js_range")

    // 選択したドメインのIDの取得、設置
    let target      = event.currentTarget
    let selected_id = target.getAttribute("data-id")
    let type = target.getAttribute("data-type");

    
    input_selectedID.forEach((id)=>{
        id.value = selected_id
    })

    selectedID.value = selected_id
    input_checkType.value = type;

}

// ドメイン編集モーダル表示して初期化
export const displayEditModalAndInitializingForTag = ()=>{
    const modal_btn = document.querySelectorAll(".edit_modal_btns")

    // 各ドメインの設定ボタン(3点マーク)をクリックしたときの処理
    modal_btn.forEach((btn)=>{
        btn.addEventListener("click", (e)=>{
            //ドメイン編集モーダルの初期化
            initializeTagEditModal(e)
            // モーダルのドメイン削除文言の切り替え
            displaySettingModal()
            
        })
    })
}


export const setDataToObjWhenUpdateTagData = ()=>{

    const trigger_type = document.querySelector(".trigger_msg").querySelector(".label").innerHTML
    const ad_code = document.querySelector(".js_ad_field").value
    const ad_num = document.querySelector(".js_adNum_field").value
    const typeValue = document.getElementById("js_type")
    const range = document.querySelector(".js_adRange_field").value
    
    
    tag_data["ad_code"] = ad_code
    tag_data["ad_num"] = ad_num
    tag_data["trigger"] = trigger_type
    tag_data["ad_range"] = range

    if(tag_data["ad_range"] === ""){
        tag_data["applygingAll"] = "checked"
    } else{
        tag_data["applygingAll"] = "unchecked"
    }


    if(ad_code !== "" && ad_num !== "" && range !== "1"){
        typeValue.value = "withCodeRange"
    } else if(ad_code !== "" && ad_num !== "" && range === "1"){
        typeValue.value = "withCode"
    }else{
        typeValue.value = "withoutCode"
    }

    enableAddBtn()
}
