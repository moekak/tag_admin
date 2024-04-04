import {enableDelete, disableDelete, inputValue, updateDomainInfo, sendErrorLog, redirectToErrorPage  } from "@index/index.js";



// 編集するドメイン情報の取得
export const fetchDomainInfo = ()=>{
    const domain_id = document.getElementById("js_domain_id").value
    fetch(`${process.env.API_URL}/getSpecificDomainInfoFetch.php`, {

        // 第1引数に送り先
        method: "POST", // メソッド指定
        headers: {
            "Content-Type": "application/json",
        }, // jsonを指定
    
        body: JSON.stringify(domain_id), // json形式に変換して添付
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('サーバーエラーが発生しました。');
        }
        return response.json();
    })
    .then((res) => {
        updateDomainInfo(res)
    })
    .catch((error) => {
        sendErrorLog(error)
        redirectToErrorPage()
        
    }); 
}

// コピーサイト、タグ引継ぎのサイトのドメイン名を取得して表示させる処理
export const fetchReferences = (res2, id_type, reference_type, btn)=>{
    const id = res2[0][id_type]
    fetch(`${process.env.API_URL}/getParentDomainFetch.php`, {
        // 第1引数に送り先
        method: "POST", // メソッド指定
        headers: {
            "Content-Type": "application/json",
        }, // jsonを指定
    
        body: JSON.stringify(id), // json形式に変換して添付
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('サーバーエラーが発生しました。');
        }
        return response.json();
    })
    .then((res) => {
        if(res){
            inputValue[reference_type] = `${res}`
            btn.querySelector(".label").innerHTML = res;
        }else{
            inputValue[reference_type] = ""
            btn.querySelector(".label").innerHTML = "コピーサイト参照先"
        }
    })
    .catch((error) => {
        sendErrorLog(error)
        redirectToErrorPage()
    }); 
}

// タグが編集で選択されたドメインに紐づいてるかの確認
export const fetchTagID = ()=>{
    const id = document.querySelector(".js_selectedID").value;

    fetch(`${process.env.API_URL}/checkTagID.php`, {
        // 第1引数に送り先
        method: "POST", // メソッド指定
        headers: {
            "Content-Type": "application/json",
        }, // jsonを指定
    
        body: JSON.stringify(id), // json形式に変換して添付
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('サーバーエラーが発生しました。');
        }
        return response.json();
    })
    .then((res) => {
        const pElements = document.querySelector(".js_delete_tag").getElementsByTagName("p");
        if(res.length <= 0){
            
            for (var i = 0; i < pElements.length; i++) {
                pElements[i].classList.remove("red")
                pElements[i].style.color = "rgba(255, 0, 0, 0.25)";
            }
            const imgs = document.querySelector(".js_delete_tag").getElementsByTagName("img");
            for (var i = 0; i < imgs.length; i++) {
                imgs[i].src = `${process.env.SYSTEM_URL}public/img/trash_2.png`
            }

            document.querySelector(".js_delete_tag").style.pointerEvents = "none"


        }else{
            for (var i = 0; i < pElements.length; i++) {
                pElements[i].style.color = "red";
            }
            const imgs = document.querySelector(".js_delete_tag").getElementsByTagName("img");
            for (var i = 0; i < imgs.length; i++) {
                imgs[i].src = `${process.env.SYSTEM_URL}public/img/trash.png`
            }

            document.querySelector(".js_delete_tag").style.pointerEvents = "auto"

        }
    })
    .catch((error) => {
        sendErrorLog(error)
        redirectToErrorPage()
    }); 
}

// 非同期関数Promiseオブジェクトを返す
export const fetchTagReferenceDomain = (id) => {
    const data = id
    return fetch(`${process.env.API_URL}/getDomainWithTagReference.php`, {
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
  };
  
 
export const fetchCopySIte = ()=>{
    const id = document.querySelector(".js_selectedID").value;

   

    fetch(`${process.env.API_URL}/checkCopySite.php`, {
        // 第1引数に送り先
        method: "POST", // メソッド指定
        headers: {
            "Content-Type": "application/json",
        }, // jsonを指定
    
        body: JSON.stringify(id), // json形式に変換して添付
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('サーバーエラーが発生しました。');
        }
        return response.json();
    })
    .then((res) => {
        if(res.length > 0){
            disableDelete()
        }else{
            enableDelete()
        }
    })
    .catch((error) => {
        sendErrorLog(error)
        redirectToErrorPage()
    }); 
}
 