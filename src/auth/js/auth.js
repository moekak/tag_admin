const login_btn = document.getElementById("js_login_btn");
const input_name = document.getElementById("js_input_name");
const input_password = document.getElementById("js_input_password");


// ログインのフロント側のバリデーション
let values ={
    name: "",
    password: ""
}
input_name.addEventListener("input", ()=>{
    values["name"] = input_name.value
    changeBtnStyle()
})
input_password.addEventListener("input", ()=>{
    values["password"] = input_password.value
    changeBtnStyle()
})


const changeBtnStyle = ()=>{
    if(values["name"] && values["password"]){
        login_btn.classList.remove("disabled_btn")
    }else{
        login_btn.classList.add("disabled_btn")
    }
}