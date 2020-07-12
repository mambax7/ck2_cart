<?php
//  ------------------------------------------------------------------------ //
// 本模組由 tad 製作
// 製作日期：2008-12-02
// $Id:$
// ------------------------------------------------------------------------- //

//區塊主函式 (我的購物車)
function ck2_my_cart(){
	global $xoopsUser,$xoopsDB;
	
  $block_content="";
	foreach($_COOKIE['specification_sn'] as $specification_sn => $amount){
	  if(empty($amount))continue;
	  $commodity=get_ck2_com_from_spec($specification_sn);
    $block_content.="<tr><td><a href='".XOOPS_URL."/modules/ck2_cart/commodity.php?commodity_sn=$commodity_sn' style='font-size:11px;font-weight:normal;'>{$commodity['specification_title']}</a></td>
		<td><select name='amount[$specification_sn]'>".amount2option($commodity['specification_amount'],$amount)."</select></td></tr>";
	}
	
	$block="
	<script type='text/javascript' src='http://www.google.com/jsapi'></script>
	<script type='text/javascript' language='javascript'> google.load('jquery', '1.3.2'); </script>
	<script type='text/javascript' src='".XOOPS_URL."/modules/ck2_cart/class/jquery.tools.min.js'></script>
	<script type='text/javascript'>
	  $(function() {
	    $('img.pay[title]').tooltip({tip:'#paytip',position: 'bottom left'});
	  });
	</script>
	<style>
	#paytip {
	  width:160px;
	  display:none;
	  border:1px solid black;
	  font-size:12px;
	  padding:10px;
	  color:#FFFFFF;
	  background-color: #CC3300;
	}
	</style>";
	
	if(empty($block_content)){
    $block.="<div style='font-size:11pt;text-align:center;margin:6px 2px;background-color:#505050;border:1px solid gray;color:white;padding:4px;'>"._MB_CK2CART_CART_EMPTY."</div>";
	}else{
		$block.="
		<form action='".XOOPS_URL."/modules/ck2_cart/order.php' method='post'>
		<table style='background-color:#F7F7F7;border:1px solid gray;padding:4px;'>
		<tr><td colspan=2><div style='font-size:11pt;text-align:center;margin:6px 2px;background-color:#505050;border:1px solid gray;color:white;padding:4px;'>"._MB_CK2CART_ORDER_LIST."</div></td></tr>
		$block_content
		</table>
		<p align='center'><input type='submit' value='"._MB_CK2CART_CART_SUBMIT."'></p>
		</form>";
	}
	
	if($xoopsUser){
		$uid=$xoopsUser->getVar("uid");

		$sql = "select * from ".$xoopsDB->prefix("ck2_stores_order")." where uid='$uid' order by order_date desc";

		$result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());
    $block.="<table>";
		while($all=$xoopsDB->fetchArray($result)){
		  //以下會產生這些變數： $order_sn , $uid , $order_date , $customer_addr_sn , $shipping_sn , $order_note ,$sum , $payment_sn , $bank_name , $bank_num , $pay_money , $order_pay_date , $order_status
	    foreach($all as $k=>$v){
	      $$k=$v;
	    }
	    
	    //$order_status=(empty($order_status))?_MB_CK2CART_ORDER_STATUS_WAIT:$order_status;
	    $sn=mk_order_sn($order_sn,$order_date);
	    $pic=(empty($pay_money))?"<a href='".XOOPS_URL."/modules/ck2_cart/pay.php?order_sn=$order_sn'><img src='".XOOPS_URL."/modules/ck2_cart/images/file_edit.png' alt='".sprintf(_MB_CK2CART_ORDER_PAY,$specification_title)."' title='".sprintf(_MB_CK2CART_ORDER_PAY,$sn)."' class='pay'></a>":"";
      $block.=(empty($pay_money))?"<tr>
			<td><a href='".XOOPS_URL."/modules/ck2_cart/pay.php?order_sn=$order_sn' style='font-size:11px;font-weight:normal;'>".sprintf(_MB_CK2CART_MY_ORDER,$sn,$order_date)."</a></td>
			<td nowrap>$pic</td>
			</tr>":"";
    }
    $block.="</table>
	<div id='paytip'>&nbsp;</div>";
	}
	return $block;
}


//以流水號取得某筆ck2_stores_commodity資料
if(!function_exists('get_ck2_com_from_spec')){
	function get_ck2_com_from_spec($specification_sn=""){
		global $xoopsDB;
		if(empty($specification_sn))return;
		$sql = "select a.*,b.commodity_sn,b.com_title from ".$xoopsDB->prefix("ck2_stores_commodity_specification")." as a left join ".$xoopsDB->prefix("ck2_stores_commodity")." as b on a.commodity_sn=b.commodity_sn where a.specification_sn='$specification_sn'";
		$result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());
		$data=$xoopsDB->fetchArray($result);
		return $data;
	}
}

//把數量變成選項
if(!function_exists('amount2option')){
	function amount2option($amount="",$default_amount=""){
	  $main="<option></option>";
		for($i=1;$i<=$amount;$i++){
		  $selected=($default_amount==$i)?"selected":"";
			$main.="<option value='$i' $selected>$i</option>";
		}
		return $main;
	}
}

//訂單編號
if(!function_exists('mk_order_sn')){
	function mk_order_sn($order_sn="",$order_date=""){
	  $order_date=substr($order_date,2,8);
	  $order_date=str_replace("-","",$order_date);
		$sn=$order_date.sprintf("%04s",$order_sn);
		return $sn;
	}
}
?>
