<?php
//  ------------------------------------------------------------------------ //
// 本模組由 tad 製作
// 製作日期：2008-12-02
// $Id:$
// ------------------------------------------------------------------------- //

/*-----------引入檔案區--------------*/
include "../../../include/cp_header.php";
include "../function.php";

/*-----------function區--------------*/
//ck2_stores_payment編輯表單
function ck2_stores_payment_form($payment_sn=""){
	global $xoopsDB,$xoopsUser;
	//include_once(XOOPS_ROOT_PATH."/class/xoopsformloader.php");
	//include_once(XOOPS_ROOT_PATH."/class/xoopseditor/xoopseditor.php");

	//抓取預設值
	if(!empty($payment_sn)){
		$DBV=get_ck2_stores_payment($payment_sn);
	}else{
		$DBV=array();
	}

	//預設值設定


	//設定「payment_sn」欄位預設值
	$payment_sn=(!isset($DBV['payment_sn']))?"":$DBV['payment_sn'];

	//設定「payment_name」欄位預設值
	$payment_name=(!isset($DBV['payment_name']))?"":$DBV['payment_name'];

	//設定「payment_desc」欄位預設值
	$payment_desc=(!isset($DBV['payment_desc']))?"":$DBV['payment_desc'];

	//設定「enable」欄位預設值
	$enable=(!isset($DBV['enable']))?"":$DBV['enable'];

	$op=(empty($payment_sn))?"insert_ck2_stores_payment":"update_ck2_stores_payment";
	//$op="replace_ck2_stores_payment";

	$main="
	<link type='text/css' rel='stylesheet' href='".XOOPS_URL."/modules/ck2_cart/class/formValidator/style/validator.css'>
	<script src='".XOOPS_URL."/modules/ck2_cart/class/formValidator/jquery_last.js' type='text/javascript'></script>
	<script src='".XOOPS_URL."/modules/ck2_cart/class/formValidator/formValidator.js' type='text/javascript' charset='UTF-8'></script>
	<script src='".XOOPS_URL."/modules/ck2_cart/class/formValidator/formValidatorRegex.js' type='text/javascript' charset='UTF-8'></script>
	<script src='".XOOPS_URL."/modules/ck2_cart/class/formValidator/DateTimeMask.js' language='javascript' type='text/javascript'></script>
	<script type='text/javascript'>
	$(document).ready(function(){
	$.formValidator.initConfig({formid:'myForm',onerror:function(msg){alert(msg)}});



	//「付款方式」欄位檢查
	$('#payment_name').formValidator({
		onshow:'".sprintf(_MD_INPUT_VALIDATOR,_MA_CK2CART_PAYMENT_NAME)."',
		onfocus:'".sprintf(_MD_INPUT_VALIDATOR_CHK,'1','255')."',
		oncorrect:'OK!'
	}).inputValidator({
		min:1,
		max:255,
		onerror:'".sprintf(_MD_INPUT_VALIDATOR_ERROR,_MA_CK2CART_PAYMENT_NAME)."'
	});
	});
	</script>
	<script defer='defer' src='".XOOPS_URL."/modules/ck2_cart/class/formValidator/datepicker/WdatePicker.js' type='text/javascript'></script>

	<form action='{$_SERVER['PHP_SELF']}' method='post' id='myForm' enctype='multipart/form-data'>
	<table class='form_tbl'>


	<!--付款方式編號-->
	<input type='hidden' name='payment_sn' value='{$payment_sn}'>

	<!--付款方式-->
	<tr><td class='title' nowrap>"._MA_CK2CART_PAYMENT_NAME."</td>
	<td class='col'><input type='text' name='payment_name' size='40' value='{$payment_name}' id='payment_name'></td><td class='col'><div id='payment_nameTip'></div></td></tr>";

	include(XOOPS_ROOT_PATH."/modules/ck2_cart/class/fckeditor/fckeditor.php") ;
	$oFCKeditor = new FCKeditor('payment_desc') ;
	$oFCKeditor->BasePath	= XOOPS_URL."/modules/ck2_cart/class/fckeditor/" ;
	$oFCKeditor->Config['AutoDetectLanguage']=false;
	$oFCKeditor->Config['DefaultLanguage']		= 'zh' ;
	$oFCKeditor->ToolbarSet ='my';  //Basic , Default
	$oFCKeditor->Width = '544' ;
	$oFCKeditor->Height = '150' ;
	$oFCKeditor->Value =$payment_desc;
	$payment_desc_editor=$oFCKeditor->CreateHtml() ;

	$main.="
	<!--相關說明-->
<tr><td class='title' nowrap>"._MA_CK2CART_PAYMENT_DESC."</td>
	<td class='col' colspan='2'>$payment_desc_editor<div id='payment_descTip'></div></td></tr>

	<!--狀態-->
	<tr><td class='title' nowrap>"._MA_CK2CART_PAYMENT_ENABLE."</td>
	<td class='col'>
	<input type='radio' name='enable' id='enable' value='1' ".chk($enable,'1','1').">"._MA_CK2CART_PAYMENT_IS_ENABLE."
	<input type='radio' name='enable' id='enable' value='0' ".chk($enable,'0').">"._MA_CK2CART_PAYMENT_IS_UNABLE."</td><td class='col'><input type='hidden' name='op' value='{$op}'>
	<input type='submit' value='"._MA_SAVE."'></td></tr>
	
	</table>
	</form>";
	
	$main.=list_ck2_stores_payment(1,true);

	//raised,corners,inset
	$main=div_3d(_MA_PAYMENT_INPUT_FORM,$main,"raised");

	return $main;
}

//新增資料到ck2_stores_payment中
function insert_ck2_stores_payment(){
	global $xoopsDB,$xoopsUser;
	$stores_sn=get_stores_sn();

	$sql = "insert into ".$xoopsDB->prefix("ck2_stores_payment")."
	(`stores_sn`,`payment_name` , `payment_desc` , `enable`)
	values('$stores_sn','{$_POST['payment_name']}' , '{$_POST['payment_desc']}' , '{$_POST['enable']}')";
	$xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());

	//取得最後新增資料的流水編號
	$payment_sn=$xoopsDB->getInsertId();
	return $payment_sn;
}

//列出所有ck2_stores_payment資料
function list_ck2_stores_payment($show_function=1,$nodiv=false){
	global $xoopsDB,$xoopsModule;
	$MDIR=$xoopsModule->getVar('dirname');
	$stores_sn=get_stores_sn();
	$sql = "select * from ".$xoopsDB->prefix("ck2_stores_payment")." where stores_sn='$stores_sn'";

	//PageBar(資料數, 每頁顯示幾筆資料, 最多顯示幾個頁數選項);
	$result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());
	$total=$xoopsDB->getRowsNum($result);

	$navbar = new PageBar($total, 20, 10);
	$mybar = $navbar->makeBar();
	$bar= sprintf(_BP_TOOLBAR,$mybar['total'],$mybar['current'])."{$mybar['left']}{$mybar['center']}{$mybar['right']}";
	$sql.=$mybar['sql'];

	$result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());

	$function_title=($show_function)?"<th>"._BP_FUNCTION."</th>":"";

	//刪除確認的JS
	$data="
	<script>
	function delete_ck2_stores_payment_func(payment_sn){
		var sure = window.confirm('"._BP_DEL_CHK."');
		if (!sure)	return;
		location.href=\"{$_SERVER['PHP_SELF']}?op=delete_ck2_stores_payment&payment_sn=\" + payment_sn;
	}
	</script>

	<table summary='list_table' id='tbl' style='width:100%;'>
	<tr>
	<th>"._MA_CK2CART_PAYMENT_SN."</th>
	<th>"._MA_CK2CART_PAYMENT_NAME."</th>
	<th>"._MA_CK2CART_PAYMENT_DESC."</th>
	<th>"._MA_CK2CART_ENABLE."</th>
	$function_title</tr>
	<tbody>";

	while($all=$xoopsDB->fetchArray($result)){
	  //以下會產生這些變數： $payment_sn , $payment_name , $payment_desc , $enable
    foreach($all as $k=>$v){
      $$k=$v;
    }

		$fun=($show_function)?"
		<td>
		<a href='{$_SERVER['PHP_SELF']}?op=ck2_stores_payment_form&payment_sn=$payment_sn'><img src='".XOOPS_URL."/modules/{$MDIR}/images/edit.gif' alt='"._BP_EDIT."'></a>
		<a href=\"javascript:delete_ck2_stores_payment_func($payment_sn);\"><img src='".XOOPS_URL."/modules/{$MDIR}/images/del.gif' alt='"._BP_DEL."'></a>
		</td>":"";
		
		$status=($enable=='1')?_MA_CK2CART_PAYMENT_IS_ENABLE:_MA_CK2CART_PAYMENT_IS_UNABLE;

		$data.="<tr>
		<td>{$payment_sn}</td>
		<td>{$payment_name}</td>
		<td>{$payment_desc}</td>
		<td>{$status}</td>
		$fun
		</tr>";
	}

	$data.="
	<tr>
	<td colspan=5 class='bar'>
	<!--a href='{$_SERVER['PHP_SELF']}?op=ck2_stores_payment_form'><img src='".XOOPS_URL."/modules/{$MDIR}/images/add.gif' alt='"._BP_ADD."' align='right'></a-->
	{$bar}</td></tr>
	</tbody>
	</table>";
	
	if($nodiv)return $data;

	//raised,corners,inset
	$main=div_3d("",$data,"corners");

	return $main;
}


//以流水號取得某筆ck2_stores_payment資料
function get_ck2_stores_payment($payment_sn=""){
	global $xoopsDB;
	if(empty($payment_sn))return;
	$sql = "select * from ".$xoopsDB->prefix("ck2_stores_payment")." where payment_sn='$payment_sn'";
	$result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());
	$data=$xoopsDB->fetchArray($result);
	return $data;
}

//更新ck2_stores_payment某一筆資料
function update_ck2_stores_payment($payment_sn=""){
	global $xoopsDB,$xoopsUser;


	$sql = "update ".$xoopsDB->prefix("ck2_stores_payment")." set
	 `payment_name` = '{$_POST['payment_name']}' ,
	 `payment_desc` = '{$_POST['payment_desc']}' ,
	 `enable` = '{$_POST['enable']}'
	where payment_sn='$payment_sn'";
	$xoopsDB->queryF($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());
	return $payment_sn;
}

//刪除ck2_stores_payment某筆資料資料
function delete_ck2_stores_payment($payment_sn=""){
	global $xoopsDB;
	$sql = "delete from ".$xoopsDB->prefix("ck2_stores_payment")." where payment_sn='$payment_sn'";
	$xoopsDB->queryF($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());
}



/*-----------執行動作判斷區----------*/
$op = (!isset($_REQUEST['op']))? "main":$_REQUEST['op'];

switch($op){

	//更新資料
	case "update_ck2_stores_payment":
	update_ck2_stores_payment($_POST['payment_sn']);
	header("location: {$_SERVER['PHP_SELF']}");
	break;
	
	//新增資料
	case "insert_ck2_stores_payment":
	insert_ck2_stores_payment();
	header("location: {$_SERVER['PHP_SELF']}");
	break;

	//輸入表格
	case "ck2_stores_payment_form":
	$main=ck2_stores_payment_form($_GET['payment_sn']);
	break;

	//刪除資料
	case "delete_ck2_stores_payment":
	delete_ck2_stores_payment($_GET['payment_sn']);
	header("location: {$_SERVER['PHP_SELF']}");
	break;

	//預設動作
	default:

		
		$main=ck2_stores_payment_form($_GET['payment_sn']);

	break;



}

/*-----------秀出結果區--------------*/
xoops_cp_header();
echo "<link rel='stylesheet' type='text/css' media='screen' href='../module.css' />";
echo menu_interface();
echo $main;
xoops_cp_footer();

?>
