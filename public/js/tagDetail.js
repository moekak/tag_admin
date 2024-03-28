import { tag_data, enableAddBtn } from "@index/index.js"

const trigger_type = document.querySelector(".trigger_msg").querySelector(".label").innerHTML
const ad_code = document.querySelector(".js_ad_field").value
const ad_num = document.querySelector(".js_adNum_field").value
const typeValue = document.getElementById("js_type")
const range = document.querySelector(".js_adRange_field").value


    tag_data["ad_code"] = ad_code
    tag_data["ad_num"] = ad_num
    tag_data["trigger"] = trigger_type
    tag_data["ad_range"] = range

    if(tag_data["ad_range"] === "1"){
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


    // console.log(tag_data);
    // console.log("3");

    enableAddBtn()



