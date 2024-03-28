
import { tag_data, enableAddBtn } from "@index/index.js"


// 選択された配信トリガーがALLかをチェック。Boolen型を返す
export const isTriggerAll = ()=>{
    return tag_data["applygingAll"] === "checked" 
}

export const insertAdCodeAndTriggerToObj = (key, value)=>{

        tag_data[key] = value;

        enableAddBtn() 
}

export const insertTagToObj = (key, value)=>{
    if(!/^[\s]*$/.test(value)){
        tag_data[key] = value;
    }else{
        tag_data[key] = ""
    }
    enableAddBtn() 
}

export const clearTagDataObj = (key)=>{
    tag_data[key] = ""
 }

export const setTagDataToInputField = ()=>{
    const tag_head = document.getElementById("tag_head")
    const tag_body = document.getElementById("tag_body")

    if(/^[\s]*$/.test(tag_data["tag_head"])){
        tag_head.value = ""
    }else{
        tag_head.value = tag_data["tag_head"]
    }
    if(/^[\s]*$/.test(tag_data["tag_body"])){
        tag_body.value = ""
    }else{
        tag_body.value = tag_data["tag_body"]
    }

}

export const checkTagTriggerType = () =>{
    const typeValue  = document.getElementById("js_type")
    
    if(tag_data["ad_code"] && tag_data["ad_num"] && tag_data["ad_range"] === "1"){

        typeValue.value = "withCode"
    }
    if(tag_data["ad_code"] && tag_data["ad_num"] && (tag_data["ad_range"] !== "" && tag_data["ad_range"] !== "1")){
        typeValue.value = "withCodeRange"
    }
}