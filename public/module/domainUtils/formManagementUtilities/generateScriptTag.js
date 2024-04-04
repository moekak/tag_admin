
export const copyTextToCplipboard = (btn, txt)=>{
  btn.addEventListener("click", ()=>{
    
    navigator.clipboard.writeText(txt).then(()=>{

      document.querySelector(".js_copy_success").classList.remove("hidden")

      setTimeout(()=>{
        document.querySelector(".js_copy_success").classList.add("hidden")
      }, 1000)

     
    }).catch(err =>{
     
    })

  })
}