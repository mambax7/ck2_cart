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
//ck2_stores_shipping編輯表單
function ck2_stores_shipping_form($shipping_sn=""){
	global $xoopsDB;
	include_once(XOOPS_ROOT_PATH."/class/xoopsformloader.php");
	//include_once(XOOPS_ROOT_PATH."/class/xoopseditor/xoopseditor.php");

	//抓取預設值
	if(!empty($shipping_sn)){
		$DBV=get_ck2_stores_shipping($shipping_sn);
	}else{
		$DBV=array();
	}

	//預設值設定

	$shipping_sn=(!isset($DBV['shipping_sn']))?"":$DBV['shipping_sn'];
	$shipping_name=(!isset($DBV['shipping_name']))?"":$DBV['shipping_name'];
	$shipping_desc=(!isset($DBV['shipping_desc']))?"":$DBV['shipping_desc'];
	$shipping_pay=(!isset($DBV['shipping_pay']))?"":$DBV['shipping_pay'];
	$enable=(!isset($DBV['enable']))?"1":$DBV['enable'];

	$op=(empty($shipping_sn))?"insert_ck2_stores_shipping":"update_ck2_stores_shipping";
	//$op="replace_ck2_stores_shipping";

	$main="
	<link type='text/css' rel='stylesheet' href='".XOOPS_URL."/modules/ck2_cart/class/formValidator/style/validator.css'>
	<script src='".XOOPS_URL."/modules/ck2_cart/class/formValidator/jquery_last.js' type='text/javascript'></script>
	<script src='".XOOPS_URL."/modules/ck2_cart/class/formValidator/formValidator.js' type='text/javascript' charset='UTF-8'></script>
	<script src='".XOOPS_URL."/modules/ck2_cart/class/formValidator/formValidatorRegex.js' type='text/javascript' charset='UTF-8'></script>
	<script src='".XOOPS_URL."/modules/ck2_cart/class/formValidator/DateTimeMask.js' language='javascript' type='text/javascript'></script>
	<script type='text/javascript'>
	$(document).ready(function(){
	$.formValidator.initConfig({formid:'myForm',onerror:function(msg){alert(msg)}});



	//「配送名稱」欄位檢查
	$('#shipping_name').formValidator({
		onshow:'".sprintf(_MD_INPUT_VALIDATOR,_MA_CK2CART_SHIPPING_NAME)."',
		onfocus:'".sprintf(_MD_INPUT_VALIDATOR_CHK,'1','255')."',
		oncorrect:'OK!'
	}).inputValidator({
		min:1,
		max:255,
		onerror:'".sprintf(_MD_INPUT_VALIDATOR_ERROR,_MA_CK2CART_SHIPPING_NAME)."'
	});

	//「配送費用」欄位檢查
	$('#shipping_pay').formValidator({
		onshow:'".sprintf(_MD_INPUT_VALIDATOR,_MA_CK2CART_SHIPPING_PAY)."',
		onfocus:'".sprintf(_MD_INPUT_VALIDATOR_CHK,'1','255')."',
		oncorrect:'OK!'
	}).inputValidator({
		min:1,
		max:255,
		onerror:'".sprintf(_MD_INPUT_VALIDATOR_ERROR,_MA_CK2CART_SHIPPING_PAY)."'
	});
	});
	</script>
	<script defer='defer' src='".XOOPS_URL."/modules/ck2_cart/class/formValidator/datepicker/WdatePicker.js' type='text/javascript'></script>

	<form action='{$_SERVER['PHP_SELF']}' method='post' id='myForm' enctype='multipart/form-data'>
	<table class='form_tbl'>

	<input type='hidden' name='shipping_sn' value='{$shipping_sn}'>
	<tr><td class='title'>"._MA_CK2CART_SHIPPING_NAME."</td>
	<td class='col'><input type='text' name='shipping_name' size='40' value='{$shipping_name}' id='shipping_name'></td><td class='col'><div id='shipping_nameTip'></div></td></tr>";

	include(XOOPS_ROOT_PATH."/modules/ck2_cart/class/fckeditor/fckeditor.php") ;
	$oFCKeditor = new FCKeditor('shipping_desc') ;
	$oFCKeditor->BasePath	= XOOPS_URL."/modules/ck2_cart/class/fckeditor/" ;
	$oFCKeditor->Config['AutoDetectLanguage']=false;
	$oFCKeditor->Config['DefaultLanguage']		= 'zh' ;
	$oFCKeditor->ToolbarSet ='my';
	$oFCKeditor->Width = '544' ;
	$oFCKeditor->Height = '150' ;
	$oFCKeditor->Value =$shipping_desc;
	$shipping_desc_editor=$oFCKeditor->CreateHtml() ;

	$main.="<tr><td class='title'>"._MA_CK2CART_SHIPPING_DESC."</td>
	<td class='col' colspan=2>$shipping_desc_editor<div id='shipping_descTip'></div></td></tr>
	<tr><td class='title'>"._MA_CK2CART_SHIPPING_PAY."</td>
	<td class='col'><input type='text' name='shipping_pay' size='20' value='{$shipping_pay}' id='shipping_pay'></td><td class='col'><div id='shipping_payTip'></div></td></tr>
	<tr><td class='title'>"._MA_CK2CART_SHIPPING_ENABLE."</td>
	<td class='col'>
	<input type='radio' name='enable' id='enable' value='1' ".chk($enable,'1','1').">"._MA_CK2CART_SHIPPING_IS_ENABLE."
	<input type='radio' name='enable' id='enable' value='0' ".chk($enable,'0').">"._MA_CK2CART_SHIPPING_IS_UNABLE."</td><td class='col'>
	<input type='hidden' name='op' value='{$op}'>
	<input type='submit' value='"._MA_SAVE."'><div id='enableTip'></div></td></tr>
	</table>
	</form>";

	$main.=list_ck2_stores_shipping(1,true);
	//raised,corners,inset
	$main=div_3d(_MA_SHIPPING_INPUT_FORM,$main,"raised");

	return $main;
}

//新增資料到ck2_stores_shipping中
function insert_ck2_stores_shipping(){
	global $xoopsDB;
	$stores_sn=get_stores_sn();
	$sql = "insert into ".$xoopsDB->prefix("ck2_stores_shipping")." (`stores_sn`,`shipping_name`,`shipping_desc`,`shipping_pay`,`enable`) values('{$stores_sn}','{$_POST['shipping_name']}','{$_POST['shipping_desc']}','{$_POST['shipping_pay']}','{$_POST['enable']}')";
	$xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());

	//取得最後新增資料的流水編號
	$shipping_sn=$xoopsDB->getInsertId();
	return $shipping_sn;
}

//列出所有ck2_stores_shipping資料
function list_ck2_stores_shipping($show_function=1,$nodiv=false){
	global $xoopsDB,$xoopsModule;
	$MDIR=$xoopsModule->getVar('dirname');
	$stores_sn=get_stores_sn();
	$sql = "select * from ".$xoopsDB->prefix("ck2_stores_shipping")." where stores_sn='$stores_sn'";

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
	function delete_ck2_stores_shipping_func(shipping_sn){
		var sure = window.confirm('"._BP_DEL_CHK."');
		if (!sure)	return;
		location.href=\"{$_SERVER['PHP_SELF']}?op=delete_ck2_stores_shipping&shipping_sn=\" + shipping_sn;
	}
	</script>
	<table id='tbl' style='width:100%;'>
	<tr><th>"._MA_CK2CART_SHIPPING_SN."</th>
	<th>"._MA_CK2CART_SHIPPING_NAME."</th>
	<th>"._MA_CK2CART_SHIPPING_PAY."</th>
	<th>"._MA_CK2CART_SHIPPING_ENABLE."</th>
	$function_title</tr>
	<tbody>";

	while($all=$xoopsDB->fetchArray($result)){
	  //以下會產生這些變數： $shipping_sn,$shipping_name,$shipping_desc,$shipping_pay,$enable
    foreach($all as $k=>$v){
      $$k=$v;
    }
		$fun=($show_function)?"<td>
		<a href='{$_SERVER['PHP_SELF']}?op=ck2_stores_shipping_form&shipping_sn=$shipping_sn'><img src='".XOOPS_URL."/modules/{$MDIR}/images/edit.gif' alt='"._BP_EDIT."'></a>
		<a href=\"javascript:delete_ck2_stores_shipping_func($shipping_sn);\"><img src='".XOOPS_URL."/modules/{$MDIR}/images/del.gif' alt='"._BP_DEL."'></a></td>":"";
		
		$status=($enable=='1')?_MA_CK2CART_SHIPPING_IS_ENABLE:_MA_CK2CART_SHIPPING_IS_UNABLE;
		
		$data.="<tr>
		<td>{$shipping_sn}</td>
		<td>{$shipping_name}</td>
		<td>{$shipping_pay}</td>
		<td>{$status}</td>
		$fun</tr>";
	}

	$data.="
	<tr>
	<td colspan=5 class='bar'>
	{$bar}</td></tr>
	</tbody>
	</table>";
	
	if($nodiv)return $data;

	//raised,corners,inset
	$main=div_3d("",$data,"corners");

	return $main;
}



//更新ck2_stores_shipping某一筆資料
function update_ck2_stores_shipping($shipping_sn=""){
	global $xoopsDB;
	$sql = "update ".$xoopsDB->prefix("ck2_stores_shipping")." set  `shipping_name` = '{$_POST['shipping_name']}', `shipping_desc` = '{$_POST['shipping_desc']}', `shipping_pay` = '{$_POST['shipping_pay']}', `enable` = '{$_POST['enable']}' where shipping_sn='$shipping_sn'";
	$xoopsDB->queryF($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());
	return $shipping_sn;
}

//刪除ck2_stores_shipping某筆資料資料
function delete_ck2_stores_shipping($shipping_sn=""){
	global $xoopsDB;
	$sql = "delete from ".$xoopsDB->prefix("ck2_stores_shipping")." where shipping_sn='$shipping_sn'";
	$xoopsDB->queryF($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());
}

/*-----------執行動作判斷區----------*/
$op = (!isset($_REQUEST['op']))? "main":$_REQUEST['op'];

switch($op){

	//更新資料
	case "update_ck2_stores_shipping":
	update_ck2_stores_shipping($_POST['shipping_sn']);
	header("location: {$_SERVER['PHP_SELF']}");
	break;

	//新增資料
	case "insert_ck2_stores_shipping":
	insert_ck2_stores_shipping();
	header("location: {$_SERVER['PHP_SELF']}");
	break;
	
	//輸入表格
	case "ck2_stores_shipping_form":
	$main=ck2_stores_shipping_form($_GET['shipping_sn']);
	break;

	//刪除資料
	case "delete_ck2_stores_shipping":
	delete_ck2_stores_shipping($_GET['shipping_sn']);
	header("location: {$_SERVER['PHP_SELF']}");
	break;

	//預設動作
	default:
	$main=ck2_stores_shipping_form($_GET['shipping_sn']);
	break;


}

/*-----------秀出結果區--------------*/
xoops_cp_header();
echo "<link rel='stylesheet' type='text/css' media='screen' href='../module.css' />";
echo menu_interface();
echo $main;
xoops_cp_footer();

?>
