import {insertTagToObj, setTagDataToInputField } from "@index/index.js";

export const acEditor =(tag_category, clickCount)=>{
    const name = `editor${clickCount}`
    var editor = ace.edit(name);
    editor.$blockScrolling = Infinity;
    editor.setOptions({
        enableBasicAutocompletion: false,
        enableSnippets: false,
        enableLiveAutocompletion: false
    });
    editor.setTheme("ace/theme/monokai");
    editor.getSession().setMode("ace/mode/html");
     // エディタの内容が変更されるたびにイベントをトリガー
    editor.getSession().on('change', function() {
        var content = editor.getValue();
        insertTagToObj(tag_category, content)
        setTagDataToInputField()
        // console.log(content);  // エディタの内容がリアルタイムでコンソールに表示される
    });
}
