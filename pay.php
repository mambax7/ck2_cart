<?php
//  ------------------------------------------------------------------------ //
// 本模組由 tad 製作
// 製作日期：2008-12-02
// $Id:$
// ------------------------------------------------------------------------- //

/*-----------引入檔案區--------------*/
include "header.php";
$xoopsOption['template_main'] = "pay_tpl.html";
if(empty($_REQUEST['order_sn']) or !$xoopsUser){
	header("location: index.php");
}

/*-----------function區--------------*/


//列出所有ck2_stores_order_commodity資料
function list_ck2_stores_order($order_sn=""){
	global $xoopsDB,$xoopsUser,$xoopsModule,$bank_arr;
	
	$uid=$xoopsUser->getVar('uid');

	$sql = "select a.*,b.* from ".$xoopsDB->prefix("ck2_stores_order")." as a left join ".$xoopsDB->prefix("ck2_stores_customer")." as b on a.uid=b.uid where a.order_sn='$order_sn' and a.uid='$uid'";
	$result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());
	$all=$xoopsDB->fetchArray($result);
  //以下會產生這些變數： $order_sn , $uid , $order_date , $customer_addr_sn , $shipping_sn , $order_note ,$sum, $payment_sn , $bank_sn , $order_pay_date , $order_status,$uid,$customer_name,$customer_email,$customer_tel,$customer_zip,$customer_city,$customer_town,$customer_addr
  foreach($all as $k=>$v){
    $$k=$v;
  }

	$money=0;
	

	$MDIR=$xoopsModule->getVar('dirname');
	$sql = "select a.*,b.specification_title,b.commodity_sn from ".$xoopsDB->prefix("ck2_stores_order_commodity")." as a left join ".$xoopsDB->prefix("ck2_stores_commodity_specification")." as b on a.specification_sn=b.specification_sn where a.order_sn='$order_sn'";
	$result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());

  $order_status=(empty($order_status))?_MD_CK2CART_ORDER_STATUS_WAIT:$order_status;

	$data="
	<h3>"._MD_CK2CART_ORDER_STATUS."$order_status</h3>
	<table id='tbl' summary='list_table' style='width:100%;border:1px solid gray;'>
	<tr><th>"._MA_CK2CART_CUSTOMER_NAME."</th><td colspan=3>$customer_name</td></tr>
	<tr><th>"._MD_CK2CART_ORDER_DATE."</th><td colspan=3>$order_date</td></tr>
	<tr><th>"._MA_CK2CART_CUSTOMER_EMAIL."</th><td colspan=3>$customer_email</td></tr>
	<tr><th>"._MA_CK2CART_CUSTOMER_TEL."</th><td colspan=3>$customer_tel</td></tr>
	<tr><th>"._MA_CK2CART_CUSTOMER_ADDR."</th><td colspan=3>{$customer_zip} {$customer_city}{$customer_town}{$customer_addr}</td></tr>
	</table>
	<br>
	<table id='tbl' summary='list_table2' style='width:100%;border:1px solid gray;'>
	<tr><th>"._MD_CK2CART_ORDER_SPEC_SN."</th><th>"._MD_CK2CART_COM_NAME."</th><th>"._MD_CK2CART_AMOUNT."</th><th>"._MD_CK2CART_SUM."</th></tr>
	";
	while($all=$xoopsDB->fetchArray($result)){
	  //以下會產生這些變數： $order_sn , $specification_sn , $amount , $sum
    foreach($all as $k=>$v){
      $$k=$v;
    }

		$data.="<tr>
		<td>$specification_sn</td>
		<td><a href='commodity.php?commodity_sn=$commodity_sn'>{$specification_title}</a></td>
		<td>{$amount}</td>
		<td style='text-align:right;'>{$sum} "._MD_CK2CART_MONEY."</td>
		$fun
		</tr>";

    $money+=$sum;
	}

  $shipping=get_ck2_stores_shipping($shipping_sn);
  $money+=$shipping['shipping_pay'];

	$data.="
	<tr><th>"._MD_CK2CART_SHIPPING."</th><td>{$shipping['shipping_name']}</td><th>"._MD_CK2CART_SHIPPING_PAY."</th><td align='right'>{$shipping['shipping_pay']} "._MD_CK2CART_MONEY."</td></tr>
	<tr><th>"._MA_CK2CART_CUSTOMER_ORDER_NOTE."</th><td>$order_note</td><th>"._MD_CK2CART_TOTAL."</th><td align='right'>$money "._MD_CK2CART_MONEY."</td></tr>
	</table>";


	//已付款通知
	if(empty($pay_money)){
	
	  if(_CHARSET=="Big5"){
			include "language/tchinese/bank_name.php";
		}else{
			include "language/tchinese_utf8/bank_name.php";
		}
	
	  $opt="";
	  foreach($bank_arr as $bank_name){
			$opt.="<option value='$bank_name'>$bank_name</option>";
		}
	  
	  
		$data.="
		<script type='text/javascript' src='http://www.google.com/jsapi'></script>
		<script type='text/javascript' language='javascript'> google.load('jquery', '1.3.2'); </script>
	  <script src='".XOOPS_URL."/modules/ck2_cart/class/jquery_ui_datepicker/jquery_ui_datepicker.js' type='text/javascript'></script>
	  <script src='".XOOPS_URL."/modules/ck2_cart/class/jquery_ui_datepicker/i18n/ui.datepicker-zh-TW.js' type='text/javascript'></script>
		<script src='".XOOPS_URL."/modules/ck2_cart/class/jquery_ui_datepicker/timepicker_plug/timepicker.js' type='text/javascript'></script>
		<link rel='stylesheet' type='text/css' href='".XOOPS_URL."/modules/ck2_cart/class/jquery_ui_datepicker/timepicker_plug/css/style.css'>
		<link rel='stylesheet' type='text/css' href='".XOOPS_URL."/modules/ck2_cart/class/jquery_ui_datepicker/smothness/jquery_ui_datepicker.css'>
	  <script type='text/javascript'>

		$(document).ready(function(){
				$('#order_pay_date').datetime({	userLang	: 'zh-TW',	americanMode: false});

			});

		</script>
		<form action='{$_SERVER['PHP_SELF']}' method='post'>
		<h3>"._MD_CK2CART_PAY_NOTIFY."</h3>
		<table>
		<tr><td>"._MD_CK2CART_BANK_NAME."</td><td><select name='bank_name'>$opt</select></td></tr>
		<tr><td>"._MD_CK2CART_BANK_NUM."</td><td><input type='text' name='bank_num' value=''></td></tr>
		<tr><td>"._MD_CK2CART_BANK_PAY_TIME."</td><td><input type='text' name='order_pay_date' value='".date("Y-m-d H:i:s")."' id='order_pay_date'></td></tr>
		<tr><td>"._MD_CK2CART_BANK_PAY_MONEY."</td><td><input type='text' name='pay_money' value='$money'></td></tr>
		</table>
		<input type='hidden' name='stores_sn' value='$stores_sn'>
		<input type='hidden' name='order_date' value='$order_date'>
		<input type='hidden' name='order_sn' value='$order_sn'>
		<input type='hidden' name='op' value='pay_notify'>
		<div align='center'><input type='submit' value='"._MD_CK2CART_PAY_NOTIFY_SUBMIT."' class='submit'></div>
		</form>";
	}else{
    $data.=sprintf(_MD_CK2CART_ORDER_PAY_OK,$order_pay_date);
	}

	$sn=mk_order_sn($order_sn,$order_date);
	
	//raised,corners,inset
	$main=div_3d(sprintf(_MD_CK2CART_MY_ORDER,$sn),$data,"corners","width:100%");
	return $main;
}



//儲存付款資訊
function update_ck2_stores_order($order_sn=""){
	global $xoopsDB,$xoopsUser;

	$sql = "update ".$xoopsDB->prefix("ck2_stores_order")." set
	 `bank_name` = '{$_POST['bank_name']}' ,
	 `bank_num` = '{$_POST['bank_num']}' ,
	 `pay_money` = '{$_POST['pay_money']}' ,
	 `order_pay_date` = '{$_POST['order_pay_date']}'
	where order_sn='$order_sn'";
	$xoopsDB->queryF($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());

	$store=get_ck2_stores($_POST['stores_sn']);
	$ck2_stores_order=list_ck2_stores_order($order_sn);
	$sn=mk_order_sn($order_sn,$_POST['order_date']);
	$title=sprintf(_MA_CK2CART_ORDER_MAIL_TITLE2,$store['store_title'],$sn);
	$content=sprintf(_MA_CK2CART_ORDER_MAIL_CONTENT2,$store['store_title'],$_POST['customer_name'],$order_sn,$order_sn,$store['store_title']);
	send_now($_POST['customer_email'],$title,$content);
	send_now($store['store_email'],$title,$content);

	
	return $order_sn;
}

/*-----------執行動作判斷區----------*/
$op=(empty($_REQUEST['op']))?"":$_REQUEST['op'];
$order_sn=(empty($_REQUEST['order_sn']))?"":intval($_REQUEST['order_sn']);
switch($op){
	case "pay_notify":
	update_ck2_stores_order($order_sn);
	redirect_header("index.php",3, _MD_CK2CART_BANK_PAY_OK);
	break;

	default:
	$main=list_ck2_stores_order($order_sn);
	break;
}

/*-----------秀出結果區--------------*/
include XOOPS_ROOT_PATH."/header.php";
$xoopsTpl->assign( "css" , "<link rel='stylesheet' type='text/css' media='screen' href='".XOOPS_URL."/modules/ck2_cart/module.css' />") ;
$xoopsTpl->assign( "toolbar" , toolbar($interface_menu)) ;
$xoopsTpl->assign( "content" , $main) ;
include_once XOOPS_ROOT_PATH.'/footer.php';

?>
