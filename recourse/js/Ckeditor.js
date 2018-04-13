const basicUrl = `./additions/`; // Kcfinder的基本Url

const editorConfig = {
	toolbar: [
		{name: 'clipboard', items: ['Undo', 'Redo']},
		{name: 'paragraph', items: ['NumberedList', 'BulletedList']},
		{name: 'insert', items: ['Image', 'Table', 'Youtube', 'Mathjax']},
		{name: 'links', items: ['Link', 'Unlink']},
		// {name: 'document', items: ['Print', 'Source']}
	],
	extraPlugins: 'mathjax,pastefromword',
	disallowedContent: 'img{width,height,float}',
	extraAllowedContent: 'img[width,height,align];span{background}',
	filebrowserUploadUrl: `${basicUrl}/kcfinder/upload.php?opener=ckeditor&type=files`,
	filebrowserImageUploadUrl: `${basicUrl}/kcfinder/upload.php?opener=ckeditor&type=images`,
	filebrowserFlashUploadUrl: `${basicUrl}/kcfinder/upload.php?opener=ckeditor&type=flash`,
	mathJaxLib: 'https://cdnjs.cloudflare.com/ajax/libs/mathjax/2.7.0/MathJax.js?config=TeX-AMS_HTML',
	mathJaxClass: 'mjx'
};

/**
 * @函式名稱 autoTransferInlineEditor
 * @param editor_id 想轉變成編輯器的目標ＩＤ
 * @用法 呼叫他 會轉變成inline編輯器
 */
function autoTransferInlineEditor(editor_id) {
	CKEDITOR.inline(editor_id, editorConfig);
	switchReadonlyMode(editor_id);
}

/**
 * @函式名稱 autoTransferInlineEditor
 * @param editor_id 想轉變成編輯器的目標ＩＤ
 * @用法 呼叫他 會轉變成replace編輯器
 */
function autoTransferReplaceEditor(editor_id) {
	CKEDITOR.replace(editor_id, editorConfig);
}

/**
 * @函式名稱  changeMathIntoTex()
 * @功能      刷新頁面
 * @觸發方式   呼叫它
 * @輸入      無
 * @輸出      無
 * @備註		不需要管是否該區域能不能編輯
 */
function changeMathIntoTex() {
	MathJax.Hub.Queue(["Typeset", MathJax.Hub, this.formula]); //Rewrite Tex code in Data
}

/**
 * @函式名稱    outputEditorContent
 * @功能        Editor的內容存成Data輸出
 * @觸發方式     事件呼叫函式 觸發，將Editor內部內容抓出來
 * @輸入變數     editor_input : (String) 目標編輯器的id
 * @函式輸出    editor_content : (String) 目標編輯器的內文,Html格式
 */
function outputEditorContent(editor_input) {
	//Output Data from id = 'ckeditor' editor place(Textarea/Div);
	return CKEDITOR.instances[editor_input].getData();
}

/**
 * @函式名稱 inputOriginalData
 * @param editor_output 輸出目標編輯器的id
 * @param data_content 輸出內容
 * @用法 將內容塞進另一個編輯器內
 */
function inputOriginalData(editor_output, data_content) {
	CKEDITOR.instances[editor_output].setData(data_content);
	CKEDITOR.instances[editor_output].setReadOnly(false);
	switchReadonlyMode(editor_output);
}

/**
 * @函式名稱    inputEditorContent
 * @功能        將Data的東西放上目標位置
 * @觸發方式     事件呼叫函式 觸發，將Data內部內容部署到目標位置
 * @輸入變數     target_id : (String) 要部署的id | editor_content : (String) 目標資料
 * @函式輸出     無
 * @備註            使用此function並不會使目標區域變成可編輯;不會使目標區域便編輯器
 */
function inputEditorContent(target_id, editor_content) {
	document.getElementById(target_id).innerHTML = editor_content;//insert What you write in editor
	changeMathIntoTex();
}

/**
 * @函式名稱   directTransfer2Input
 * @功能       直接將editor內容轉入目標位置
 * @觸發方式     事件呼叫函式 觸發，將editor內容部署到目標位置
 * @輸入變數     target_id : (String) 要部署的id | editor_input : (String) 編譯器id
 * @函式輸出     無
 */
function directTransfer2Input(editor_input, target_id) {
	inputEditorContent(target_id, outputEditorContent(editor_input));
}

/**
 * @函式名稱 directTransfer2Input
 * @param editor_input 輸出目標id
 * @param editor_output 輸出目標編輯器的id
 * @用法 將內容塞進另一個輸入目標位置id內 會轉換目標位置成為inline 然後將值輸入進去
 */
function directTransfer2Input2(editor_input, editor_output) {
	inputOriginalData(editor_output, CKEDITOR.instances[editor_input].getData());
}

/**
 * @函式名稱 switchReadonlyMode
 * @param target_id 輸出目標區塊的id
 * @用法 將想修改的區域 id 輸入,轉成ReadOnly
 */
function switchReadonlyMode(target_id) {
	let introduction = document.getElementById(target_id);
	introduction.setAttribute("contenteditable", false);
}
