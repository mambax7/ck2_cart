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
//列出所有ck2_stores_order資料
function list_ck2_stores_order(){
	global $xoopsDB,$xoopsModule;
	$MDIR=$xoopsModule->getVar('dirname');
	$sql = "select a.*,b.customer_name from ".$xoopsDB->prefix("ck2_stores_order")." as a left join ".$xoopsDB->prefix("ck2_stores_customer")." as b on a.uid=b.uid order by a.order_date desc";

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
	<script type='text/javascript' src='../class/jquery-1.3.2.min.js'></script>
	<script type='text/javascript' src='../class/jquery.tools.min.js'></script>

	<script type='text/javascript'>
	  $(function() {
	    $('img.note[title]').tooltip('#demotip');
	  });
	</script>
	<style>
	#demotip {
	  display:none;
	  border:1px solid black;
	  font-size:12px;
	  padding:10px;
	  color:#FFFFFF;
	  background-color: #CC3300;
	}
	</style>
  <div id='demotip'>&nbsp;</div>
	<table summary='list_table' style='width:100%;'>
	<tr>
	<th>"._MA_CK2CART_ORDER_SN."</th>
	<th>"._MA_CK2CART_SPECIFICATION."</th>
	<th>"._MA_CK2CART_TOTAL."</th>
	<th>"._MA_CK2CART_ORDER_NOTE."</th>
	<th>"._MA_CK2CART_BANK_SN."</th>
	<th>"._MA_CK2CART_ORDER_STATUS."</th>
	</tr>
	<tbody>";

	while($all=$xoopsDB->fetchArray($result)){
	  //以下會產生這些變數： $order_sn , $uid , $order_date , $customer_addr_sn , $shipping_sn , $order_note , $sum, $payment_sn , $bank_name , $bank_num , $pay_money , $order_pay_date , $order_status
    foreach($all as $k=>$v){
      $$k=$v;
    }
    
		//$order_status_option.=mc2arr("status",$order_status,"option");

		$sn=mk_order_sn($order_sn,$order_date);

		
		$order_note=nl2br($order_note);
		$note=(empty($order_note))?"":"<img src='../images/001_12.png' title='{$order_note}' class='note'>";
		
		$i++;
		$color=($i%2)?"#FEFEFE":"#F7F7F7";
		
		$spec=list_ck2_stores_order_commodity($order_sn);
		
		$order_status=(empty($order_status))?_MA_CK2CART_ORDER_STATUS_WAIT:$order_status;
		
		$pay_info=(!empty($bank_name) or !empty($bank_num))?"{$bank_name}<br>{$bank_num}<br>{$order_pay_date}":"";

		$data.="<tr style='background-color:$color'>
		<td valign='top'>[{$sn}] {$customer_name}<br>{$order_date}</td>
		<td>{$spec['all']}</td>
		<td align='right' style='font-size:11pt;'>{$spec['money']}</td>
		<td align='center'>{$note}</td>
		<td>{$pay_info}</td>
		<td><a href='index.php?op=process&order_sn=$order_sn'>$order_status</a></td>
		</tr>";
	}

	$data.="
	<tr>
	<td colspan=11 class='bar'>
	{$bar}</td></tr>
	</tbody>
	</table>";

	//raised,corners,inset
	$main=div_3d("",$data,"corners","");

	return $main;
}




//列出所有ck2_stores_order_commodity資料
function list_ck2_order($order_sn=""){
	global $xoopsDB,$xoopsModule,$bank_arr;
	
	$old_sn=$order_sn;

	$sql = "select a.*,b.* from ".$xoopsDB->prefix("ck2_stores_order")." as a left join ".$xoopsDB->prefix("ck2_stores_customer")." as b on a.uid=b.uid where order_sn='$order_sn'";
	$result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());
	$all=$xoopsDB->fetchArray($result);
  //以下會產生這些變數： $order_sn , $uid , $order_date , $customer_addr_sn , $shipping_sn , $order_note ,$sum, $payment_sn , $bank_sn , $order_pay_date , $order_status,$uid,$customer_name,$customer_email,$customer_tel,$customer_zip,$customer_city,$customer_town,$customer_addr
  foreach($all as $k=>$v){
    $$k=$v;
  }

	$money=0;

	$sn=mk_order_sn($order_sn,$order_date);

	$MDIR=$xoopsModule->getVar('dirname');
	$sql = "select a.*,b.specification_title,b.commodity_sn from ".$xoopsDB->prefix("ck2_stores_order_commodity")." as a left join ".$xoopsDB->prefix("ck2_stores_commodity_specification")." as b on a.specification_sn=b.specification_sn where a.order_sn='$order_sn'";
	$result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());

	$data="
	<table id='order' summary='list_table' style='width:100%;'>
	<tr><th>"._MA_CK2CART_CUSTOMER_NAME."</th><td colspan=3>$customer_name</td></tr>
	<tr><th>"._MD_CK2CART_ORDER_DATE."</th><td colspan=3>$order_date</td></tr>
	<tr><th>"._MA_CK2CART_CUSTOMER_EMAIL."</th><td colspan=3>$customer_email</td></tr>
	<tr><th>"._MA_CK2CART_CUSTOMER_TEL."</th><td colspan=3>$customer_tel</td></tr>
	<tr><th>"._MA_CK2CART_CUSTOMER_ADDR."</th><td colspan=3>{$customer_zip} {$customer_city}{$customer_town}{$customer_addr}</td></tr>
	</table>
	<br>
	<table id='order' summary='list_table' style='width:100%;'>
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
		<td style='text-align:right;'>{$sum}</td>
		$fun
		</tr>";

    $money+=$sum;
	}

  $shipping=get_ck2_stores_shipping($shipping_sn);
  $money+=$shipping['shipping_pay'];

	$data.="
	<tr><th>"._MD_CK2CART_SHIPPING."</th><td>{$shipping['shipping_name']}</td><th>"._MD_CK2CART_SHIPPING_PAY."</th><td align='right'>{$shipping['shipping_pay']}</td></tr>
	<tr><th>"._MA_CK2CART_CUSTOMER_ORDER_NOTE."</th><td>$order_note</td><th>"._MD_CK2CART_TOTAL."</th><td>$money</td></tr>
	</table>";

	$order_pay_date_time=strtotime($order_pay_date);


	  if(_CHARSET=="Big5"){
			include "language/tchinese/bank_name.php";
		}else{
			include "language/tchinese_utf8/bank_name.php";
		}

	  $opt="";
	  foreach($bank_arr as $bank_name){
			$opt.="<option value='$bank_name'>$bank_name</option>";
		}

    $order=get_ck2_stores_order($old_sn);

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

		</script>";

		if(!empty($order['pay_money'])){
			$data.="
			<h3>"._MD_CK2CART_PAY_NOTIFY."</h3>
			<table>
			<tr><td>"._MD_CK2CART_BANK_NAME."</td><td>{$order['bank_name']}</td></tr>
			<tr><td>"._MD_CK2CART_BANK_NUM."</td><td>{$order['bank_num']}</td></tr>
			<tr><td>"._MD_CK2CART_BANK_PAY_TIME."</td><td>{$order['order_pay_date']}</td></tr>
			<tr><td>"._MD_CK2CART_BANK_PAY_MONEY."</td><td>{$order['pay_money']}</td></tr>
			</table>
			";
		}else{
      $data.="<h3>"._MD_CK2CART_PAY_NOT_YET."</h3>";
		}

		$order_status_option.=mc2arr("status",$order['order_status'],"option");
		$data.="<form action='{$_SERVER['PHP_SELF']}' method='post'>
			"._MD_CK2CART_STATUS."<select name='order_status'>
			<option>"._MA_CK2CART_ORDER_STATUS_WAIT."</option>
			$order_status_option
			<option value='"._MA_CK2CART_ORDER_STATUS_OK."'>"._MA_CK2CART_ORDER_STATUS_OK."</option>
			</select>
			<input type='hidden' name='order_sn' value='$order_sn'>
			<input type='hidden' name='op' value='change_order_status'>
			<input type='submit' value='"._MD_CK2CART_STATUS_SUBMIT."' class='submit'>
			</form>";
		
		
	//raised,corners,inset
	$main=div_3d(sprintf(_MD_CK2CART_MY_ORDER,$sn),$data,"corners");
	return $main;
}


//以流水號取得某筆ck2_stores_order資料
function get_ck2_stores_order($order_sn=""){
	global $xoopsDB;
	if(empty($order_sn))return;
	$order_sn=intval($order_sn);
	$sql = "select * from ".$xoopsDB->prefix("ck2_stores_order")." where order_sn='$order_sn'";
	$result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());
	$data=$xoopsDB->fetchArray($result);
	return $data;
}


//改變訂單狀態
function change_order_status($order_sn,$order_status){
	global $xoopsDB;
	$order_sn=intval($order_sn);
	$sql = "update ".$xoopsDB->prefix("ck2_stores_order")." set order_status='{$order_status}' where order_sn='$order_sn'";
	$xoopsDB->queryF($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());
	
	if($order_status==_MA_CK2CART_ORDER_STATUS_OK){
	  $order=get_ck2_stores_order($order_sn);
	  $customer=get_ck2_stores_customer($order['uid']);
		$store=get_ck2_stores($order['stores_sn']);
		$ck2_stores_order=list_ck2_stores_order($order_sn);
		$sn=mk_order_sn($order_sn,$order['order_date']);
		$title=sprintf(_MA_CK2CART_ORDER_MAIL_TITLE3,$store['store_title'],$sn);
		$content=sprintf(_MA_CK2CART_ORDER_MAIL_CONTENT3,$store['store_title'],$order['customer_name'],$customer['customer_zip'].$customer['customer_city'].$customer['customer_town'].$customer['customer_addr'],$store['store_title']);
		send_now($customer['customer_email'],$title,$content);
	}
}



/*-----------執行動作判斷區----------*/
$op = (!isset($_REQUEST['op']))? "main":$_REQUEST['op'];

switch($op){
	case "process":
	$main=list_ck2_order($_GET['order_sn']);
	//header("location: {$_SERVER['PHP_SELF']}");
	break;
	
	case "change_order_status":
	change_order_status($_POST['order_sn'],$_POST['order_status']);
	header("location: {$_SERVER['PHP_SELF']}");
	break;

	default:
	$main=list_ck2_stores_order();
	break;
}

/*-----------秀出結果區--------------*/
xoops_cp_header();
echo "<link rel='stylesheet' type='text/css' media='screen' href='../module.css' />";
echo menu_interface();
echo $main;
xoops_cp_footer();

?>
