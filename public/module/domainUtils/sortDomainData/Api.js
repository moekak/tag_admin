import {createIndexDiv,getCategoryID,displayEditModalAndInitializing,sendErrorLog,redirectToErrorPage} from "@index/index.js";

//カテゴリーごとのソート
export const fetchDomainDataByCategory = (category_id) => {
  fetch(`${process.env.API_URL}/sortDomainDataByCategory.php`, {
    // 第1引数に送り先
    method: "POST", // メソッド指定
    headers: {
      "Content-Type": "application/json",
    }, // jsonを指定

    body: JSON.stringify(category_id), // json形式に変換して添付
  })
    .then((response) => {
      if (!response.ok) {
        throw new Error("サーバーエラーが発生しました。");
      }
      return response.json();
    })
    .then((res) => {

      console.log(res);
      // 結果分だけ表示させる
      createIndexDiv(res);
      displayEditModalAndInitializing()
   
    })
    .catch((error) => {
      sendErrorLog(error);
      redirectToErrorPage();
    });
};


export const fetchDomainDataByAll = () => {
  fetch(`${process.env.API_URL}/getDomainInfoAll.php`, {
    // 第1引数に送り先
    method: "POST", // メソッド指定
    headers: {
      "Content-Type": "application/json",
    }, // jsonを指定
  })
    .then((response) => {
      if (!response.ok) {
        throw new Error("サーバーエラーが発生しました。");
      }
      return response.json();
    })
    .then((res) => {


      // 結果分だけ表示させる
      createIndexDiv(res);
      displayEditModalAndInitializing()

    })
    .catch((error) => {
      sendErrorLog(error);
      redirectToErrorPage();
    });
};

// 非同期関数Promiseオブジェクトを返す
export const fetchDomainOriginalData = (id) => {
  const data = id;
  return fetch(`${process.env.API_URL}/getOriginalDomainBySearch.php`, {
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

// 検索ワードからデータを非同期に取得する処理
export const fetchDomainInfoBySearch = () => {
  const search_input_field = document.querySelector(".js_search_domain");
  let timeout;

  search_input_field.addEventListener("input", (e) => {
  
    clearTimeout(timeout);
    timeout = setTimeout(() => {
      if(e.target.value == ""){
        fetchDomainDataByAll()
      }else{
        const data = {
          keyword: search_input_field.value,
          category_id: getCategoryID(),
        };
  
        fetch(`${process.env.API_URL}/searchDomainDataFetch.php`, {
          method: "POST",
          headers: { "Content-Type": "application/json" },
          body: JSON.stringify(data),
        })
          .then((response) => {
            if (!response.ok) {
              throw new Error("サーバーエラーが発生しました。");
            }
            return response.json();
          })
          .then((res) => {
              createIndexDiv(res);
              displayEditModalAndInitializing()
          })
          .catch((error) => {
            sendErrorLog(error);
            redirectToErrorPage();
          });
      }
      
    }, 300);
  });
};
