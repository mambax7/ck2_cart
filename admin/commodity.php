<?php
//  ------------------------------------------------------------------------ //
// 本模組由 tad 製作
// 製作日期：2008-12-02
// $Id:$
// ------------------------------------------------------------------------- //

/*-----------引入檔案區--------------*/
include "../../../include/cp_header.php";
include "../function.php";
include "admin_function.php";

/*-----------function區--------------*/
//ck2_stores_commodity_kinds編輯表單
function ck2_stores_commodity_kinds_form($kinds_sn=""){
	global $xoopsDB;

	$stores_sn=get_stores_sn();


	//抓取預設值
	if(!empty($kinds_sn)){
		$DBV=get_ck2_stores_commodity_kinds($kinds_sn);
	}else{
		$DBV=array();
	}

	//預設值設定

	$kinds_sn=(!isset($DBV['kinds_sn']))?"":$DBV['kinds_sn'];
	$of_kinds_sn=(!isset($DBV['of_kinds_sn']))?"0":$DBV['of_kinds_sn'];
	$stores_sn=(!isset($DBV['stores_sn']))?$stores_sn:$DBV['stores_sn'];
	$kind_title=(!isset($DBV['kind_title']))?"":$DBV['kind_title'];
	$kind_desc=(!isset($DBV['kind_desc']))?"":$DBV['kind_desc'];
	$kind_sort=(!isset($DBV['kind_sort']))?ck2_stores_commodity_kinds_max_sort():$DBV['kind_sort'];
	$kind_counter=(!isset($DBV['kind_counter']))?"":$DBV['kind_counter'];
	$enable=(!isset($DBV['enable']))?"1":$DBV['enable'];

	//設定「payment」欄位預設值
	$payment=(!isset($DBV['payment']))?"":$DBV['payment'];

	//設定「shipping」欄位預設值
	$shipping=(!isset($DBV['shipping']))?"":$DBV['shipping'];

	$commodity_kinds_select=commodity_kinds_select($stores_sn,0,$of_kinds_sn,$kinds_sn);

	$op=(empty($kinds_sn))?"insert_ck2_stores_commodity_kinds":"update_ck2_stores_commodity_kinds";
	//$op="replace_ck2_stores_commodity_kinds";
	
	
	//父分類選單
	if(empty($commodity_kinds_select)){
    $of_kinds_select="";
	}else{
    $of_kinds_select="<tr><td class='title'>"._MA_CK2CART_OF_KINDS_SN."</td>
	<td class='col'><select name='of_kinds_sn' size=1>
		<option value='' ".chk($of_kinds_sn,'','1','selected')."></option>
		$commodity_kinds_select
	</select></td></tr>";
	}

	$main="
	<link type='text/css' rel='stylesheet' href='".XOOPS_URL."/modules/ck2_cart/class/formValidator/style/validator.css'>
	<script src='".XOOPS_URL."/modules/ck2_cart/class/formValidator/jquery_last.js' type='text/javascript'></script>
	<script src='".XOOPS_URL."/modules/ck2_cart/class/formValidator/formValidator.js' type='text/javascript' charset='UTF-8'></script>
	<script src='".XOOPS_URL."/modules/ck2_cart/class/formValidator/formValidatorRegex.js' type='text/javascript' charset='UTF-8'></script>
	<script src='".XOOPS_URL."/modules/ck2_cart/class/formValidator/DateTimeMask.js' language='javascript' type='text/javascript'></script>
	<script type='text/javascript'>
	$(document).ready(function(){
	$.formValidator.initConfig({formid:'myForm',onerror:function(msg){alert(msg)}});



	//「分類名稱」欄位檢查
	$('#kind_title').formValidator({
		onshow:'".sprintf(_MD_INPUT_VALIDATOR,_MA_CK2CART_KIND_TITLE)."',
		onfocus:'".sprintf(_MD_INPUT_VALIDATOR_CHK,'1','255')."',
		oncorrect:'OK!'
	}).inputValidator({
		min:1,
		max:255,
		onerror:'".sprintf(_MD_INPUT_VALIDATOR_ERROR,_MA_CK2CART_KIND_TITLE)."'
	});

	//「分類排序」欄位檢查
	$('#kind_sort').formValidator({
		onshow:'".sprintf(_MD_INPUT_VALIDATOR,_MA_CK2CART_KIND_SORT)."',
		onfocus:'".sprintf(_MD_INPUT_VALIDATOR_CHK,'1','255')."',
		oncorrect:'OK!'
	}).inputValidator({
		min:1,
		max:255,
		onerror:'".sprintf(_MD_INPUT_VALIDATOR_ERROR,_MA_CK2CART_KIND_SORT)."'
	});
	});
	</script>
	<script defer='defer' src='".XOOPS_URL."/modules/ck2_cart/class/formValidator/datepicker/WdatePicker.js' type='text/javascript'></script>

	<form action='{$_SERVER['PHP_SELF']}' method='post' id='myForm' enctype='multipart/form-data'>
	<table class='form_tbl'>
		<tr><td class='title'>"._MA_CK2CART_KIND_SORT."</td>
	<td class='col'><input type='text' name='kind_sort' size='2' value='{$kind_sort}' id='kind_sort'></td></tr>
	$of_kinds_select

	<input type='hidden' name='stores_sn' value='{$stores_sn}'>
	<tr><td class='title'>"._MA_CK2CART_KIND_TITLE."</td>
	<td class='col'><input type='text' name='kind_title' size='20' value='{$kind_title}' id='kind_title'></td></tr>

	<tr><td class='title'>"._MA_CK2CART_COMMODITY_ENABLE."</td>
	<td class='col'>
	<input type='radio' name='enable' id='enable' value='1' ".chk($enable,'1','1').">"._MA_CK2CART_COMMODITY_IS_ENABLE."
	<input type='radio' name='enable' id='enable' value='0' ".chk($enable,'0').">"._MA_CK2CART_COMMODITY_IS_UNABLE."</td><td class='col'><input type='hidden' name='kinds_sn' value='{$kinds_sn}'>
	<input type='hidden' name='op' value='{$op}'>
	<input type='submit' value='"._MA_SAVE."'></td></tr>
	</table>
	</form>";
	

	$main.=list_ck2_stores_commodity_kinds($stores_sn,true);

	//raised,corners,inset
	$main=div_3d(_MA_CK2CART_COMMODITY_KIND_INPUT_FORM,$main,"raised");
	return $main;
}

//新增資料到ck2_stores_commodity_kinds中
function insert_ck2_stores_commodity_kinds(){
	global $xoopsDB;
	$sql = "insert into ".$xoopsDB->prefix("ck2_stores_commodity_kinds")." (`of_kinds_sn`,`stores_sn`,`kind_title`,`kind_desc`,`kind_sort`,`kind_counter`,`enable`) values('{$_POST['of_kinds_sn']}','{$_POST['stores_sn']}','{$_POST['kind_title']}','{$_POST['kind_desc']}','{$_POST['kind_sort']}','{$_POST['kind_counter']}','{$_POST['enable']}')";
	$xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());

	//取得最後新增資料的流水編號
	$kinds_sn=$xoopsDB->getInsertId();
	return $kinds_sn;
}

//列出所有ck2_stores_commodity_kinds資料
function list_ck2_stores_commodity_kinds($stores_sn="",$nodiv=false,$show_function=1){
	global $xoopsDB,$xoopsModule;
	$MDIR=$xoopsModule->getVar('dirname');
	$sql = "select * from ".$xoopsDB->prefix("ck2_stores_commodity_kinds")." where enable='1' order by kind_sort";
	$result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());

	$function_title=($show_function)?"<th>"._BP_FUNCTION."</th>":"";

	//刪除確認的JS
	$data="
	<script>
	function delete_ck2_stores_commodity_kinds_func(kinds_sn){
		var sure = window.confirm('"._BP_DEL_CHK."');
		if (!sure)	return;
		location.href=\"{$_SERVER['PHP_SELF']}?op=delete_ck2_stores_commodity_kinds&kinds_sn=\" + kinds_sn;
	}
	</script>
	<table id='tbl' style='width:100%;'>
	<tr>
	<th>"._MA_CK2CART_KIND_SORT."</th>
	<th>"._MA_CK2CART_KIND_TITLE."</th>
	<th>"._MA_CK2CART_COMMODITY_NUM."</th>
	<th>"._MA_CK2CART_KIND_COUNTER."</th>
	<th>"._MA_CK2CART_COMMODITY_ENABLE."</th>
	$function_title</tr>
	<tbody>";

	while($all=$xoopsDB->fetchArray($result)){
	  //以下會產生這些變數： $kinds_sn,$of_kinds_sn,$stores_sn,$kind_title,$kind_desc,$kind_sort,$kind_counter,$enable
    foreach($all as $k=>$v){
      $$k=$v;
    }
		$fun=($show_function)?"<td>
		<a href='{$_SERVER['PHP_SELF']}?op=ck2_stores_commodity_kinds_form&kinds_sn=$kinds_sn'>"._BP_EDIT."</a> |
		<a href=\"javascript:delete_ck2_stores_commodity_kinds_func($kinds_sn);\">"._BP_DEL."</a>	|
		<a href='{$_SERVER['PHP_SELF']}?op=add_commodity&kinds_sn=$kinds_sn'>"._MA_CK2CART_ADD_COMMODITY."</a></td>":"";
		
		$status=($enable=='1')?_MA_CK2CART_COMMODITY_IS_ENABLE:_MA_CK2CART_COMMODITY_IS_UNABLE;
		
		$kind_commodity_num=kind_commodity_num();
		
		$data.="<tr>
		<td>{$kind_sort}</td>
		<td>{$kind_title}</td>
		<td>{$kind_commodity_num[$kinds_sn]}</td>
		<td>{$kind_counter}</td>
		<td>{$status}</td>
		$fun</tr>";
	}

	$data.="
	</tbody>
	</table>";
	
	if($nodiv)return $data;

	//raised,corners,inset
	$main=div_3d("",$data,"corners");

	return $main;
}


//抓出商品類別中的商品數量
function kind_commodity_num(){
	global $xoopsDB;
	$sql = "select kinds_sn,count(*) from ".$xoopsDB->prefix("ck2_stores_commodity")." group by kinds_sn";
	$result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());
	while(list($kinds_sn,$count)=$xoopsDB->fetchRow($result)){
		$data_arr[$kinds_sn]=$count;
	}
	return $data_arr;
}


//以流水號取得某筆ck2_stores_commodity_kinds資料
function get_ck2_stores_commodity_kinds($kinds_sn=""){
	global $xoopsDB;
	if(empty($kinds_sn))return;
	$sql = "select * from ".$xoopsDB->prefix("ck2_stores_commodity_kinds")." where kinds_sn='$kinds_sn'";
	$result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());
	$data=$xoopsDB->fetchArray($result);
	return $data;
}

//更新ck2_stores_commodity_kinds某一筆資料
function update_ck2_stores_commodity_kinds($kinds_sn=""){
	global $xoopsDB;
	$sql = "update ".$xoopsDB->prefix("ck2_stores_commodity_kinds")." set  `of_kinds_sn` = '{$_POST['of_kinds_sn']}', `stores_sn` = '{$_POST['stores_sn']}', `kind_title` = '{$_POST['kind_title']}', `kind_desc` = '{$_POST['kind_desc']}', `kind_sort` = '{$_POST['kind_sort']}', `kind_counter` = '{$_POST['kind_counter']}', `enable` = '{$_POST['enable']}' where kinds_sn='$kinds_sn'";
	$xoopsDB->queryF($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());
	return $kinds_sn;
}

//刪除ck2_stores_commodity_kinds某筆資料資料
function delete_ck2_stores_commodity_kinds($kinds_sn=""){
	global $xoopsDB;
	$sql = "delete from ".$xoopsDB->prefix("ck2_stores_commodity_kinds")." where kinds_sn='$kinds_sn'";
	$xoopsDB->queryF($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());
}

//自動取得ck2_stores_commodity_kinds的最新排序
function ck2_stores_commodity_kinds_max_sort(){
	global $xoopsDB;
	$sql = "select max(`kind_sort`) from ".$xoopsDB->prefix("ck2_stores_commodity_kinds");
	$result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());
	list($sort)=$xoopsDB->fetchRow($result);
	return ++$sort;
}


/*=============商品函數=============*/

//ck2_stores_commodity編輯表單
function ck2_stores_commodity_form($commodity_sn="",$specification_sn=""){
	global $xoopsDB;
	include_once(XOOPS_ROOT_PATH."/class/xoopsformloader.php");
	//include_once(XOOPS_ROOT_PATH."/class/xoopseditor/xoopseditor.php");

	//抓取預設值
	if(!empty($commodity_sn)){
		$DBV=get_ck2_stores_commodity($commodity_sn);
	}else{
		$DBV=array();
	}

	//預設值設定

	$commodity_sn=(!isset($DBV['commodity_sn']))?"":$DBV['commodity_sn'];
	$kinds_sn=(!isset($DBV['kinds_sn']))?$_GET['kinds_sn']:$DBV['kinds_sn'];
	$brand_sn=(!isset($DBV['brand_sn']))?"":$DBV['brand_sn'];
	$com_title=(!isset($DBV['com_title']))?"":$DBV['com_title'];
	$com_summary=(!isset($DBV['com_summary']))?"":$DBV['com_summary'];
	$com_content=(!isset($DBV['com_content']))?"":$DBV['com_content'];
	$com_unit=(!isset($DBV['com_unit']))?"":$DBV['com_unit'];
	$com_post_date=(!isset($DBV['com_post_date']))?"":$DBV['com_post_date'];
	$com_counter=(!isset($DBV['com_counter']))?"":$DBV['com_counter'];
	$enable=(!isset($DBV['enable']))?"1":$DBV['enable'];
	$payment=(!isset($DBV['payment']))?"":$DBV['payment'];
	$shipping=(!isset($DBV['shipping']))?"":$DBV['shipping'];
	
	include(XOOPS_ROOT_PATH."/modules/ck2_cart/class/fckeditor/fckeditor.php") ;

	$oFCKeditor = new FCKeditor('com_content') ;
	$oFCKeditor->BasePath	= XOOPS_URL."/modules/ck2_cart/class/fckeditor/" ;
	$oFCKeditor->Config['AutoDetectLanguage']=false;
	$oFCKeditor->Config['DefaultLanguage']		= 'zh' ;
	$oFCKeditor->ToolbarSet ='my';
	$oFCKeditor->Width = '600' ;
	$oFCKeditor->Height = '250' ;
	$oFCKeditor->Value =$com_content;
	$com_content_editor=$oFCKeditor->CreateHtml() ;
	
	$op=(empty($commodity_sn))?"insert_ck2_stores_commodity":"update_ck2_stores_commodity";
	//$op="replace_ck2_stores_commodity";

	$main="
	<link type='text/css' rel='stylesheet' href='".XOOPS_URL."/modules/ck2_cart/class/formValidator/style/validator.css'>
	<script src='".XOOPS_URL."/modules/ck2_cart/class/formValidator/jquery_last.js' type='text/javascript'></script>
	<script src='".XOOPS_URL."/modules/ck2_cart/class/formValidator/formValidator.js' type='text/javascript' charset='UTF-8'></script>
	<script src='".XOOPS_URL."/modules/ck2_cart/class/formValidator/formValidatorRegex.js' type='text/javascript' charset='UTF-8'></script>
	<script src='".XOOPS_URL."/modules/ck2_cart/class/formValidator/DateTimeMask.js' language='javascript' type='text/javascript'></script>
	<script type='text/javascript'>
	$(document).ready(function(){
	var i=1;
	$('#add').click(function(){
		$('#spec').clone().attr('id', 'spec'+i).insertBefore('#add_tr');
		i++;
	});
	
	$.formValidator.initConfig({formid:'myForm',onerror:function(msg){alert(msg)}});



	//「商品名稱」欄位檢查
	$('#com_title').formValidator({
		onshow:'".sprintf(_MD_INPUT_VALIDATOR,_MA_CK2CART_COM_TITLE)."',
		onfocus:'".sprintf(_MD_INPUT_VALIDATOR_CHK,'1','255')."',
		oncorrect:'OK!'
	}).inputValidator({
		min:1,
		max:255,
		onerror:'".sprintf(_MD_INPUT_VALIDATOR_ERROR,_MA_CK2CART_COM_TITLE)."'
	});

	//「商品摘要」欄位檢查
	$('#com_summary').formValidator({
		onshow:'".sprintf(_MD_INPUT_VALIDATOR,_MA_CK2CART_COM_SUMMARY)."',
		onfocus:'".sprintf(_MD_INPUT_VALIDATOR_MIN,'1')."',
		oncorrect:'OK!'
	});

	//「商品描述」欄位檢查
	$('#com_content').formValidator({
		onshow:'".sprintf(_MD_INPUT_VALIDATOR,_MA_CK2CART_COM_CONTENT)."',
		onfocus:'".sprintf(_MD_INPUT_VALIDATOR_MIN,'1')."',
		oncorrect:'OK!'
	});



	//「價格單位」欄位檢查
	$('#com_unit').formValidator({
		onshow:'".sprintf(_MD_INPUT_VALIDATOR,_MA_CK2CART_COM_UNIT)."',
		onfocus:'".sprintf(_MD_INPUT_VALIDATOR_CHK,'1','255')."',
		oncorrect:'OK!'
	});
	


	//「特價結束日期」欄位檢查
	$('#specification_sprice_end_date').focus(function(){
		WdatePicker({
			skin:'whyGreen',
			oncleared:function(){\$(this).blur();},
			onpicked:function(){\$(this).blur();}
		})
	});
	});
	</script>
	<script defer='defer' src='".XOOPS_URL."/modules/ck2_cart/class/formValidator/datepicker/WdatePicker.js' type='text/javascript'></script>

	<form action='{$_SERVER['PHP_SELF']}' method='post' id='myForm' enctype='multipart/form-data'>
	<table class='form_tbl'>

	
	<tr><td class='title'>"._MA_CK2CART_COM_TITLE."</td>
	<td class='col' colspan=5><input type='text' name='com_title' size='50' value='{$com_title}' id='com_title'></td></tr>
	
	<tr><td class='title'>"._MA_CK2CART_COM_IMAGE."</td>
	<td class='col' colspan=5><input type='file' name='image' size='50'  id='image'></td></tr>

	<tr><td class='title'>"._MA_CK2CART_COM_SUMMARY."</td>
	<td class='col' colspan=5><textarea name='com_summary' cols='80' rows=4 id='com_summary' style='width:100%;'>{$com_summary}</textarea></td></tr>

	<tr><td class='title'>"._MA_CK2CART_COM_CONTENT."</td>
	<td class='col' colspan=5>$com_content_editor</td></tr>
	

	<tr><td class='title'>"._MA_CK2CART_COM_UNIT."</td>
	<td class='col'><input type='text' name='com_unit' size='2' value='{$com_unit}' id='com_unit'></td>

	<td class='title'>"._MA_CK2CART_BRAND_SN."</td>
	<td class='col'><select name='brand_sn' size=1>
		<option value='' ".chk($brand_sn,'','1','selected')."></option>
		".get_ck2_stores_brand_options($brand_sn)."
	</select></td>
	
	<td class='title'>"._MA_CK2CART_COM_ENABLE."</td>
	<td class='col'>
	<input type='radio' name='enable' id='enable' value='1' ".chk($enable,'1','1').">"._MA_CK2CART_COM_IS_ENABLE."
	<input type='radio' name='enable' id='enable' value='0' ".chk($enable,'0').">"._MA_CK2CART_COM_IS_UNABLE."</td></tr>";
	

	$payment_arr=explode(",",$payment);
	$main.="
	<!--付款方式--><tr><td class='title' nowrap>"._MA_CK2CART_PAYMENT."</td>
	<td class='col' colspan=5>".get_ck2_stores_payment_checkbox($payment_arr)."
	</td></tr>";

	$shipping_arr=explode(",",$shipping);
	$main.="
	<!--運送方式--><tr><td class='title' nowrap>"._MA_CK2CART_SHIPPING."</td>
	<td class='col' colspan=5>".get_ck2_stores_shipping_checkbox($shipping_arr)."
	</td></tr>";

	$list_ck2_stores_commodity_specification=list_ck2_stores_commodity_specification($commodity_sn,$specification_sn);

	$main.="
	
	
	<!--規格及數量-->
	<tr><td class='title' nowrap>"._MA_CK2CART_SPECIFICATION."</td>
	<td class='col' colspan=5>
	$list_ck2_stores_commodity_specification
	</td></tr>
	
	
	<tr><td class='bar' colspan='6'>
	<input type='hidden' name='commodity_sn' value='{$commodity_sn}'>
	<input type='hidden' name='kinds_sn' value='{$kinds_sn}'>
	<input type='hidden' name='op' value='{$op}'>
	<input type='submit' value='"._MA_SAVE."'></td></tr>
	</table>
	</form>";

	//raised,corners,inset
	$main=div_3d(_MA_CK2CART_COMMODITY_INPUT_FORM,$main,"raised");

	return $main;
}


//列出所有ck2_stores_commodity_specification資料
function list_ck2_stores_commodity_specification($commodity_sn="",$spec_sn=""){
	global $xoopsDB,$xoopsModule;
	$show_function=1;
	$MDIR=$xoopsModule->getVar('dirname');
	$sql = "select * from ".$xoopsDB->prefix("ck2_stores_commodity_specification")." where commodity_sn='$commodity_sn'";
	$result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());

	$function_title=($show_function)?"<th>"._BP_FUNCTION."</th>":"";

	//刪除確認的JS
	$data="
	<script>
	function delete_ck2_stores_commodity_specification_func(specification_sn){
		var sure = window.confirm('"._BP_DEL_CHK."');
		if (!sure)	return;
		location.href=\"{$_SERVER['PHP_SELF']}?op=delete_ck2_stores_commodity_specification&commodity_sn=$commodity_sn&specification_sn=\" + specification_sn;
	}
	</script>

	<table summary='list_table' id='tbl' style='width:100%;'>
	<tr>
	<th>"._MA_CK2CART_SPECIFICATION_TITLE."</th>
	<th>"._MA_CK2CART_SPECIFICATION_AMOUNT."</th>
	<th>"._MA_CK2CART_SPECIFICATION_PRICE."</th>
	<th>"._MA_CK2CART_SPECIFICATION_SPRICE."</th>
	<th>"._MA_CK2CART_SPECIFICATION_SPRICE_END_DATE."</th>
	<th>"._MA_CK2CART_SALE_LOG."</th>
	$function_title</tr>
	<tbody>";

	while($all=$xoopsDB->fetchArray($result)){
	  //以下會產生這些變數： $specification_sn , $commodity_sn , $specification_title , $specification_amount , $specification_price , $specification_sprice , $specification_sprice_end_date
    foreach($all as $k=>$v){
      $$k=$v;
    }
    
    $sale_log=get_ck2_stores_order_commodity($specification_sn);
    
    $del=(empty($sale_log['amount']))?"<a href=\"javascript:delete_ck2_stores_commodity_specification_func($specification_sn);\"><img src='".XOOPS_URL."/modules/{$MDIR}/images/del.gif' alt='"._BP_DEL."'></a>
		</td>":"";

		$fun=($show_function)?"
		<td>
		<a href='{$_SERVER['PHP_SELF']}?op=ck2_stores_commodity_specification_form&specification_sn=$specification_sn&commodity_sn=$commodity_sn'><img src='".XOOPS_URL."/modules/{$MDIR}/images/edit.gif' alt='"._BP_EDIT."'></a>
		$del":"";

		$data.=($spec_sn==$specification_sn)?"<tr id='spec'>
			<!--規格名稱-->
			<td class='col'><input type='text' name='update_specification_title[$specification_sn]' size='30' value='$specification_title' onClick=\"if(this.value=='')this.value=com_title.value\"></td>
			<!--數量-->
			<td class='col'><input type='text' name='update_specification_amount[$specification_sn]' size='2' value='$specification_amount'></td>
			<!--價錢-->
			<td class='col'><input type='text' name='update_specification_price[$specification_sn]' size='5' value='$specification_price'></td>
			<!--特價-->
			<td class='col'><input type='text' name='update_specification_sprice[$specification_sn]' size='5' value='$specification_sprice'></td>
			<!--特價結束日期-->
			<td class='col'><input type='text' name='update_specification_sprice_end_date[$specification_sn]' size='10' value='$specification_sprice_end_date' id='specification_sprice_end_date'></td>
			<td></td>
			</tr>":"<tr>
		<td>{$specification_title}</td>
		<td>{$specification_amount}</td>
		<td>{$specification_price}</td>
		<td>{$specification_sprice}</td>
		<td>{$specification_sprice_end_date}</td>
		<td>{$sale_log['amount']}</td>
		$fun
		</tr>";
	}

	$data.="
	<tr id='spec'>
			<!--規格名稱-->
			<td class='col'><input type='text' name='specification_title[]' size='30' value='' onClick=\"if(this.value=='')this.value=com_title.value\"></td>
			<!--數量-->
			<td class='col'><input type='text' name='specification_amount[]' size='2' value=''></td>
			<!--價錢-->
			<td class='col'><input type='text' name='specification_price[]' size='5' value=''></td>
			<!--特價-->
			<td class='col'><input type='text' name='specification_sprice[]' size='5' value=''></td>
			<!--特價結束日期-->
			<td class='col'><input type='text' name='specification_sprice_end_date[]' size='10' value='' id='specification_sprice_end_date'></td>
			<td></td>
			<td></td>
		</tr>
	<tr id='add_tr'>
	<td colspan=7 class='bar'><div id='add'>
	"._MA_CK2CART_SPECIFICATION_ADD."</div></td></tr>
	</tbody>
	</table>";


	return $data;
}

//取得某規格的售出數量紀錄
function get_ck2_stores_order_commodity($specification_sn=""){
	global $xoopsDB;
	if(empty($specification_sn))return;
	$sql = "select count(*),sum(`amount`) from ".$xoopsDB->prefix("ck2_stores_order_commodity")." where specification_sn='$specification_sn' group by specification_sn ";
	//die($sql);
	$result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());
	list($count,$amount)=$xoopsDB->fetchRow($result);
	$data['count']=$count;
	$data['amount']=$amount;
	return $data;
}




//新增資料到ck2_stores_commodity中
function insert_ck2_stores_commodity(){
	global $xoopsDB;
	$payment=implode(",",$_POST['payment']);
	$shipping=implode(",",$_POST['shipping']);
	
	$sql = "insert into ".$xoopsDB->prefix("ck2_stores_commodity")." (`kinds_sn`,`brand_sn`,`com_title`,`com_summary`,`com_content`,`com_unit`,`com_post_date`,`com_counter`,`enable` , `payment` , `shipping`) values('{$_POST['kinds_sn']}','{$_POST['brand_sn']}','{$_POST['com_title']}','{$_POST['com_summary']}','{$_POST['com_content']}','{$_POST['com_unit']}',now(),'{$_POST['com_counter']}','{$_POST['enable']}', '{$payment}' , '{$shipping}')";
	$xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());

	//取得最後新增資料的流水編號
	$commodity_sn=$xoopsDB->getInsertId();
	

	//規格及數量
  save_commodity_specification($commodity_sn);
	
	//上傳圖檔
  if(!empty($_FILES['image']['name'])){
  	upload_pic("commodity_sn",$commodity_sn,"1","insert");
 	}

	return $commodity_sn;
}



//更新ck2_stores_commodity某一筆資料
function update_ck2_stores_commodity($commodity_sn=""){
	global $xoopsDB;
	
	
	$payment=implode(",",$_POST['payment']);
	$shipping=implode(",",$_POST['shipping']);

	$sql = "update ".$xoopsDB->prefix("ck2_stores_commodity")." set  `kinds_sn` = '{$_POST['kinds_sn']}', `brand_sn` = '{$_POST['brand_sn']}', `com_title` = '{$_POST['com_title']}', `com_summary` = '{$_POST['com_summary']}', `com_content` = '{$_POST['com_content']}', `com_unit` = '{$_POST['com_unit']}', `com_post_date` = now(), `enable` = '{$_POST['enable']}',`payment` = '{$payment}',`shipping` = '{$shipping}' where commodity_sn='$commodity_sn'";
	$xoopsDB->queryF($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());

	//規格及數量
  save_commodity_specification($commodity_sn);
	
	
	//上傳圖檔
  if(!empty($_FILES['image']['name'])){
  	upload_pic("commodity_sn",$commodity_sn,"1","update");
 	}

	return $commodity_sn;
}


//新增資料到ck2_stores_commodity_specification中
function save_commodity_specification($commodity_sn=""){
	global $xoopsDB,$xoopsUser;

 	//新增部份
	foreach($_POST['specification_title'] as $n => $specification_title){
	  if(empty($specification_title))continue;
	
		$data[]="('{$commodity_sn}' , '{$specification_title}' , '{$_POST['specification_amount'][$n]}' , '{$_POST['specification_price'][$n]}' , '{$_POST['specification_sprice'][$n]}' , '{$_POST['specification_sprice_end_date'][$n]}')";
	}
	
	$data_str=implode(",",$data);
	
	if(!empty($data_str)){

		$sql = "insert into ".$xoopsDB->prefix("ck2_stores_commodity_specification")."
		(`commodity_sn` , `specification_title` , `specification_amount` , `specification_price` , `specification_sprice` , `specification_sprice_end_date`)
		values{$data_str}";

		$xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());
	}
	
	
	//修改部份
	foreach($_POST['update_specification_title'] as $specification_sn => $specification_title){
	  if(empty($specification_title))continue;
	  	$sql = "update ".$xoopsDB->prefix("ck2_stores_commodity_specification")." set `specification_title`='$specification_title',`specification_amount`='{$_POST['update_specification_amount'][$specification_sn]}',`specification_price`='{$_POST['update_specification_price'][$specification_sn]}' , `specification_sprice`='{$_POST['update_specification_sprice'][$specification_sn]}' , `specification_sprice_end_date`='{$_POST['update_specification_sprice_end_date'][$specification_sn]}' where specification_sn='$specification_sn'";

		$xoopsDB->queryF($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());
	}
}


//列出所有ck2_stores_commodity資料
function list_ck2_stores_commodity($show_function=1){
	global $xoopsDB,$xoopsModule;
	$MDIR=$xoopsModule->getVar('dirname');

	$stores_sn=get_stores_sn();
	$commodity_kinds_all=get_ck2_stores_commodity_kinds_all($stores_sn);
	//$commodity_brand_all=get_ck2_stores_brand_all($stores_sn);
	$kinds_sn_arr=implode(",",array_keys($commodity_kinds_all));
	
	if(empty($kinds_sn_arr))return;
	$sql = "select a.*,b.filename from ".$xoopsDB->prefix("ck2_stores_commodity")." as a left join ".$xoopsDB->prefix("ck2_stores_image_center")." as b on b.col_name='commodity_sn' and b.col_sn=a.commodity_sn where a.kinds_sn in($kinds_sn_arr) order by a.com_post_date desc";

	//PageBar(資料數, 每頁顯示幾筆資料, 最多顯示幾個頁數選項);
	$result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, $sql);
	$total=$xoopsDB->getRowsNum($result);

	$navbar = new PageBar($total, 10, 10);
	$mybar = $navbar->makeBar();
	$bar= sprintf(_BP_TOOLBAR,$mybar['total'],$mybar['current'])."{$mybar['left']}{$mybar['center']}{$mybar['right']}";
	$sql.=$mybar['sql'];

	$result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());

	$function_title=($show_function)?"<th>"._BP_FUNCTION."</th>":"";

	//刪除確認的JS
	$data="
	<script>
	function delete_ck2_stores_commodity_func(commodity_sn){
		var sure = window.confirm('"._BP_DEL_CHK."');
		if (!sure)	return;
		location.href=\"{$_SERVER['PHP_SELF']}?op=delete_ck2_stores_commodity&commodity_sn=\" + commodity_sn;
	}
	</script>
	<table id='tbl'>
	<tr>
	<th>"._MA_CK2CART_COMMODITY_SN."</th>
	<th>"._MA_CK2CART_COM_TITLE."</th>
	<th>"._MA_CK2CART_COM_COUNTER."</th>
	<th>"._MA_CK2CART_COM_ENABLE."</th>
	$function_title</tr>
	<tbody>";

	while($all=$xoopsDB->fetchArray($result)){
	  //以下會產生這些變數： $commodity_sn,$kinds_sn,$brand_sn,$com_title,$com_summary,$com_content,$com_price,$com_sprice,$com_sprice_end_date,$com_unit,$com_post_date,$com_counter,$enable
    foreach($all as $k=>$v){
      $$k=$v;
    }
    
		$fun=($show_function)?"<td>
		<a href='{$_SERVER['PHP_SELF']}?op=ck2_stores_commodity_form&commodity_sn=$commodity_sn'>"._BP_EDIT."</a><br>
		<a href=\"javascript:delete_ck2_stores_commodity_func($commodity_sn);\">"._BP_DEL."</a></td>":"";
		
		$data.="<tr>
		<td><a href='".XOOPS_URL."/modules/ck2_cart/commodity.php?commodity_sn=$commodity_sn'><div style='width:80px;height:50px;overflow:hidden;background-image:url("._CK2CART_UPLOAD_URL."/{$stores_sn}/commodity/thumb/{$commodity_sn}_{$filename});background-position: center;border:1px solid #9999CC;'></div></a></td>
		<td>
		<div>[{$commodity_kinds_all[$kinds_sn]['kind_title']}]</div>
		<a href='".XOOPS_URL."/modules/ck2_cart/commodity.php?commodity_sn=$commodity_sn' style='font-size:11pt;'>{$com_title}</a><div>"._MA_CK2CART_COM_POST_DATE._BP_FOR."{$com_post_date}</div></td>
		<td>{$com_counter}</td>
		<td>{$enable}</td>
		$fun</tr>";
	}

	$data.="
	<tr>
	<td colspan=14 class='bar'>{$bar}</td></tr>
	</tbody>
	</table>";

	//raised,corners,inset
	$main=div_3d("",$data,"corners");

	return $main;
}




//刪除ck2_stores_commodity某筆資料資料
function delete_ck2_stores_commodity($commodity_sn=""){
	global $xoopsDB;
	$sql = "delete from ".$xoopsDB->prefix("ck2_stores_commodity")." where commodity_sn='$commodity_sn'";
	$xoopsDB->queryF($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());
}



//取得所有ck2_stores_brand分類選單的選項
function get_ck2_stores_brand_options($default_brand_sn=""){
	global $xoopsDB,$xoopsModule;
	$stores_sn=get_stores_sn();
	
	$sql = "select brand_sn,brand_name from ".$xoopsDB->prefix("ck2_stores_brand")." where enable='1' and stores_sn='$stores_sn'";
	$result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());


	$main="";
	while(list($brand_sn,$brand_name)=$xoopsDB->fetchRow($result)){
	  $selected=($brand_sn==$default_brand_sn)?"selected":"";
		$main.="<option value=$brand_sn $selected>{$brand_name}</option>";

	}
	return $main;
}

//取得運送方式核選框
function get_ck2_stores_shipping_checkbox($shipping_arr=array()){
	global $xoopsDB;
	$stores_sn=get_stores_sn();
	$sql = "select shipping_sn,shipping_name from ".$xoopsDB->prefix("ck2_stores_shipping")." where stores_sn='$stores_sn'";
	$result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());
	while($data=$xoopsDB->fetchArray($result)){
		$data_arr.="<input type='checkbox' name='shipping[]' value='{$data['shipping_sn']}' ".chk2($shipping_arr,$data['shipping_sn'],'1').">{$data['shipping_name']}";
	}
	return $data_arr;
}

//取得付款方式核選框
function get_ck2_stores_payment_checkbox($payment_arr=array()){
	global $xoopsDB;
	$stores_sn=get_stores_sn();
	$sql = "select payment_sn,payment_name from ".$xoopsDB->prefix("ck2_stores_payment")." where stores_sn='$stores_sn'";
	$result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());
	while($data=$xoopsDB->fetchArray($result)){
		$data_arr.="<input type='checkbox' name='payment[]' value='{$data['payment_sn']}' ".chk2($payment_arr,$data['payment_sn'],'1').">{$data['payment_name']}";
	}
	return $data_arr;
}

//刪除ck2_stores_commodity_specification某筆資料資料
function delete_ck2_stores_commodity_specification($specification_sn=""){
	global $xoopsDB;
	$sql = "delete from ".$xoopsDB->prefix("ck2_stores_commodity_specification")." where specification_sn='$specification_sn'";
	$xoopsDB->queryF($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());
}

/*-----------執行動作判斷區----------*/
$op = (!isset($_REQUEST['op']))? "main":$_REQUEST['op'];

switch($op){
	//更新分類資料
	case "update_ck2_stores_commodity_kinds":
	update_ck2_stores_commodity_kinds($_POST['kinds_sn']);
	header("location: {$_SERVER['PHP_SELF']}");
	break;

	//新增分類資料
	case "insert_ck2_stores_commodity_kinds":
	insert_ck2_stores_commodity_kinds();
	header("location: {$_SERVER['PHP_SELF']}");
	break;
	
	//輸入分類表格
	case "ck2_stores_commodity_kinds_form":
	$main=ck2_stores_commodity_kinds_form($_GET['kinds_sn']);
	break;

	//刪除分類資料
	case "delete_ck2_stores_commodity_kinds":
	delete_ck2_stores_commodity_kinds($_GET['kinds_sn']);
	header("location: {$_SERVER['PHP_SELF']}");
	break;
	


	//新增商品資料
	case "insert_ck2_stores_commodity":
	$commodity_sn=insert_ck2_stores_commodity();
	header("location: ".XOOPS_URL."/modules/ck2_cart/commodity.php?commodity_sn=$commodity_sn");
	break;


	//輸入商品表格
	case "add_commodity":
	case "ck2_stores_commodity_form":
	$main=ck2_stores_commodity_form($_GET['commodity_sn']);
	break;

	//刪除商品資料
	case "delete_ck2_stores_commodity":
	delete_ck2_stores_commodity($_GET['commodity_sn']);
	header("location: {$_SERVER['PHP_SELF']}");
	break;

	//刪除商品資料
	case "delete_ck2_stores_commodity_specification":
	delete_ck2_stores_commodity_specification($_GET['specification_sn']);
	header("location: {$_SERVER['PHP_SELF']}?op=ck2_stores_commodity_form&commodity_sn={$_GET['commodity_sn']}");
	break;

	//更新商品資料
	case "update_ck2_stores_commodity":
	update_ck2_stores_commodity($_POST['commodity_sn']);
	header("location: ".XOOPS_URL."/modules/ck2_cart/commodity.php?commodity_sn={$_POST['commodity_sn']}");
	break;
	
	//修改規格
	case "ck2_stores_commodity_specification_form":
	$main=ck2_stores_commodity_form($_GET['commodity_sn'],$_GET['specification_sn']);
	break;
	
	//預設動作
	default:
	$main=ck2_stores_commodity_kinds_form($_GET['kinds_sn']);
	$main.=list_ck2_stores_commodity();
	break;

}

/*-----------秀出結果區--------------*/
xoops_cp_header();
echo "<link rel='stylesheet' type='text/css' media='screen' href='../module.css' />";
echo menu_interface();
echo $main;
xoops_cp_footer();

?>
