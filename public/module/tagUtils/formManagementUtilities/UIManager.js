
import { createDataCheckObj, isTriggerAll, tag_data } from "@index/index.js"


// タグ追加の配信トリガーのdropを表示
export const displayTriggerCategories = ()=>{
    const modal = document.querySelector(".tag_form")
    modal.classList.add("activeTrigger")
}
// タグ追加の配信トリガーのdropを非表示
export const hideTriggerCategories = ()=>{
    const modal = document.querySelector(".tag_form")
    modal.classList.remove("activeTrigger")
}

// タグ追加の配信トリガーのinput内の矢印を変更
export const changeArrowImg = (arrow1, arrow2)=>{
    arrow1.classList.add("hidden")
    arrow2.classList.remove("hidden")
}

// 選択した配信トリガーをセットする
export const setSelectedTrigger = (type)=>{
    const triggerInput = document.querySelector(".js_trigger")
    const trigger      = document.querySelector(".trigger_msg");
    const txt          = trigger.querySelector(".label")

    
    txt.innerHTML       = type
    triggerInput.value  = type

}

// 選択した配信トリガーに基づいて広告コードのフィールドを無効化、有効化に切り替える
export const disableCodeInputField = () =>{
    const inputs = document.querySelectorAll(".js_data_field")

    clearInputValue()
    inputs.forEach((input)=>{
        input.style.background = "#80808026"
        input.style.cursor = "not-allowed"
        input.setAttribute("readonly", true)
        removeAlertStyle(input)
    })

}

export const enableCodeInputField = () =>{
    const inputs = document.querySelectorAll(".js_data_field")

    inputs.forEach((input)=>{
        input.style.background = "transparent"
        input.style.cursor = "text"
        input.removeAttribute("readonly")
    })

}



// 必要なデータがすべて入力されてるか確認する(boolen型で返す)
export const isExistedAllData = ()=>{;
    let dataCheckObj = createDataCheckObj()
    // 配信トリガーがallの場合

    if(isTriggerAll()){
        return dataCheckObj.trigger_all
    }else{
        return dataCheckObj.trigger_exceptAllWithAdRange && dataCheckObj.trigger_exceptAllWithAdRAnge_dataCheck
    }
}

// 広告コードのinput filedを空にする
export const clearInputValue = ()=>{
    const code = document.querySelector(".js_adCode")
    const inputs = code.querySelectorAll(".domain_name_input")
    inputs.forEach((input)=>{
        input.value = ""
    })
}

// タグ追加ボタンの無効化、有効化切り替え
export const enableAddBtn = ()=>{
    const btn = document.getElementById("js_tagCreate_btn")
    if(isExistedAllData()){
        btn.classList.remove("disabled_btn")
    }else{
        btn.classList.add("disabled_btn")
    }
}

// アラートのスタイル追加
export const addAlertStyle = (variable)=>{
    variable.parentNode.querySelector(".alert_txt").classList.remove("hidden")
    variable.parentNode.querySelector(".label").classList.add("red")
    variable.classList.add("alert_active")
}

// アラートのスタイル削除
export const removeAlertStyle = (variable)=>{
    variable.parentNode.querySelector(".alert_txt").classList.add("hidden")
    variable.parentNode.querySelector(".label").classList.remove("red")
    variable.classList.remove("alert_active")
}