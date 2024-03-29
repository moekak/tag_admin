const modal_add     = document.getElementById("js_domain_add_modal")
const modal_info    = document.getElementById("js_info_modal")
const modal_bg      = document.querySelector(".bg-gray");
const modal_bg2     = document.querySelector(".bg-gray2")
const domain_modal  = document.querySelector(".admin_modal_container")

// ドメイン設定情報モーダル表示
export const displayInfoModal = ()=>{
    modal_bg2.classList.remove("hidden")
    modal_info.classList.remove("hidden")
}

// ドメイン設定情報モーダル非表示
export const closeInfoModal = ()=>{
    modal_bg2.classList.add("hidden")
    modal_info.classList.add("hidden")
}

// ドメイン追加、編集モーダル表示
export const displayDomainHandlingModal = ()=>{
    modal_add.style.transform = "translateX(0px)"
    modal_bg.classList.remove("hidden")
}
// ドメイン追加、編集モーダル表示(スライドなし)
export const displayDomainHandlingModalWithNoAni = ()=>{
    modal_add.style.transform = "translateX(0px)"
    modal_add.style.transition = "0s"
    modal_bg.classList.remove("hidden")
}

// ドメイン追加、編集モーダル非表示
export const closeDomainHandlingModal  = ()=>{
    modal_add.style.transform = "translateX(960px)"
    modal_add.style.transition = "0.5s"
    modal_bg.classList.add("hidden")
}

export const closeModal = (modal)=>{
    modal.classList.add("hidden")
    modal_bg.classList.add("hidden")
}

export const displaySettingModal = ()=>{
    domain_modal.classList.remove("hidden")
    modal_bg.classList.remove("hidden")
}

export const displayAlertModalForDeleteDomain = ()=>{
    const modal = document.querySelector(".domain_delete_modal_container");
    modal.classList.remove("hidden");
}

// タグ削除のアラートモーダル表示
export const displayAlertModalForDeleteTag = ()=>{
    const modal = document.querySelector(".tag_delete_modal_container");
    modal.classList.remove("hidden");
}



