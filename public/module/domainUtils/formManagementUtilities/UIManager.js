
import { clearObjectElements, inputValue, isLocalStorageDataExisted, unsetLocalStorage } from "@index/index.js";

export const initializeModal = ()=>{
    document.getElementById("js_domain_top_txt").innerHTML = "ドメイン追加"
    document.getElementById("js_create_btn").innerHTML = "追加"
    document.getElementById("js_domain_id").value = "";
}

const isDomainTypeOriginal  = ()=>{
    return Boolean(inputValue["domain_type"] === "original" )
}
const isDomainTypeCopyOrDirectory  = ()=>{
    return Boolean(inputValue["domain_type"] === "copy" ||  inputValue["domain_type"] === "directory")
}

const isNewTag = ()=>{
    return Boolean(inputValue["tag_type"] === "new" || inputValue["tag_type"] === "")
}

const WithoutTag = ()=>{
    return Boolean(document.querySelector(".js_tagCheck").checked && (inputValue["domain_type"] === "copy" ||  inputValue["domain_type"] === "directory"))
}


const toggleInputField = () =>{
    if(isDomainTypeCopyOrDirectory()){
        // タグを入れないか入れるかのチェックフォームを表示する
        document.getElementById("js_checkTag").style.display = "block"
    } else{
        document.getElementById("js_checkTag").style.display = "none"
    }

    if(isDomainTypeOriginal()){
        document.querySelector(".cateogory_area").style.display = "block"
        document.querySelector(".copy_area").style.display = "none"
    }else{
        document.querySelector(".cateogory_area").style.display = "none"
        document.querySelector(".copy_area").style.display = "block"
    }
    if(WithoutTag()){
        document.querySelector(".tagInfo_area").style.display = "none"
        // document.querySelector(".tag_area").style.display = "none"
    }else{
        document.querySelector(".tagInfo_area").style.display = "block"
        document.querySelector(".tag_area").style.display = "block"
    }
}





// ドメインコピー参照先ボタンの無効化、有効化
const enableDomainReferenceBtn = ()=>{
    const copy_site_btn = document.getElementById("js_copy_site")

    if(inputValue["domain_type"]){
        copy_site_btn.disabled = isDomainTypeOriginal()
        
        if(isDomainTypeOriginal()){
            copy_site_btn.querySelector(".label").innerHTML = "コピーサイト参照先"
        }  
    }
}

// タグ引継ぎ参照先ボタンの無効化、有効化
export const enableTagReferenceBtn = ()=>{
    const tag_btn = document.getElementById("js_copy_tag")
    tag_btn.disabled = isNewTag();
    
    if(isNewTag()){
        tag_btn.querySelector(".label").innerHTML = "タグ参照先"
        inputValue["tag_reference"] = ""
        inputValue["tag_reference_id"] = ""
    }
}



// 作成ボタンの無効化、有効化の切り替え関数
export const updateButtonState = () =>{

    const create_btn            = document.getElementById("js_create_btn")
    const data                  = inputValue || isLocalStorageDataExisted();


    // オリジナルドメインの場合
    const isOriginalDomain                  = Boolean(data.domain_name && data.domain_category  && data.domain_type == "original" && data.tag_type === "new");
    // オリジナルドメインの場合でタグを参照する場合
    const isOriginalDomainWithTagReference  = Boolean(data.domain_name && data.domain_category && data.domain_type == "original" && data.tag_type === "reference" && data.tag_reference && data.tag_reference_id)
    // コピーまたはディレクトリ別の場合
    const isCopyOrDirectory                 = Boolean(data.domain_name && data.domain_category && (data.domain_type === "copy" || data.domain_type === "directory") && data.tag_type === "new" && data.copy_reference && data.copy_reference_id && data.original_domain_id);
    // コピーまたはディレクトリ別の場合でタグを参照する場合
    const isNewTagWithReference             = Boolean(data.domain_name && data.domain_category && (data.domain_type === "copy" || data.domain_type === "directory") && data.tag_type !== "new" && data.copy_reference && data.copy_reference_id && data.original_domain_id && data.tag_reference && data.tag_reference_id);
    // コピーまたはディレクトリ別でタグを入れない
    const isWithoutTag                      = Boolean(data.domain_name && data.domain_category  && (data.domain_type === "copy" || data.domain_type === "directory") && data.tag_type === "withoutTag" && data.copy_reference && data.copy_reference_id && data.original_domain_id && data.tag_reference && data.tag_reference_id);
    // 作成ボタンの無効化有効化を切り替え
    create_btn.classList.toggle("disabled_btn", !(isOriginalDomain || isCopyOrDirectory || isNewTagWithReference || isOriginalDomainWithTagReference || isWithoutTag))
}


// UIを更新する
export const updateUI = (key, key2, event, inputValue) =>{

    if(!/^[\s]*$/.test(event) && event !== ""){
        inputValue[key] = event;
    }else{
        inputValue[key] = ""
    }
      
    // オブジェクト変数に選択されたデータをそれぞれ格納する
    
    // input fieldに選択されたデータをそれぞれ格納する
    
    document.getElementById(`js_${key2}`).value = event
    updateButtonState();
    // enableTagInfoRadioBtn();
    toggleInputField()
    enableDomainReferenceBtn();
    enableTagReferenceBtn();
    unsetLocalStorage("data")
    clearObjectElements()
}




//ドメイン使用の有無の文言切り替え
export const isActive = ()=>{
    const active_num = document.querySelector(".js_isActive")
    
    if(active_num.value === "0"){
        document.getElementById("active_check").innerHTML = "使用する"
        document.getElementById("active_status").value = "使用する"

    }else{
        document.getElementById("active_status").value = "使用しない"
    }
}

export const disableDelete = () =>{

        const btn = document.querySelector(".js_delete_domain")

        btn.getElementsByTagName("p")[0].classList.remove("red")
        btn.getElementsByTagName("p")[0].style.color = "rgba(255, 0, 0, 0.25)"

        btn.getElementsByTagName("img")[0].src = `${process.env.SYSTEM_URL}public/img/trash_2.png`

}

export const enableDelete = () =>{

        const btn = document.querySelector(".js_delete_domain")

        btn.getElementsByTagName("p")[0].classList.add("red")
        btn.getElementsByTagName("p")[0].style.color = "red"

        btn.getElementsByTagName("img")[0].src = `${process.env.SYSTEM_URL}public/img/trash.png`

}

//ドメインデータのカテゴリーごとの切り替えでボタンの色を変える
export const changeCategoryBtn = (category_id)=>{
    const categories = document.querySelectorAll(".js_categories")

    categories.forEach((category)=>{
        if(category.getAttribute("data-category-id") === category_id){
            category.classList.add("active")
        }else{
            category.classList.remove("active")
        }
    })
}

export const addStyleToMenuBtn = ()=>{
    const btns = document.querySelectorAll(".js_menu_btn");

    btns.forEach((btn)=>{
        btn.addEventListener("click", (e)=>{
            let target = e.currentTarget;

            target.classList.add("menu_active");

            btns.forEach((btn)=>{
                if(btn !== target){
                    btn.classList.remove("menu_active")
                }
            })
        })
    })
}


