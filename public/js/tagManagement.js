

import {setDataToObjWhenUpdateTagData, acEditor, closeModal, checkTagTriggerType, clearTagDataObj, insertAdCodeAndTriggerToObj, addAlertStyle, changeArrowImg, disableCodeInputField, displayTriggerCategories, enableAddBtn, enableCodeInputField, hideTriggerCategories, removeAlertStyle, setSelectedTrigger, isHalfWidthChars, isHalfWidthNumber, isValidNum } from "@index/index.js"





// タグ追加のdropの操作の処理
{
    const btnDown = document.querySelector(".js_arrowDown_btn")
    const btnUp = document.querySelector(".js_arrowUp_btn")

    if(btnDown !== null){
        btnDown.addEventListener("click", ()=>{
            changeArrowImg(btnDown, btnUp)
            displayTriggerCategories()
        })
    }
    
    if(btnUp !== null){
        btnUp.addEventListener("click", ()=>{
            changeArrowImg(btnUp, btnDown)
            hideTriggerCategories()
        })  
    }
    
}

// 配信トリガー選択したときの処理
{
    const trigger_btns  = document.querySelectorAll(".js_trigger_btns")
    const btnDown       = document.querySelector(".js_arrowDown_btn")
    const btnUp         = document.querySelector(".js_arrowUp_btn")

    

    trigger_btns.forEach((btn)=>{
        btn.addEventListener("click", (e)=>{
           
            let target = e.currentTarget
            let type = target.getAttribute("data-type")


            setSelectedTrigger(type)
            insertAdCodeAndTriggerToObj("trigger", type)
            hideTriggerCategories()
            changeArrowImg(btnUp, btnDown)
            // disableAdInput()
            enableAddBtn()
            // checkTagTriggerType(typeValue)
        })
    })

}

// タグのデータをtag_data変数オブジェクトに格納し、条件分岐でアラートスタイルの切り替え
{
    const ad_field      = document.querySelector(".js_ad_field")
    const adNum_field   = document.querySelector(".js_adNum_field")
    const adRange_field = document.querySelector(".js_adRange_field")

    if(ad_field !== null){
        ad_field.addEventListener("input", ()=>{
            // 入力された値が半角文字の場合
            if(isHalfWidthChars(ad_field.value)){
                removeAlertStyle(ad_field)
            // 入力値が空の場合
            } else if(ad_field.value == ""){
                // オブジェクト変数を空にする
                clearTagDataObj("ad_code")
                removeAlertStyle(ad_field)
            } else{
                addAlertStyle(ad_field)
            }

            // データをオブジェクト変数の中にセット
            insertAdCodeAndTriggerToObj("ad_code", ad_field.value)
            checkTagTriggerType()
            enableAddBtn()
        
        })
    }

    

    if(adNum_field !== null){
        adNum_field.addEventListener("input", ()=>{
            if(isHalfWidthNumber(adNum_field.value)){
                removeAlertStyle(adNum_field)
            // 入力値が空の場合
            } else if(adNum_field.value == ""){
                // オブジェクト変数を空にする
                clearTagDataObj("ad_num")
                removeAlertStyle(adNum_field)
            } else{
                addAlertStyle(adNum_field)
            }
    
            insertAdCodeAndTriggerToObj("ad_num", adNum_field.value)
            enableAddBtn()
            checkTagTriggerType()
        })
    }
    


    if(adRange_field !== null){
        adRange_field.addEventListener("input", ()=>{
            // 入力値が半角数字なおかつ1～100までの値の場合
            if(isHalfWidthNumber(adRange_field.value) && isValidNum(adRange_field.value, 1, 100)){
                removeAlertStyle(adRange_field)
            // 入力値が空の場合
            }else if(adRange_field.value == ""){
                // オブジェクト変数を空にする
                clearTagDataObj("ad_range")
                removeAlertStyle(adRange_field)
            } else{
                addAlertStyle(adRange_field)
            }

            insertAdCodeAndTriggerToObj("ad_range", adRange_field.value)
            checkTagTriggerType()
            enableAddBtn()

        })
    }
    


    // 全ページ適用にチェックが入ってるか確認
    const check = document.getElementById("apply_all")

    if(check !== null){
        check.addEventListener("change", ()=>{
            if(check.checked){
                const typeValue = document.getElementById("js_type")
                typeValue.value = "withoutCode"

                // setSelectedTrigger(type)
                insertAdCodeAndTriggerToObj("applygingAll", "checked")
                disableCodeInputField()
            }else{
                insertAdCodeAndTriggerToObj("applygingAll", "unchecked")
                enableCodeInputField()
            }
            enableAddBtn()
        })
    }
    
}

   //モーダルの非表示
   {
    const close_btns = document.querySelectorAll(".js_close_btns2")
    const modals = document.querySelectorAll(".js_modals")

    close_btns.forEach((btn)=>{
        btn.addEventListener("click", ()=>{
            modals.forEach((modal)=>{
                closeModal(modal)
            })
            
        })  
    })



   


}





acEditor("tag_head", 1)
acEditor("tag_body", 2)


setDataToObjWhenUpdateTagData()