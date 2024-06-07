import { fetchCopySIte ,displaySettingModal, isActive, clearInputValue, clearInputField, fetchTagID } from "@index/index.js";

// ドメイン編集モーダルの初期化
export const initializeDomainEditModal = (event)=>{
    const input_selectedID  = document.querySelectorAll(".js_selectedID")
    const active_num        = document.querySelector(".js_isActive")

    // 値をすべて初期化する
    document.getElementById("js_error").innerHTML = "";
    clearInputValue()
    clearInputField()

    // 選択したドメインのIDの取得、設置
    let target      = event.currentTarget
    let selected_id = target.getAttribute("data-id")

    input_selectedID.forEach((id)=>{
        id.value = selected_id
    })
    

    // 使用有無の切り替えで使うidを取得、設置
    active_num.value= target.getAttribute("data-id-active")

    return target.getAttribute("data-tag-domain-id")

}

// ドメイン編集モーダル表示して初期化
export const displayEditModalAndInitializing = ()=>{
    const modal_btn = document.querySelectorAll(".edit_modal_btns")

    // 各ドメインの設定ボタン(3点マーク)をクリックしたときの処理
    modal_btn.forEach((btn)=>{
        btn.addEventListener("click", (e)=>{
            //ドメイン編集モーダルの初期化
            let id = initializeDomainEditModal(e)
            // モーダルのドメイン削除文言の切り替え
            fetchTagID(id)
            fetchCopySIte()
            //ドメイン使用の有無の文言切り替え
            isActive()
            setTimeout(() => {
                displaySettingModal() 
             }, 100);

             document.querySelector(".js_delete_tag").setAttribute("data-id", id)
        })
    })
}