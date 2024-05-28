// 検索結果を表示させるテンプレートの生成
export const createDiv = (res, className)=>{

    const id = res["domain_type"] == "directory" ? res["id"] : res["original_parent_id"]
    const template  = `<p class="label" style="margin: 0;" data-category-id="${res["domain_category_id"]}" data-id="${res["id"]}" data-original-id="${id}">${res["domain_name"]} </p>`
    const tempDiv   = document.createElement("div");
    tempDiv.classList.add(className)
    tempDiv.innerHTML = template.trim();

    return tempDiv;
}
