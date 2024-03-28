import { fetchReferences, inputValue } from "@index/index.js"

// ####################### DOM要素 #################################

const copy_site_btn = document.getElementById("js_copy_site")
const copy_tag_btn  = document.getElementById("js_copy_tag")
const tag_id        = document.getElementById("tag_reference_id")

// #################################################################

export const updateDomainInfo = (res)=>{
    document.getElementById("js_create_btn").classList.remove("disabled_btn")
    setDomainName(res)
    setDomainCategory(res)
    setDomainType(res)
    setTagInfo(res)
    setReferenceData(res)
    hideUnnecessaryForms()
}

// ドメイン名の設置
const setDomainName = (res)=>{
    const name      = document.getElementById("js_domain_name")
    const form_name = document.getElementById("domain_name")
    const domain_id = document.getElementById("js_domain_id")

    name.value      = res[0]["domain_name"]
    form_name.value = res[0]["domain_name"]
    domain_id.value = res[0]["id"]
   
    inputValue["domain_name"]   = res[0]["domain_name"]
    inputValue["domain_id"]     = res[0]["id"]

}

// ドメインカテゴリー値設置
const setDomainCategory = (res) =>{
    const radio_domain_categories   = document.getElementById("js_domain_category")

    inputValue["domain_category"] = `${res[0]["domain_category_id"]}`
    radio_domain_categories.value = res[0]["domain_category_id"];
}

// ドメイン種類値の設置
const setDomainType = (res)=>{
    const radio_domain_types = document.getElementById("js_domain_type");


    inputValue["domain_type"] = res[0]["domain_type"] 
    radio_domain_types.value = res[0]["domain_type"] 
   
}

// タグ情報値の設置
const setTagInfo = (res)=>{
    const radio_tag_info  = document.getElementById("js_tag_type")
    

    if(res[0]["parent_tag_id"] === null || res[0]["parent_tag_id"] === undefined){
        inputValue["tag_type"] = "new"
        radio_tag_info.value = "new"
    } else if(res[0]["random_domain_id"] === "0") {
        inputValue["tag_type"] = "withoutTag"
        radio_tag_info.value = "withoutTag"
    }else{
        inputValue["tag_type"] = "reference"
        radio_tag_info.value = "reference"
    }

}

// 参照先のデータ設置
const setReferenceData = (res)=>{

    if(res[0]["parent_domain_id"] !== null){

        // 元のオリジナルドメインのid取得
        if(res[0]["original_parent_id"]){
            document.getElementById("js_original_domain_id").value = res[0]["original_parent_id"];
            inputValue["original_domain_id"] = `${res[0]["original_parent_id"]}`
        }else{
            document.getElementById("js_original_domain_id").value = res[0]["parent_domain_id"]
            inputValue["original_domain_id"] = `${res[0]["parent_domain_id"]}`
        }

        // copy_site_btn.disabled = res[0]["domain_type"] === "original"
    
        inputValue["copy_reference_id"] = `${res[0]["parent_domain_id"]}`
        document.getElementById("js_copy_reference_id").value = res[0]["parent_domain_id"]
        fetchReferences(res, "parent_domain_id", "copy_reference", copy_site_btn)

        if(res[0]["parent_tag_id"] !== null){
            tag_id.value = res[0]["parent_tag_id"]
            inputValue["tag_reference_id"] = `"${res[0]["parent_tag_id"]}"`
            document.getElementById("js_tag_reference_id").value = res[0]["parent_tag_id"]
            fetchReferences(res, "parent_tag_id", "tag_reference", copy_tag_btn)
        }
    } else{

        inputValue["copy_reference_id"] = ""
        inputValue["tag_reference_id"] = ""
        inputValue["original_domain_id"] = ""
        copy_site_btn.querySelector(".label").innerHTML = "コピーサイト参照先"
        copy_tag_btn.querySelector(".label").innerHTML = "タグ参照先"
    }

    copy_tag_btn.disabled = true
    copy_site_btn.disabled = true
}


export const hideUnnecessaryForms = () =>{
    document.querySelector(".js_form").style.display = "none";
    document.querySelector(".domain_add_modal_main").style.height = "15%"
}