// 検索結果を表示させるテンプレートの生成
export const createDiv = (res, className)=>{
    const template  = `<p class="label" style="margin: 0;" data-id="${res["id"]}">${res["domain_name"]} </p>`
    const tempDiv   = document.createElement("div");
    tempDiv.classList.add(className)
    tempDiv.innerHTML = template.trim();

    return tempDiv;
}
export const createPtagForTagDeleteModal = (data)=>{
    return new Promise((resolve)=>{
        const div = document.querySelector(".domain_index");

        div.innerHTML = "";
        let template = "";

        if(data.length > 0){

            data.forEach((data)=>{
                template  = `<a href="${process.env.SYSTEM_URL}showTag/?id=${data["id"]}">${data["domain_name"]}</a><br>`
                div.insertAdjacentHTML("beforeend", template.trim())
            })
        }else{  
            template = `<p class="txt_small">このタグを使用してるドメインはありません</p>`
            div.insertAdjacentHTML("beforeend", template.trim())
        }

        resolve()
        
       
    })
    
}

// ドメイン情報をカテゴリーごとに表示させる
export const createIndexDiv = (resArray)=>{

    const table = document.querySelector(".js_table");

    table.innerHTML = "";

    const template2 = 
        ` <thead style="position: sticky; top: 0;" id="js_table">
            <tr>
                <th scope="col" style="width: 376px;">ドメイン名</th>
                <th scope="col">オリジナルLP</th>
                <th scope="col">ステータス</th>
                <th scope="col">ドメインID</th>
                <th></th>
            </tr>
        </thead>` 

    table.insertAdjacentHTML("beforeend", template2.trim())

    resArray.forEach((res)=>{

        let copySites = res["copy"] == undefined ? "" :  createIndexCopyDiv(res["copy"])
        let directorySites = res["directory"] == undefined ? "" : createIndexDirectoryDiv(res["directory"])
        const domainStatus      = res["domain_type"] === "original" ? "〇" : "";
        const domainActive      = res["is_active"] === "0" ? "red" : "green";
        const domainActiveText  = res["is_active"] === "0" ? "未使用" : "使用";
        const style = res["domain_type"] === "original" ? "table-light" : ""
        const random_domain_id = res["random_domain_id"] == "0" ? res["tag_reference_randomID"] : res["random_domain_id"]
        const tag_domain_id = res["tag_reference_id"] != null ?  res["tag_reference_id"] : res["id"]

        const redirect_url = `${process.env.SYSTEM_URL}showTag/?id=${res["id"]}`
        const template = 
        `<tbody>
            <tr class="${style}" data-id=${res["id"]}>
                <td class="domain_name align-middle"><a href="${redirect_url}" class="textdecoration_none">${res["domain_name"]}</a></td>
                <td class="original_check align-middle">${domainStatus}</td>
                <td class="align-middle ${domainActive}">${domainActiveText}</td>
                <td class="text_color align-middle">${random_domain_id}</td>
                <td class="text_color align-middle edit_modal_btns" data-id="${res["id"]}" data-id-active=${res["is_active"]} data-tag-domain-id=${tag_domain_id}><i class="fas fa-ellipsis-v text_color"></i></td>                                 
            </tr>
            ${directorySites}
            ${copySites}
        </tbody>` 

        table.insertAdjacentHTML('beforeend', template.trim());
    })
}
export const createIndexDivForMore = (resArray)=>{
    const table = document.querySelector(".js_table");

    resArray.forEach((res)=>{

        let copySites = res["copy"] == undefined ? "" :  createIndexCopyDiv(res["copy"])
        let directorySites = res["directory"] == undefined ? "" : createIndexDirectoryDiv(res["directory"])
        const domainStatus      = res["domain_type"] === "original" ? "〇" : "";
        const domainActive      = res["is_active"] === "0" ? "red" : "green";
        const domainActiveText  = res["is_active"] === "0" ? "未使用" : "使用";
        const style = res["domain_type"] === "original" ? "table-light" : ""
        const random_domain_id = res["random_domain_id"] == "0" ? res["tag_reference_randomID"]  : res["random_domain_id"]
        const tag_domain_id = res["tag_reference_id"] != null ?  res["tag_reference_id"] : res["id"]

        const redirect_url = `${process.env.SYSTEM_URL}showTag/?id=${res["id"]}`
        const template = 
        `<tbody>
            <tr class="${style}" data-id=${res["id"]}>
                <td class="domain_name align-middle"><a href="${redirect_url}" class="textdecoration_none">${res["domain_name"]}</a></td>
                <td class="original_check align-middle">${domainStatus}</td>
                <td class="align-middle ${domainActive}">${domainActiveText}</td>
                <td class="text_color align-middle">${random_domain_id}</td>
                <td class="text_color align-middle edit_modal_btns" data-id="${res["id"]}" data-id-active=${res["is_active"]} data-tag-domain-id=${tag_domain_id}><i class="fas fa-ellipsis-v text_color"></i></td>                                 
            </tr>
            ${directorySites}
            ${copySites}
        </tbody>` 

        table.insertAdjacentHTML('beforeend', template.trim());
    })
   
    return table;
}
export const createIndexCopyDiv = (res)=>{
    
    let rows = "";

    res.forEach((item)=>{
        let directorySites = item["directory"] == undefined ? "" :  createIndexCopyDirectoryDiv(item["directory"]);
        const domainActive      = item["is_active"] === "0" ? "red" : "green";
        const domainActiveText  = item["is_active"] === "0" ? "未使用" : "使用";
        const random_domain_id  = item["random_domain_id"] == "0" ? item["tag_reference_randomID"]  : item["random_domain_id"]
        const redirect_url      = `${process.env.SYSTEM_URL}showTag/?id=${item["id"]}`
        const tag_domain_id = item["tag_reference_id"] != null ?  item["tag_reference_id"] : item["id"]

        rows += 
        `<tr class="js_copySites">
            <td class="domain_name align-middle"style="padding-left: 8%;">
                <div class="img_container">
                    <img src="${process.env.SYSTEM_URL}public/img/copy1.png" alt="" class="copy_icon">
                </div>
                <div class="hyper_link">
                    <a href="${redirect_url}" class="textdecoration_none">${item["domain_name"]}</a>
                </div>
            </td>
            <td class="original_check align-middle"></td>
            <td class="align-middle ${domainActive}">${domainActiveText}</td>
            <td class="text_color align-middle">${random_domain_id}</td>
            <td class="text_color align-middle edit_modal_btns js_tables" data-id="${item["id"]}" data-id-active=${item["is_active"]} data-tag-domain-id=${tag_domain_id}><i class="fas fa-ellipsis-v text_color"></i></td>                                 
        </tr>
        ${directorySites}

        ` 
    })
    return rows
}
export const createIndexCopyDirectoryDiv = (res)=>{
    let rows = "";

    res.forEach((item)=>{
    

        const domainActive      = item["is_active"] === "0" ? "red" : "green";
        const domainActiveText  = item["is_active"] === "0" ? "未使用" : "使用";
        const random_domain_id  = item["random_domain_id"] == "0" ? item["tag_reference_randomID"]  : item["random_domain_id"]
        const redirect_url      =  `${process.env.SYSTEM_URL}showTag/?id=${item["id"]}`
        const tag_domain_id = item["tag_reference_id"] != null ?  item["tag_reference_id"] : item["id"]

        rows += 
        `<tr class="js_copySites">
            <td class="domain_name align-middle" style="padding-left: 16%;">
                <div class="img_container">
                    <img src="${process.env.SYSTEM_URL}public/img/folder.png" alt="" class="copy_icon">
                </div>
                <div class="hyper_link">
                    <a href="${redirect_url}" class="textdecoration_none">${item["domain_name"]}</a>
                </div>
            </td>
            <td class="original_check align-middle"></td>
            <td class="align-middle ${domainActive}">${domainActiveText}</td>
            <td class="text_color align-middle">${random_domain_id}</td>
            <td class="text_color align-middle edit_modal_btns js_tables" data-id="${item["id"]}" data-id-active=${item["is_active"]} data-tag-domain-id=${tag_domain_id}><i class="fas fa-ellipsis-v text_color"></i></td>                                 
        </tr>
        `    
    })

    return rows
}
export const createIndexDirectoryDiv = (res)=>{
    let rows = "";

    res.forEach((item)=>{

        const domainActive      = item["is_active"] === "0" ? "red" : "green";
        const domainActiveText  = item["is_active"] === "0" ? "未使用" : "使用";
        const random_domain_id  = item["random_domain_id"] == "0" ? item["tag_reference_randomID"] : item["random_domain_id"]
        const redirect_url      =  `${process.env.SYSTEM_URL}showTag/?id=${item["id"]}`
        const tag_domain_id =  item["tag_reference_id"] != null ?  item["tag_reference_id"] : item["id"]

        rows += 
        `<tr class="js_copySites">
            <td class="domain_name align-middle" style="padding-left: 8%;">
                <div class="img_container">
                    <img src="${process.env.SYSTEM_URL}public/img/folder.png" alt="" class="copy_icon">
                </div>
                <div class="hyper_link">
                    <a href="${redirect_url}" class="textdecoration_none">${item["domain_name"]}</a>
                </div></td>
            <td class="original_check align-middle"></td>
            <td class="align-middle ${domainActive}">${domainActiveText}</td>
            <td class="text_color align-middle">${random_domain_id}</td>
            <td class="text_color align-middle edit_modal_btns js_tables" data-id="${item["id"]}" data-id-active=${item["is_active"]} data-tag-domain-id=${tag_domain_id}><i class="fas fa-ellipsis-v text_color"></i></td>                                 
        </tr>`    
    })

    return rows

   
}
