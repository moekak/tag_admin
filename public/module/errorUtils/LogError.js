export const sendErrorLog = (error)=>{

    const error_mag = `Fetch error: ${error.message}, Detailed error: ${error.stack}, ${new Date()}`
    fetch(`${process.env.API_URL}/logErrorFetch.php`, {
        // 第1引数に送り先
        method: "POST", // メソッド指定
        headers: {
            "Content-Type": "application/json",
        }, // jsonを指定
    
        body: JSON.stringify(error_mag), // json形式に変換して添付
    })
    
}

export const redirectToErrorPage = ()=>{
    window.location.href = `${process.env.SYSTEM_URL}error`

}