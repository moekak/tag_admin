const copy_btn = document.querySelector(".js_copy_btn")

copy_btn.classList.add("menu_active")

const directory_btn = document.querySelector(".js_directory_btn")
const directory_site = document.querySelector(".js_directory_site")
const copy_site = document.querySelector(".js_copy_site")

if(directory_btn !== null){
    directory_btn.addEventListener("click", ()=>{
        copy_site.classList.add("hidden")
        directory_site.classList.remove("hidden")
    }) 
}
if(copy_btn !== null){
    copy_btn.addEventListener("click", ()=>{
        copy_site.classList.remove("hidden")
        directory_site.classList.add("hidden")
    }) 
}