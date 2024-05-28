import { updateButtonState, enableTagReferenceBtn, inputValue, createDiv} from "@index/index.js";



// 検索結果を表示させるコンテイナーを空にする
export const makeParentDivEmpty = (className)=>{
    const wrapper = document.querySelector(`${className}`);
    wrapper.innerHTML = "";
}

// 検索結果の数だけ結果を表示させるdiv要素を追加する
export const appendSearchResults = (res, className, className2)=>{
    makeParentDivEmpty(className)
    const wrapper = document.querySelector(`${className}`);

    res.forEach((res)=>{
        wrapper.appendChild(createDiv(res, className2));
    })
}


// コピーサイト参照LPのidとドメイン名をinputの値にセットする
export const setSelectedDomainIdAndName = (copy_site_btn, e) =>{
    const reference_domain  = document.getElementById("js_copy_reference_id");
    const original_domain   = document.getElementById("js_original_domain_id");
    const domain_category = document.getElementById("js_domain_category")

    let target                  = e.currentTarget;
    let selectedID              = target.querySelector(".label").getAttribute("data-id");
    let selectedDomainName      = target.querySelector(".label").innerHTML
    let selectedDomainCategory  = target.querySelector(".label").getAttribute("data-category-id");
    let selectedOriginalID      = target.querySelector(".label").getAttribute("data-original-id");
    
    
    copy_site_btn.querySelector(".label").innerHTML  = selectedDomainName;

    reference_domain.value  = selectedID;
    domain_category.value   = selectedDomainCategory;


    if(selectedOriginalID == "null"){
        original_domain.value  = selectedID
    }else{
        original_domain.value  = selectedOriginalID;
    }
    

    inputValue["copy_reference"]    = selectedDomainName;
    inputValue["copy_reference_id"] = selectedID;
    inputValue["domain_category"] = selectedDomainCategory;

    if(selectedOriginalID === "null"){
        inputValue["original_domain_id"] = selectedID;
    }else{
        inputValue["original_domain_id"] = selectedOriginalID;
    }
    
    updateButtonState("add")
    enableTagReferenceBtn();
}

// タグ参照LPのidとドメイン名をinputタグにセットする
export const setSelectedTagIdAndName = (copy_tag, e) =>{

    
    const tag_input         = document.getElementById("js_tag_reference_id")
    let target              = e.currentTarget;
    let selectedID          = target.querySelector(".label").getAttribute("data-original-id") == "null" ? target.querySelector(".label").getAttribute("data-id") : target.querySelector(".label").getAttribute("data-original-id")
    // let selectedID          = target.querySelector(".label").getAttribute("data-id")

    
    let selectedTagName     = target.querySelector(".label").innerHTML

    copy_tag.querySelector(".label").innerHTML  = selectedTagName;
    tag_input.value                             = selectedID;
    inputValue["tag_reference"]                 = selectedTagName;
    inputValue["tag_reference_id"]              = selectedID;
    updateButtonState("add")

}