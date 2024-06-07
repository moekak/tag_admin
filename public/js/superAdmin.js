// #################################################################################
// ###############################　部品 ############################################
// #################################################################################


// fetch処理
const fetchFn = (data, url)=>{
      return fetch(`${process.env.API_URL}/${url}`, {
            method: "POST",
            headers: {
            "Content-Type": "application/json",
            },
            body: JSON.stringify(data),
      }).then((response) => {
            if (!response.ok) {
            throw new Error("サーバーエラーが発生しました。");
            }
            return response.json();
      });
}

const closeModal = (element1, element2) =>{
      element1.classList.add("hidden")
      element2.classList.add("hidden")
}

const openModal = (element1, element2) =>{
      element1.classList.remove("hidden")
      element2.classList.remove("hidden")
}
// #################################################################################
// ###############################　終わり ############################################
// #################################################################################


{
      const index_btns = document.querySelectorAll(".js_index")

      index_btns.forEach((btn)=>{
            btn.addEventListener("click", ()=>{
                  const admin_id = btn.getAttribute("data-id"); 
                  const data = {id: admin_id}
                  fetchFn(data, "superAdminFetch.php")
                  .then((res)=>{
                        if(res !== ""){
                              window.location.href =`${process.env.SYSTEM_URL}index`
                        }
                  })
                  .catch((error)=>{
                        console.log(error);
                  })
            }) 
      })
      
}



// ドメイン検索
{
      const input = document.querySelector(".js_search_domain")
      input.addEventListener("input", (e)=>{

            if(e.target.value == ""){
                  const table = document.querySelector(".data_results_container");
                  table.innerHTML = ""
                  document.querySelector(".search_form").style.marginBottom ="0%"
            }
            let timeout;
            clearTimeout(timeout);
            //ユーザーが入力を終えて300ミリ秒以上経過しないと、検索リクエストが送信されないようにするため
            // （検索リクエストの過度な送信を防ぐ）
            const search_word = e.target.value;
            timeout = setTimeout(() => {
              const data = {
                  keyword: search_word,
              };

              fetchFn(data, "searchDataFetch.php")
                  .then((res)=>{
                        if(e.target.value !== ""){
                              if (res.length <= 0) {
                                   document.querySelector(".search_form").style.marginBottom ="0%"
                                   const table = document.querySelector(".data_results_container");
                                   table.innerHTML = ""
                             } else {
                                   document.querySelector(".search_form").style.marginBottom ="3%"
                                   createIndexDiv(res)
     
                                   const btns = document.querySelectorAll(".js_tag_btn")
     
                        
                                   redirectToTagOperation(btns)   
                             }
                        }
                  })
                  .catch((error)=>{
                        console.log(error);
                  })
              
            }, 300); // 300ms遅延
      });
}


const createIndexDiv = (resArray)=>{

      const table = document.querySelector(".data_results_container");
      table.innerHTML = "";
  
      const template2 = 
          ` <thead style="position: sticky; top: 0;" id="js_table">
                  <tr>
                        <th scope="col">ドメイン</th>
                        <th scope="col" style="width: 200px;">管理者</th>
                        <th>ドメインID</th>
                        <th style="width: 100px;">link</th>
                  </tr>
            </thead>` 
  
      table.insertAdjacentHTML("beforeend", template2.trim())
  
      resArray.forEach((res)=>{
            const id = res["random_domain_id"] !== "0" ? res["random_domain_id"] : res["tag_reference_randomID"]
            const template = 
                  `<tbody>
                        <tr>
                              <th class="align-middle"><a href="https://${res["domain_name"]}" target="_blank">${res["domain_name"]}</a></th>
                              <th class="align-middle">${res["username"]}</th>
                              <th class="align-middle">${id}</th>
                              <th class="align-middle">
                                    <button type="button" class="btn btn-primary js_tag_btn" data-id="${res["admin_id"]}" data-domain-id=${res["domain_id"]}>タグ操作</button>
                              </th>
                        </tr>
                  </tbody>` 
      
            table.insertAdjacentHTML('beforeend', template.trim());
      })
  }
  

//   ドメイン検索押したときの挙動
const search_tbn = document.querySelector(".js_search_btn")
const bg = document.querySelector(".bg-gray")
const modal = document.querySelector(".js_alert_modal")

search_tbn.addEventListener("click", ()=>{
      document.querySelector(".js_search_domain").value = ""
      document.querySelector(".data_results_container").innerHTML = "";
      openModal(bg, modal)
})


const close_btns = document.querySelectorAll(".js_close_icon")
const modals = document.querySelectorAll(".js_modal")

close_btns.forEach((close_btn)=>{
     close_btn.addEventListener("click", ()=>{
            modals.forEach((modal)=>{
                  closeModal(bg, modal)
            })
      }) 
})

const redirectToTagOperation = (btns)=>{
      btns.forEach((btn)=>{
            btn.addEventListener("click", (e)=>{
                  let admin_id = btn.getAttribute("data-id") 
                  let domain_id = btn.getAttribute("data-domain-id")
                  let data = {id: admin_id}

                  fetchFn(data, "superAdminFetch.php")
                        .then((res)=>{
                              if(res !== ""){
                                    window.location.href =`${process.env.SYSTEM_URL}showTag/?id=${domain_id}`
                              }
                        })
                        .catch((error)=>{
                              console.log(error);
                        })
            })
      })
}



// 管理者編集処理
{
      const edit_btns = document.querySelectorAll(".js_edit_btn")
      const edit_modal = document.querySelector(".js_edit_modal")
      const bg = document.querySelector(".bg-gray")

      edit_btns.forEach((edit_btn)=>{
            edit_btn.addEventListener("click", (e)=>{
                  let target = e.target
                  let admin_id = target.getAttribute("data-id");
                  let data = {id: admin_id}

                 

                  fetchFn(data, "getAdminUsername.php")
                        .then((res)=>{
            
                              document.querySelector(".edit_username_input").value = res
                              document.querySelector(".js_admin_input").value = admin_id
                        })
                        .catch((error)=>{
                              console.log(error);
                        })
                 
                        openModal(bg, edit_modal)
            })

      })

}

// / 処理成功後のメッセージ
{
    const success = document.getElementById("js_success");

    if(success !== null){
        if(success.innerHTML !== ""){
            success.classList.remove("hidden")
            setTimeout(()=>{
                success.classList.add("hidden")
            }, 1500)
        }else{
            success.classList.add("hidden")
        }
    }

    
}


const input_field = document.querySelector(".edit_username_input")

input_field.addEventListener("input", (e)=>{
      let value = e.target.value

      if(value == ""){
            document.querySelector(".js_update_btn").classList.add("disable")
            document.querySelector(".js_update_btn").disabled = true
      }else{
            document.querySelector(".js_update_btn").classList.remove("disable")
            document.querySelector(".js_update_btn").disabled = false
      }
})


// 管理者削除
{

      const delete_btns = document.querySelectorAll(".js_delete_btn")
      const alert_modal = document.querySelector(".js_delete_modal")

      delete_btns.forEach((btn)=>{
            btn.addEventListener("click", (e)=>{
                  let target = e.target
                  let admin_id = target.getAttribute("data-id");
                  let data = {id: admin_id}

                  fetchFn(data, "getAdminUsername.php")
                        .then((res)=>{

                              document.querySelector(".js_username").innerHTML = `管理者名: <span class="name">${res}</span>`
                              document.querySelector(".js_adminID_delete").value = admin_id
                        })
                        .catch((error)=>{
                              console.log(error);
                        })
                 
                        openModal(bg, alert_modal)
            })
      })



      const cancel_btn = document.querySelector(".js_cancel_btn")
      cancel_btn.addEventListener("click", ()=>{
            closeModal(bg, alert_modal)
      })

}



const add_admin = document.getElementById("js_admin_add")
const create_modal = document.querySelector(".js_create_modal")

add_admin.addEventListener("click", ()=>{
      openModal(bg, create_modal)
})



const username = document.querySelector(".js_new_username")
const password = document.querySelector(".js_new_password")
const add_btn = document.querySelector(".js_add_btn")

let value = {
      username: "",
      password: ""
}
username.addEventListener("input", (e)=>{
      value["username"] = e.target.value

      document.querySelector(".js_username_alert").classList.add("hidden")

      if(value["username"].length > 10){
            document.querySelector(".js_username_alert").innerHTML = "10文字以内で入力してください。"
            document.querySelector(".js_username_alert").classList.remove("hidden")
      }else{
            document.querySelector(".js_username_alert").classList.add("hidden")
      }
      checkLength()
})
password.addEventListener("input", (e)=>{
      value["password"] = e.target.value
      if(value["password"].length < 8){
            document.querySelector(".js_password_alert").classList.remove("hidden")
      }else{
            document.querySelector(".js_password_alert").classList.add("hidden")
      }
      checkLength()
})


const checkLength = () =>{

      if(value["username"].length > 10 || value["password"].length < 8){
            add_btn.classList.add("disable")
            add_btn.disabled = true
      }else{
            add_btn.classList.remove("disable")
            add_btn.disabled = false
      }
}