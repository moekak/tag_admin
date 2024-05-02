import {
  sendErrorLog,
  redirectToErrorPage,
  hideCollapse,
  makeParentDivEmpty,
  appendSearchResults,
  setSelectedDomainIdAndName,
  setSelectedTagIdAndName,
} from "@index/index.js";

export const fetchDomainData = (copy_site_btn) => {
  // 検索フォームで入力された値
  const search_input_field = document.getElementById("js_search_domain");
  let timeout;
  search_input_field.addEventListener("input", () => {
    clearTimeout(timeout);
    //ユーザーが入力を終えて300ミリ秒以上経過しないと、検索リクエストが送信されないようにするため
    // （検索リクエストの過度な送信を防ぐ）
    const domain_id = document.getElementById("js_domain_id").value;
    timeout = setTimeout(() => {
      const data = {
        keyword: search_input_field.value,
        domain_id: domain_id || "",
      };

      fetch(`${process.env.API_URL}/searchDomainFetch.php`, {
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


          // 検索結果が一つもなかったら、何も表示させない
          if (res.length <= 0) {
            makeParentDivEmpty(".domain_search_results_container");
          } else {
            appendSearchResults(
              res,
              ".domain_search_results_container",
              "domain_search_container_result"
            );
            const results = document.querySelectorAll(
              ".domain_search_container_result"
            );
            // 検索結果で表示したなにかしらをせんたくしたときの処理
            results.forEach((result) => {
              result.addEventListener("click", (e) => {
                setSelectedDomainIdAndName(copy_site_btn, e);
                hideCollapse(document.getElementById("collapseExample1"));
              });
            });
          }
        })
        .catch((error) => {
          sendErrorLog(error);
          redirectToErrorPage();
        });
    }, 300); // 300ms遅延
  });
};


let timeout;

export const fetchTagDataWithReference = (copy_tag, data) => {
    clearTimeout(timeout);

 
  //ユーザーが入力を終えて300ミリ秒以上経過しないと、検索リクエストが送信されないようにするため
  // （検索リクエストの過度な送信を防ぐ）

  timeout = setTimeout(() => {
    fetch(`${process.env.API_URL}/searchDomainFetch.php`, {
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
        // 既に見たidを記録するためのSetオブジェクトを作成
        const seenIds = new Set();

        // filter関数を使って、重複しない要素だけの新しい配列を作成
        const uniqueArray = res.filter((res) => {
          if (seenIds.has(res.id)) {
            // すでに見たidの場合はfalseを返してフィルターする
            return false;
          } else {
            // 見たことがないidの場合はSetに追加してtrueを返す
            seenIds.add(res.id);
            return true;
          }
        });

        appendSearchResults(
          uniqueArray,
          ".tag_search_results_container",
          "tag_search_container_result"
        );
        const results = document.querySelectorAll(
          ".tag_search_container_result"
        );
        // 検索結果で表示したなにかしらをせんたくしたときの処理
        results.forEach((result) => {
          result.addEventListener("click", (e) => {
            setSelectedTagIdAndName(copy_tag, e);
            hideCollapse(document.getElementById("tag_reference_collapse"));
          });
        });
      })
      .catch((error) => {
        sendErrorLog(error);
        redirectToErrorPage();
      });
  }, 300); // 300ms遅延
};
export const fetchTagData = (copy_tag, data) => {


  clearTimeout(timeout);
  //ユーザーが入力を終えて300ミリ秒以上経過しないと、検索リクエストが送信されないようにするため
  // （検索リクエストの過度な送信を防ぐ）

  timeout = setTimeout(() => {
    fetch(`${process.env.API_URL}/getTagReferenceInfoFetch.php`, {
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
    
        // 既に見たidを記録するためのSetオブジェクトを作成
        const seenIds = new Set();

        // filter関数を使って、重複しない要素だけの新しい配列を作成
        const uniqueArray = res.filter((res) => {
          if (seenIds.has(res.id)) {
            // すでに見たidの場合はfalseを返してフィルターする
            return false;
          } else {
            // 見たことがないidの場合はSetに追加してtrueを返す
            seenIds.add(res.id);
            return true;
          }
        });

        appendSearchResults(
          uniqueArray,
          ".tag_search_results_container",
          "tag_search_container_result"
        );
        const results = document.querySelectorAll(
          ".tag_search_container_result"
        );
        // 検索結果で表示したなにかしらをせんたくしたときの処理
        results.forEach((result) => {
          result.addEventListener("click", (e) => {
            setSelectedTagIdAndName(copy_tag, e);
            hideCollapse(document.getElementById("tag_reference_collapse"));
          });
        });
      })
      .catch((error) => {
        sendErrorLog(error);
        redirectToErrorPage();
      });
  }, 300); // 300ms遅延
};
