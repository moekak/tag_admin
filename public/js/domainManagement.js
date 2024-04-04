

import {toggleInputField, setDataToDOM, setDataAndID, hideUnnecessaryForms, createPtagForTagDeleteModal, fetchTagReferenceDomain, copyTextToCplipboard , closeModal, clearAllValues, fetchDomainInfoBySearch, displayAlertModalForDeleteTag, fetchDomainDataByAll, fetchDomainDataByCategory, initializeModal, fetchDomainInfo, fetchTagData, displayEditModalAndInitializing, displayInfoModal, displayDomainHandlingModalWithNoAni, closeInfoModal, displayDomainHandlingModal , closeDomainHandlingModal,displayAlertModalForDeleteDomain, fetchDomainData, hideCollapse, inputValue, clearLocalStorage, getCategoryID, insertCategoriesID, insertDataToLocalstorage, isLocalStorageDataExisted, unsetLocalStorage, changeCategoryBtn, updateButtonState, updateUI, clearInputFieldValue, clearInputValueAll, clearInputField, clearSearchInput} from "@index/index.js";






// ###################################################################################
// 　            カテゴリーごとのデータ取得と、選択したカテゴリーのスタイル追加処理
// ###################################################################################

if(!getCategoryID()){
    insertCategoriesID("all")
}
if(getCategoryID() === "all"){
    fetchDomainDataByAll()
    changeCategoryBtn(getCategoryID())
}else{
    fetchDomainDataByCategory(getCategoryID())
    changeCategoryBtn(getCategoryID())  
}

fetchDomainInfoBySearch()
// ###################################################################################
// 　       編集をし、すでにドメインが存在していた場合にページに戻ってきたときの処理
// ###################################################################################

const error = document.getElementById("js_error2");
if(error.value === "edit"){

    document.getElementById("js_domain_top_txt").innerHTML = "ドメイン編集"
    document.getElementById("js_create_btn").innerHTML = "更新"
    hideUnnecessaryForms()

}


// ###################################################################################
// 　                               ドメイン追加処理 
// ###################################################################################
{
    // ドメイン追加ボタン押したときの処理
    {
        const add_btn = document.getElementById("js_domain_add")

        add_btn.addEventListener("click", ()=>{

            document.querySelector(".js_form").style.display = "block";
            document.querySelector(".domain_add_modal_main").style.height = "70%"

            const radioBtns  = document.querySelectorAll('input[name="domain_type"]')

            radioBtns.forEach((btn)=>{
                btn.disabled = false
            })

            // モーダルの初期化
            hideCollapse(document.getElementById("collapseExample1"))
            hideCollapse(document.getElementById("tag_reference_collapse"))
            clearSearchInput()
            clearInputField()
            initializeModal()

            // モーダルの表示
            displayDomainHandlingModal()
        })
    }  


    // ドメイン追加のモーダルの非表示
    {
        const close_btns = document.querySelectorAll(".js_close_btns")

        close_btns.forEach((btn)=>{
            btn.addEventListener("click", ()=>{
                closeDomainHandlingModal()
            })  
        })

    }
    //モーダルの非表示
    {
        const close_btns = document.querySelectorAll(".js_close_btns2")
        const modals = document.querySelectorAll(".js_modals")

        close_btns.forEach((btn)=>{
            btn.addEventListener("click", ()=>{
                modals.forEach((modal)=>{
                    closeModal(modal)
                })
                
            })  
        })


        // ドメイン追加時の閉じるボタン押したときの処理
        const modal_btn = document.querySelector(".domain_modal_btn")

        modal_btn.addEventListener("click", ()=>{
            
            clearAllValues()
            document.getElementById("js_error").innerHTML = ""


        })


    }

    // ドメイン設定情報モーダルの表示
    {
        const info_btn      = document.getElementById("js_info_btn")
        const modal_bg      = document.querySelector(".bg-gray2");

        info_btn.addEventListener("click", ()=>{
            displayInfoModal()

        })
        modal_bg.addEventListener("click", ()=>{
            closeInfoModal()
        })
    }
    

    // ###################################################################################
    // 　                               1. UI更新処理
    // ###################################################################################
    {
        // ================================= DOM要素 ================================================

        const input_domain_name         = document.getElementById("domain_name");
        const radio_domain_types        = document.querySelectorAll('input[name="type"]')
        const radio_domain_categories   = document.querySelectorAll('input[name="category"]')
        const radio_tag_info            = document.querySelectorAll('input[name="tag_types"]')
        const tag_check                 = document.querySelector(".js_tagCheck");
        const create_btn                = document.getElementById("js_create_btn")

        // ==========================================================================================

        

        input_domain_name.addEventListener("input", (e)=>{
            updateUI("domain_name", "domain_name", e.target.value, inputValue, "add")
        })

        radio_domain_types.forEach((radio)=>{
            radio.addEventListener("change", (e)=>{
                if(e.target.value == "original"){
                    hideCollapse(document.getElementById("collapseExample1"))
                    document.getElementById("js_search_domain").value = ""
                    document.querySelector(".domain_search_results_container").innerHTML = "";
                }
                updateUI("domain_type", "domain_type",  e.target.value, inputValue, "add")
            })
        })
    
        radio_domain_categories.forEach((radio)=>{
            radio.addEventListener("change", (e)=>{
                updateUI("domain_category", "domain_category",  e.target.value, inputValue, "add")
            })
        })
    
        radio_tag_info.forEach((radio)=>{
            radio.addEventListener("change", (e)=>{
                if(e.target.value == "new"){
                    hideCollapse(document.getElementById("tag_reference_collapse"))
                    document.getElementById("js_search_tag").value = ""
                    setTimeout(()=>{
                        document.querySelector(".tag_search_results_container").innerHTML = "";
                    }, 500)
                    
                }
                updateUI("tag_type", "tag_type", e.target.value, inputValue, "add")
            })
        })

        tag_check.addEventListener("change", (e)=>{
            updateUI("tag_type", "tag_type", e.target.value, inputValue, "add")
        })
    
        create_btn.addEventListener("click", (e)=>{
            e.preventDefault();
            insertDataToLocalstorage(inputValue);
            const form_create = document.getElementById("js_create_form")
      
            if(create_btn.innerHTML === "追加"){
                form_create.action = `${process.env.SYSTEM_URL}create`
            }else if(create_btn.innerHTML === "更新"){
                form_create.action = `${process.env.SYSTEM_URL}edit`
            }
        
            form_create.submit();
        })
    }


    // コピーサイト参照
    {
        // ================================= DOM要素 ================================================

        const copy_site_btn     = document.getElementById("js_copy_site")
        const search_container  = document.getElementById("collapseExample1")

        // ==========================================================================================
    
        copy_site_btn.addEventListener("click", ()=>{
            hideCollapse(document.getElementById("tag_reference_collapse"))
            fetchDomainData(copy_site_btn, search_container);
        })
    }


    // タグ参照
    {
        // ================================= DOM要素 ===============================================

        const copy_tag          = document.getElementById("js_copy_tag")
        const tag_container     = document.getElementById("tag_reference_collapse")

        // =========================================================================================
    
        copy_tag.addEventListener("click", ()=>{

            hideCollapse(document.getElementById("collapseExample1"))
            fetchTagData(copy_tag, tag_container)
        })
    
    }


    // バックエンドのなんかしらのエラーが起きた場合の処理
    {
        
        const error = document.getElementById("js_error")
        const txt = document.getElementById("js_create_btn").innerHTML;


        if(error.innerHTML !== "" && txt == "追加"){
            document.getElementById("js_create_btn").classList.remove("disabled_btn")
            displayDomainHandlingModalWithNoAni()
            setDataAndID()
            toggleInputField()
            setDataToDOM()
            updateButtonState("add")
            
        } 

        
        if(error.innerHTML !== "" && txt == "更新"){
            document.getElementById("js_create_btn").classList.remove("disabled_btn")
            displayDomainHandlingModalWithNoAni()
            setDataAndID()
            setDataToDOM()
            updateButtonState("edit")
        }
        
    }


    // ドメイン追加が成功したときの処理
    {
        const success = document.getElementById("js_success");
        const copy_btn = document.querySelector(".copy_btn_script")
        const copy_btn2 = document.querySelector(".copy_btn_script2")
        const script = document.querySelector(".script_txt")
        const script2 = document.querySelector(".script_txt2")

        if(document.getElementById("script_flag").value == 1){
            document.getElementById("js_script_modal").classList.remove("hidden")
            document.querySelector(".bg-gray").classList.remove("hidden")

            copyTextToCplipboard(copy_btn, script.textContent)
            copyTextToCplipboard(copy_btn2, script2.textContent)
        }

        if(success.innerHTML !== ""){
            success.classList.remove("hidden")
        
            clearInputFieldValue()
            clearInputValueAll()
            updateButtonState("add")

            setTimeout(()=>{
                success.classList.add("hidden")
            }, 1500)
        }else{
            success.classList.add("hidden")
        }




        window.addEventListener("load", ()=>{
            // unsetLocalStorage("data")
        })

    }

    const close_btn = document.querySelector(".js_close_btns3")
    close_btn.addEventListener("click", ()=>{
        document.querySelector(".script_area").classList.add("hidden")
        document.querySelector(".bg-gray").classList.add("hidden")
    })
    
}
   
    



// ###################################################################################
// 　                               ドメイン編集処理 
// ###################################################################################
{
    //各ドメインの編集モーダルを出す
    displayEditModalAndInitializing()

    //各ドメインの編集ボタンを押したときの処理
    {
        // ================================= DOM要素 ===============================================

        const edit_btns     = document.querySelectorAll(".domains_edit")
        const domain_modal  = document.querySelector(".admin_modal_container")

        // =========================================================================================
        
        edit_btns.forEach((btn)=>{
            btn.addEventListener("click", ()=>{
                // モーダルの初期化
                hideCollapse(document.getElementById("collapseExample1"))
                hideCollapse(document.getElementById("tag_reference_collapse"))
                clearSearchInput()

                // 選択したドメインIDの設置
                document.getElementById("js_domain_id").value = btn.querySelector(".js_selectedID").value 

                //追加用と同じモーダルを出す
         
                displayDomainHandlingModal()
                domain_modal.classList.add("hidden")
                
    
                // モダールの文言を追加から編集に変える
                const title         = document.getElementById("js_domain_top_txt")
                const btn_submit    = document.getElementById("js_create_btn")

                title.innerHTML         = "ドメイン編集"
                btn_submit.innerHTML    = "更新"
    
                // ドメイン情報を取得
                fetchDomainInfo() 
                
            })
           
        })
    }
}

// ###################################################################################
// 　                               ドメイン編集処理 
// ###################################################################################
{
    const delete_btn = document.querySelector(".js_delete_domain")

    delete_btn.addEventListener("click", (e)=>{
        // タグ削除のアラートモーダルを表示
        displayAlertModalForDeleteDomain()
    })

}



// ###################################################################################
// 　                               タグ削除処理 
// ###################################################################################
{
    const delete_btn = document.querySelector(".js_delete_tag")

        delete_btn.addEventListener("click", (e)=>{
            fetchTagReferenceDomain(document.querySelector(".js_selectedID").value)
            .then((res)=>{

                createPtagForTagDeleteModal(res)

            })
            .then(()=>{
                // タグ削除のアラートモーダルを表示
                displayAlertModalForDeleteTag()
            })
            

        })

    const cancel_btns = document.querySelectorAll(".js_delete_btn");
    const alert_modals = document.querySelectorAll(".js_alert_modal");
    const bg = document.querySelector(".bg-gray")
    
    cancel_btns.forEach((btn)=>{
        btn.addEventListener("click", ()=>{
            alert_modals.forEach((modal)=>{
                modal.classList.add("hidden")
                bg.classList.add("hidden")
                const table = document.querySelector(".domain_index");

                if(table !== null){
                    table.innerHTML = "";
                }
            })
        })
    })


    const deleteBtn = document.querySelector(".js_delete")

    deleteBtn.addEventListener("click", ()=>{
        document.querySelector(".loading").classList.remove("hidden")
    })




}





// ###################################################################################
// 　                               カテゴリー追加
// ###################################################################################
{
    const category_btn = document.querySelector(".fa-cog")

    category_btn.addEventListener("click", ()=>{
        document.querySelector(".category_add_modal-container").classList.remove("hidden")
        document.querySelector(".bg-gray").classList.remove("hidden")
    })


    // 編集ボタンのスタイル変更
    const edit_btns = document.querySelectorAll(".js_edit_category");

    edit_btns.forEach((btn)=>{
        btn.classList.add("disabled_btn")
    })

    const edit_forms = document.querySelectorAll(".js_category_form")

    edit_forms.forEach((form)=>{
        form.addEventListener("input", function(e){
 
            let target = this.parentElement;

            if(e.target.value !== ""){
                 target.querySelector(".js_edit_category").classList.remove("disabled_btn")
            }else{
                target.querySelector(".js_edit_category").classList.add("disabled_btn")
            }

           

            let others = Array.from(edit_forms).filter(form=> form.parentElement !== target)
            others.forEach((other)=>{
                other.parentElement.querySelector(".js_edit_category").classList.add("disabled_btn")
            })
        })
    })
}
