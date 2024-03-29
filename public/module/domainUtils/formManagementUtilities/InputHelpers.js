
import { inputValue, updateButtonState } from "@index/index.js"



// 全てのinputValueを初期に戻す
export const clearInputValueAll = ()=>{
    inputValue["copy_reference"] = ""
    inputValue["copy_reference_id"] = ""
    inputValue["original_domain_id"] = ""
    inputValue["domain_category"] = ""
    inputValue["domain_name"] = ""
    inputValue["domain_type"] = ""
    inputValue["tag_reference"] = ""
    inputValue["tag_reference_id"] = ""
    inputValue["tag_type"] = ""

    updateButtonState("add")
}

export const clearInputField = ()=>{
    const error = document.getElementById("js_error")
    if(error.innerHTML === ""){
        clearInputFieldValue()
        clearInputValueAll()
    }   

    updateButtonState("add")
}

export const clearRadioBtns = (btns)=>{
    btns.forEach((btn)=>{
        btn.checked = false
    })

    updateButtonState("add")
}

export const clearReferences = (reference, title, id_name)=>{
    reference.querySelector(".label").innerHTML = title
    reference.disabled = true
    document.getElementById(id_name).value = "";

    updateButtonState("add")
}

export const clearInputFieldValue = ()=>{
    const input_domain_name         = document.getElementById("domain_name");
    const radio_domain_categories   = document.querySelectorAll('input[name="type"]')
    const radio_domain_types        = document.querySelectorAll('input[name="category"]')
    const radio_tag_info            = document.querySelectorAll('input[name="tag_types"]')
    const copy_site_btn             = document.getElementById("js_copy_site")
    const tag_btn                   = document.getElementById("js_copy_tag")
    const check_btn                  = document.querySelectorAll(".js_tagCheck")

  

    input_domain_name.value = "";
    clearRadioBtns(radio_domain_categories)
    clearRadioBtns(radio_domain_types)
    clearRadioBtns(radio_tag_info)
    clearRadioBtns(check_btn)
    clearReferences(copy_site_btn, "コピーサイト参照先", "js_copy_reference_id")
    clearReferences(tag_btn, "タグ参照先", "js_tag_reference_id")

    document.querySelector(".cateogory_area").style.display = "none"
    // document.getElementById("js_checkTag").style.display = "none"
    const area = document.querySelector(".tagInfo_area")
    if(area.style.display == "none"){
        area.style.display = "block"
    }

}

// 
export const clearInputValue = ()=>{
    // inputValue["tag_type"] = ""
    // inputValue["tag_reference"] = ""
    inputValue["copy_reference"] = ""
    inputValue["copy_reference_id"] = ""
    inputValue["original_domain_id"] = ""
    // inputValue["tag_reference_id"] = ""

    updateButtonState("add")
}

// ドメインタイプがオリジナルの場合は、もし値が入ってたら空にする
export const clearObjectElements = ()=>{
    if(inputValue["domain_type"] === "original"){
       clearInputValue()
    }

    updateButtonState("add")
    
}

//　検索欄を空にする
export const clearSearchInput = ()=>{
    const search_input = document.getElementById("js_search_domain")
    const container = document.querySelector(".domain_search_results_container")
    container.innerHTML = ""
    search_input.value = "";
}


export const clearAllValues = ()=>{
    // const name_filed = document.querySelector('input[name="domain_name"]')
    // const domain_type_fileds = document.querySelectorAll('input[name="type"]')
    // const radio_tag_info            = document.querySelectorAll('input[name="tag_types"]')
    // const check = document.querySelectorAll(".js_tagCheck")

    // name_filed.value = "";
    // clearRadioBtns(domain_type_fileds)
    // clearRadioBtns(radio_tag_info)
    // clearRadioBtns(check)

    clearInputFieldValue()
            clearInputValueAll()
}
