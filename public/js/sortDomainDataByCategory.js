import { getCategoryID, insertCategoriesID, changeCategoryBtn, fetchDomainDataByAll, fetchDomainDataByCategory } from "@index/index.js"


// // ###################################################################################
// // 　                    ドメインデータのソート(caetgoryごとに)
// // ###################################################################################
{

    //カテゴリーごとのソート
    const categories = document.querySelectorAll(".js_categories")
    categories.forEach((category)=>{
        category.addEventListener("click", (e)=>{
            //検索欄の値を空にする
            document.querySelector(".js_search_domain").value = ""
            document.querySelector(".js_btn ").style.display = "block"
            // 選択したカテゴリーのIDを取得する
            let category_id = e.target.getAttribute("data-category-id")
            // カテゴリーIDをローカルストレージに保存する
            //(ページ更新されてもソートしたデータを保持しておくため)
            insertCategoriesID(category_id)
            changeCategoryBtn(getCategoryID())

            if(category_id === "all"){
                fetchDomainDataByAll()
            }else{
                fetchDomainDataByCategory(category_id)
            }
        })
    })
}
