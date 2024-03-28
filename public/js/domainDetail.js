import { copyTextToCplipboard, addStyleToMenuBtn, displayEditModalAndInitializingForTag, displayAlertModalForDeleteTag  } from "@index/index.js";

// タグの削除処理

{
    const delete_btn = document.querySelector(".js_delete_tag")

        if(delete_btn !== null){
            delete_btn.addEventListener("click", (e)=>{
                
                // タグ削除のアラートモーダルを表示
                displayAlertModalForDeleteTag()
            }) 
        }
       

    const cancel_btns  = document.querySelectorAll(".js_delete_btn");
    const alert_modals = document.querySelectorAll(".js_alert_modal");
    const bg           = document.querySelector(".bg-gray")
    
    cancel_btns.forEach((btn)=>{
        btn.addEventListener("click", ()=>{
            alert_modals.forEach((modal)=>{
                modal.classList.add("hidden")
                bg.classList.add("hidden")

            })
        })
    })
}

// タグ削除するときのアラートを出す処理
displayEditModalAndInitializingForTag()

// ###########################################################################
//                           コピーサイト一覧
// ###########################################################################
const ad_index  = document.querySelector(".js_ad_code")
const copy_site = document.querySelector(".js_copy_site")
const adTag_site = document.querySelector(".js_adtag_site")
const directory_site = document.querySelector(".js_directory_site")


const copy_btn = document.querySelector(".js_copy_btn")

if(copy_btn !== null){
   copy_btn.addEventListener("click", ()=>{
        ad_index.classList.add("hidden")
        copy_site.classList.remove("hidden")
        adTag_site.classList.add("hidden")
        directory_site.classList.add("hidden")

    }) 
}

// ###########################################################################
//                           ディレクトリ別サイト一覧
// ###########################################################################



const directory_btn = document.querySelector(".js_directory_btn")

if(directory_btn !== null){
   directory_btn.addEventListener("click", ()=>{
        ad_index.classList.add("hidden")
        copy_site.classList.add("hidden")
        adTag_site.classList.add("hidden")
        directory_site.classList.remove("hidden")

    }) 
}



// ###########################################################################
//                           広告コード一覧
// ###########################################################################

const tag_btn = document.querySelector(".js_tag_btn")


if(tag_btn !== null){
    tag_btn.addEventListener("click", ()=>{
        ad_index.classList.remove("hidden")
        copy_site.classList.add("hidden")
        adTag_site.classList.add("hidden")
        directory_site.classList.add("hidden")
    })
}


// ###########################################################################
//                           タグ追加
// ###########################################################################

const add_btn = document.querySelector(".js_addTag_btn")

if(add_btn !== null){
    add_btn.addEventListener("click", ()=>{
        ad_index.classList.add("hidden")
        copy_site.classList.add("hidden")
        adTag_site.classList.remove("hidden")
        directory_site.classList.add("hidden")
    })
}

addStyleToMenuBtn()

// 処理成功後のメッセージ
{
    const success = document.getElementById("js_success");

    if(success !== null){
        if(success.innerHTML !== ""){
            setTimeout(()=>{
                success.style.display = "none";
            }, 1500)
        }else{
            success.style.display = "none";
        }
    }

    
}

{
    const script_btn = document.querySelector(".js_script_tag")
    const copy_btn = document.querySelector(".copy_btn_script")
    const copy_btn2 = document.querySelector(".copy_btn_script2")
    const script = document.querySelector(".script_txt")
    const script2 = document.querySelector(".script_txt2")

    script_btn.addEventListener("click", ()=>{
        document.querySelector(".script_txt").textContent = `<script src='https://tag-tracker.biz/index.js?id=${document.getElementById("js_script_name").innerHTML}'></script>`
        document.querySelector(".script_txt2").textContent = `<script src='https://tag-tracker.biz/rd.js?id=${document.getElementById("js_script_name").innerHTML}'></script>`
       document.querySelector(".script_area").classList.remove("hidden")
       document.querySelector(".bg-gray").classList.remove("hidden")
       copyTextToCplipboard(copy_btn, script.textContent)
       copyTextToCplipboard(copy_btn2, script2.textContent)
    })


    const close_btn = document.querySelector(".js_close_btns3")
    close_btn.addEventListener("click", ()=>{
        document.querySelector(".script_area").classList.add("hidden")
        document.querySelector(".bg-gray").classList.add("hidden")
    })
}