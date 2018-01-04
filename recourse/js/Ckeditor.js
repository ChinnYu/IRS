/**
 * @函式名稱  changeMathIntoTex()
 * @功能      刷新頁面
 * @觸發方式   呼叫它
 * @輸入      無
 * @輸出      無
 */
function changeMathIntoTex() {
    MathJax.Hub.Queue(["Typeset", MathJax.Hub, this.formula]); //Rewrite Tex code in Data
}

/**
 * @函式名稱｜    outputEditorContent
 * @功能    |    Editor的內容存成Data輸出
 * @觸發方式 |    事件呼叫函式 觸發，將Editor內部內容抓出來
 * @輸入變數 |    editor_input : (String) 目標編輯器的id
 * @函式輸出 ｜   editor_content : (String) 目標編輯器的內文,Html格式
 */
function outputEditorContent(editor_input) {
    //Output Data from id = 'ckeditor' editor place(Textarea/Div);
    return CKEDITOR.instances[editor_input].getData();
}

/**
 * @函式名稱｜    inputEditorContent
 * @功能    |    將Data的東西放上目標位置
 * @觸發方式 |    事件呼叫函式 觸發，將Data內部內容部署到目標位置
 * @輸入變數 |    target_id : (String) 要部署的id | editor_content : (String) 目標資料
 * @函式輸出 ｜    無
 */
function inputEditorContent(target_id, editor_content) {
    document.getElementById(target_id).innerHTML = editor_content;//insert What you write in editor
    MathJax.Hub.Queue(["Typeset", MathJax.Hub, this.formula]); //Rewrite Tex code in Data
}

/**
 * @函式名稱｜    directTransfer2Input
 * @功能    |    直接將editor內容轉入目標位置
 * @觸發方式 |    事件呼叫函式 觸發，將editor內容部署到目標位置
 * @輸入變數 |    target_id : (String) 要部署的id | editor_input : (String) 編譯器id
 * @函式輸出 ｜    無
 */
function directTransfer2Input(editor_input, target_id) {
    inputEditorContent(target_id, outputEditorContent(editor_input));
}
