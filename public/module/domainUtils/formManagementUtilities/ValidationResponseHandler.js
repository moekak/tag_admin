
import { isLocalStorageDataExisted, inputValue } from "@index/index.js";



//######################################################################################
// フォームデータのバリデーションエラー後(バックエンド)の状態復元のための処理
// データはlocalstrageから取得
// #####################################################################################






// localStorageから取得したデータをinputオブジェクト変数に格納
export const setTextValue = (value, property)=>{
    if(value !== undefined){
        inputValue[property] = value
    }
}

// 必要なhidden inputとフォームに値を設定
export const setDataAndID = ()=>{

    const data = isLocalStorageDataExisted()

    console.log(data);
    const classes = ["domain_name","copy_reference_id", "original_domain_id", "tag_reference_id", "domain_type", "domain_id", "copy_reference", "domain_category", "tag_reference", "tag_type"]

    for(let i = 0; i < classes.length; i++){
        if(document.getElementById(`js_${classes[i]}`)){
            document.getElementById(`js_${classes[i]}`).value = data[classes[i]] || ""
        }
        
        inputValue[classes[i]] = data[classes[i]] || ""
    }

    console.log(inputValue);
}

export const setDataToDOM = ()=>{
    const data = isLocalStorageDataExisted()

    console.log(data);
    document.getElementById("domain_name").value = data["domain_name"] || "";
    const radio_domain_categories   = document.querySelectorAll('input[name="type"]')
    const radio_tag_categories   = document.querySelectorAll('input[name="tag_types"]')
    const copy_reference = document.querySelector(".copy_reference").querySelector(".label")
    const tag_reference = document.querySelector(".tag_reference").querySelector(".label")
    const categories = document.querySelectorAll('input[name="category"]')

    radio_domain_categories.forEach((radio)=>{
        if(radio.value == data["domain_type"]){
            radio.checked = true
        }
    })
    radio_tag_categories.forEach((radio)=>{
        if(radio.value == data["tag_type"]){
            radio.checked = true
        }
    })
    categories.forEach((radio)=>{
        if(data["domain_category"] && radio.value == data["domain_category"]){
            radio.checked = true
        }
    })

    if(data["copy_reference"] !== ""){
        document.querySelector(".copy_reference").disabled = false
        copy_reference.innerHTML = data["copy_reference"] 
    }
    if(data["tag_reference"] !== ""){
        tag_reference.innerHTML = data["tag_reference"] 
        document.querySelector(".tag_reference").disabled = false
    }
}

