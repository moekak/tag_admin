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

        // // ドメイン編集のボタン押せるようにしてる
        displayEditModalAndInitializing()
        
    })
    .catch((error) => {
        sendErrorLog(error)
        redirectToErrorPage()
        
    }); 
}



// 検索かけた際は、もっと表示するボタンを非表示にする
const search = document.querySelector(".js_search_domain")

search.addEventListener("input", ()=>{
    document.querySelector(".js_btn ").style.display = "none"
})