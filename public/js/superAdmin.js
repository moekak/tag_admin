

{
      const index_btns = document.querySelectorAll(".js_index")

      index_btns.forEach((btn)=>{
            btn.addEventListener("click", ()=>{
                  const admin_id = btn.getAttribute("data-id"); 

                  fetch(`${process.env.API_URL}/superAdminFetch.php`, {
                        method: 'POST',
                        body: JSON.stringify({id: admin_id}),
                        headers: {
                            'Content-Type': 'application/json'
                        }
                    }).then(response => {
                        if (!response.ok) {
                              throw new Error('サーバーエラーが発生しました。');
                          }
                          return response.json();
                        
                    }).then((res)=>{
                        if(res !== ""){
                              window.location.href =`${process.env.SYSTEM_URL}index`
                        }

                    }).catch(error => {
                        console.error('Error:', error);
                    });
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
        
              fetch(`${process.env.API_URL}/searchDataFetch.php`, {
                // 第1引数に送り先
                method: "POST", // メソッド指定
                headers: {
                  "Content-Type": "application/json",
                }, // jsonを指定
        
                body: JSON.stringify(data), // json形式に変換して添付
              })
                .then((response) => {
                  if (!response.ok) {
                    throw new Error("サーバーエラーが発生しました。");
                  }
                  return response.json();
                })
                .then((res) => {
                  console.log(res);
                   // 検索結果が一つもなかったら、何も表示させない
                   if(e.target.value !== ""){
                         if (res.length <= 0) {
                              document.querySelector(".search_form").style.marginBottom ="0%"
                              const table = document.querySelector(".data_results_container");
                              table.innerHTML = ""
                        } else {
                              document.querySelector(".search_form").style.marginBottom ="3%"
                              appendSearchResults(
                                    res,
                                    ".data_results_container",
                              );

                              const btns = document.querySelectorAll(".js_tag_btn")

                              console.log(btns);
                              redirectToTagOperation(btns)


                              
                        }
                   }
                 
        
                })
                .catch((error) => {
                  console.log(error);
                        // sendErrorLog(error);
                        // redirectToErrorPage();
                });
            }, 300); // 300ms遅延
          });

}


const appendSearchResults = (res, className)=>{
      createIndexDiv(res)
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
      bg.classList.remove("hidden")
      modal.classList.remove("hidden")
})


const close_btn = document.querySelector(".js_close_icon")
close_btn.addEventListener("click", ()=>{
      bg.classList.add("hidden")
      modal.classList.add("hidden")
})



const redirectToTagOperation = (btns)=>{
      btns.forEach((btn)=>{
            btn.addEventListener("click", (e)=>{
                  let admin_id = btn.getAttribute("data-id") 
                  let domain_id = btn.getAttribute("data-domain-id")
                  fetch(`${process.env.API_URL}/superAdminFetch.php`, {
                        method: 'POST',
                        body: JSON.stringify({id: admin_id}),
                        headers: {
                            'Content-Type': 'application/json'
                        }
                    }).then(response => {
                        if (!response.ok) {
                              throw new Error('サーバーエラーが発生しました。');
                          }
                          return response.json();
                        
                    }).then((res)=>{
                        if(res !== ""){
                              window.location.href =`${process.env.SYSTEM_URL}showTag/?id=${domain_id}`
                        }

                    }).catch(error => {
                        console.error('Error:', error);
                    });
            })
      })
}