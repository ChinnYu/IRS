<?php session_start(); ?>
<!--上方語法為啟用session，此語法要放在網頁最前方-->
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<?php

//連接資料庫
//include("LoginDB.php");
//搜尋資料庫資料
//$sql = "SELECT * FROM member_table where username = '$id'";
//$result = mysql_query($sql);
//$row = @mysql_fetch_row($result);
//獲得是老師還學生身分
$id = $_POST['id'];//暫時用
$pw = $_POST['pwd'];//暫時用
$identity = 0; ////暫時用 0當老師

$row1='acl';//暫時用
$row2='acl23122';//暫時用
if($row1==$id&&$row2==$pw)
{
        //將帳號寫入session，方便驗證使用者身份
        //$_SESSION['username'] = $id;
		if($identity==0){
			//echo '<meta http-equiv=REFRESH CONTENT=1;url=TeacherHomepage.html>';
			echo '<meta http-equiv=REFRESH CONTENT=1;url=TeacherHome.html>';
		}
}
else
{
        echo '登入失敗!'.$id.$pw.'123';
		echo $row1.$row2;
		//echo $id.$pw;
        echo '<meta http-equiv=REFRESH CONTENT=1;url=index.html>';
}
?>