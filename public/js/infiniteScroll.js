import {createIndexDivForMore, displayEditModalAndInitializing,sendErrorLog, redirectToErrorPage} from "@index/index.js"


// ####################################################################################
//                             データを非同期で取得する
// ####################################################################################

const btn = document.querySelector(".js_btn")
let clickCount = 1

const categoeis = document.querySelectorAll(".js_categories");

categoeis.forEach((category)=>{
    category.addEventListener("click", ()=>{
        document.querySelector(".js_btn").innerHTML = "もっと表示させる"
        clickCount = 1
    })
})

btn.addEventListener("click", ()=>{
    fetchDomainInfo(clickCount)
    clickCount ++

    document.querySelector(".js_btn").innerHTML = "読み込み中..."
    
})


const fetchDomainInfo = (clickCount)=>{
    const category = localStorage.getItem("category_id")
    const data = {category: category, click: clickCount}
    fetch(`${process.env.API_URL}/getDataByScroll.php`, {
        // 第1引数に送り先
        method: "POST", // メソッド指定
        headers: {
        "Content-Type": "application/json",
        }, // jsonを指定
    
        body: JSON.stringify(data), // json形式に変換して添付
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('サーバーエラーが発生しました。');
        }
        return response.json();
    })
    .then((res) => {

   
        if(res.length <=0){

            document.querySelector(".js_btn").innerHTML = "全て読み込まれました"
        }else{
            document.querySelector(".js_btn").innerHTML = "もっと表示させる"
        }
        
        createIndexDivForMore(res)

        // for(let i = 0; i < res.length; i ++){
        //     fetchDomainCopyDataByAllForScroll(res[i]["id"])
        // }
        // // ドメイン編集のボタン押せるようにしてる
        displayEditModalAndInitializing()
        
    })
    .catch((error) => {
        sendErrorLog(error)
        redirectToErrorPage()
        
    }); 
}

const fetchDomainCopyDataByAllForScroll = (domain_id)=>{
    const data = {domain_id: domain_id}

    fetch(`${process.env.API_URL}/getDomainCopyInfoAll.php`, {
        // 第1引数に送り先
        method: "POST", // メソッド指定
        headers: {
            "Content-Type": "application/json",
        }, // jsonを指定
    
        body: JSON.stringify(data, true), // json形式に変換して添付
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('サーバーエラーが発生しました。');
        }
        return response.json();
    })
    .then((res) => {
        createIndexCopyDivForScroll(res)
        displayEditModalAndInitializing()
   

    })
    .catch((error) => {
        sendErrorLog(error)
        redirectToErrorPage()
    }); 

}

// ドメイン情報をカテゴリーごとに表示させる
const createIndexDivForScroll = (resArray)=>{
    const table = document.querySelector(".js_table");


    resArray.forEach((res)=>{
        const domainStatus      = res["domain_type"] === "original" ? "〇" : "";
        const domainActive      = res["is_active"] === "0" ? "red" : "green";
        const domainActiveText  = res["is_active"] === "0" ? "未使用" : "使用";
        const style = res["domain_type"] === "original" ? "table-light" : ""
        const random_domain_id = res["random_domain_id"] == "0" ? "" : res["random_domain_id"]
        const template = 
        `<tbody>
            <tr class="${style}" data-id=${res["id"]}>
                <td class="domain_name align-middle"><a href="${process.env.SYSTEM_URL}show/?id=${res["id"]}" class="textdecoration_none">${res["domain_name"]}</a></td>
                <td class="original_check align-middle">${domainStatus}</td>
                <td class="align-middle ${domainActive}">${domainActiveText}</td>
                <td class="text_color align-middle">${random_domain_id}</td>
                <td class="text_color align-middle edit_modal_btns" data-id="${res["id"]}" data-id-active=${res["is_active"]}><i class="fas fa-ellipsis-v text_color"></i></td>                                 
            </tr>
        </tbody>` 

        table.insertAdjacentHTML('beforeend', template.trim());
    })
    return table;
}


const createIndexCopyDivForScroll = (resArray)=>{
    
    resArray.forEach((res)=>{

        const current = document.querySelector(`[data-id="${res["original_parent_id"]}"]`);
        const parent            = current.parentElement;
        const domainStatus      = res["domain_type"] === "original" ? "〇" : "";
        const domainActive      = res["is_active"] === "0" ? "red" : "green";
        const domainActiveText  = res["is_active"] === "0" ? "未使用" : "使用";
        const random_domain_id = res["random_domain_id"] == "0" ? "" : res["random_domain_id"]
        // const style = res["random_domain_id"] == "0" ? "table-warning" : ""

        const template = 
        `<tr class="js_copySites">
            <td class="domain_name align-middle"style="padding-left: 5%;"><a href="${process.env.SYSTEM_URL}show/?id=${res["id"]}" class="textdecoration_none">${res["domain_name"]}</a></td>
            <td class="original_check align-middle">${domainStatus}</td>
            <td class="align-middle ${domainActive}">${domainActiveText}</td>
            <td class="text_color align-middle">${random_domain_id}</td>
            <td class="text_color align-middle edit_modal_btns" data-id="${res["id"]}" data-id-active=${res["is_active"]}><i class="fas fa-ellipsis-v text_color"></i></td>                                 
        </tr>` 

        parent.insertAdjacentHTML('beforeend', template.trim());

        return parent
    })
}


// 検索かけた際は、もっと表示するボタンを非表示にする
const search = document.querySelector(".js_search_domain")

search.addEventListener("input", ()=>{
    document.querySelector(".js_btn ").style.display = "none"
})