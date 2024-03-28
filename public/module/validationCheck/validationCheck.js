import { tag_data } from "@index/index.js";

// 半角数字かチェック
export const isHalfWidthNumber = (str)=>{
    return /^[0-9]+$/.test(str);
}

// 半角文字かチェック
export const isHalfWidthChars = (str) => {
    return /^[A-Za-z\s!-/:-@\[-`{-~]+$/.test(str);
}

// 数値が範囲以内かチェック
export const isValidNum = (num, min_length, max_length)=>{
    return (num >= min_length && num <= max_length)
}

export const createDataCheckObj = ()=>{
    return {
        trigger_all : Boolean(tag_data["trigger"] && (tag_data["tag_head"] || tag_data["tag_body"])),
        trigger_exceptAll : Boolean(tag_data["trigger"] && tag_data["ad_code"] && tag_data["ad_num"] && (tag_data["tag_head"] || tag_data["tag_body"])),
        trigger_exceptAllWithAdRange : Boolean(tag_data["trigger"] && tag_data["ad_code"] && tag_data["ad_num"] && tag_data["ad_range"] && (tag_data["tag_head"] || tag_data["tag_body"])),
        trigger_exceptAll_dataCheck: Boolean(isHalfWidthChars(tag_data["ad_code"]) && isHalfWidthNumber(tag_data["ad_num"])),
        trigger_exceptAllWithAdRAnge_dataCheck: Boolean(isHalfWidthChars(tag_data["ad_code"]) && isHalfWidthNumber(tag_data["ad_num"]) && isHalfWidthNumber(tag_data["ad_range"]) && isValidNum(tag_data["ad_range"], 1, 100))
    }
 
}

   