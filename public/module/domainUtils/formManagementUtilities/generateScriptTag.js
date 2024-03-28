
export const copyTextToCplipboard = (btn, txt)=>{
  btn.addEventListener("click", ()=>{
    
    navigator.clipboard.writeText(txt).then(()=>{
      alert("scriptタグがコピーされました")

     
    }).catch(err =>{
      console.log(err);
    })

  })
}