
import { isLocalStorageDataExisted, inputValue } from "@index/index.js";



//######################################################################################
// フォームデータのバリデーションエラー後(バックエンド)の状態復元のための処理
// データはlocalstrageから取得
// #####################################################################################

const data = isLocalStorageDataExisted()

// localStorageから取得したデータをinputオブジェクト変数に格納
export const setTextValue = (value, property)=>{
    if(value !== undefined){
        inputValue[property] = value
    }
}

// 必要なhidden inputとフォームに値を設定
export const setDataAndID = ()=>{

    document.getElementById("js_domain_name").value = data["domain_name"] || "";
    document.getElementById("js_copy_reference_id").value = data["copy_reference_id"] || ""
    document.getElementById("js_original_domain_id").value = data["original_domain_id"] || ""
    document.getElementById("js_tag_reference_id").value = data["tag_reference_id"] || ""
    document.getElementById("js_domain_type").value = data["domain_type"] || ""
    document.getElementById("js_domain_id").value = data["domain_id"] || ""
}