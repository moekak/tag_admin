
import { inputValue } from "@index/index.js";


export const insertDataToLocalstorage = ()=>{
    const ArrayString = JSON.stringify(inputValue);
    localStorage.setItem("data", ArrayString);
}

export const isLocalStorageDataExisted = () =>{
    const ObjectString  = localStorage.getItem("data");
    const data          = JSON.parse(ObjectString)
    return data;
}

export const unsetLocalStorage = (key) =>{
    localStorage.removeItem(key)
}

export const insertCategoriesID = (id)=>{
    localStorage.setItem("category_id", id)
    
}

export const getCategoryID = ()=>{
    return localStorage.getItem("category_id")
}

export const clearLocalStorage = ()=>{
    localStorage.clear()
}