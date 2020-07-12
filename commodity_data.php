<?php
include "header.php";

$op=(empty($_REQUEST['op']))?"":$_REQUEST['op'];
$commodity_sn=(empty($_REQUEST['commodity_sn']))?"":intval($_REQUEST['commodity_sn']);

$commodity=get_ck2_stores_commodity($commodity_sn);


switch($op){
	case "content":
	echo $commodity['com_content'];
	break;
	
	case "payment":
	echo show_payment($commodity['payment']);
	break;

	case "shipping":
	echo show_shipping($commodity['shipping']);
	break;
	
	default:
	echo "";
	break;
}

function show_payment($payment=""){
	global $xoopsDB,$xoopsUser;
	$main="";
	//$payment_arr=explode(",",$payment);
	$sql = "select * from ".$xoopsDB->prefix("ck2_stores_payment")." where payment_sn in ($payment) and enable='1'";
	$result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());
	while($all=$xoopsDB->fetchArray($result)){
	  //以下會產生這些變數： $payment_sn , $payment_name , $payment_desc , $enable
    foreach($all as $k=>$v){
      $$k=$v;
    }
    $main.="
		<h3>$payment_name</h3>
		$payment_desc";
	}
	return $main;
}


function show_shipping($shipping=""){
	global $xoopsDB,$xoopsUser;
	$main="";
	//$payment_arr=explode(",",$payment);
	$sql = "select * from ".$xoopsDB->prefix("ck2_stores_shipping")." where shipping_sn in ($shipping)and enable='1'";
	$result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());
	while($all=$xoopsDB->fetchArray($result)){
	  //以下會產生這些變數： $shipping_sn,$shipping_name,$shipping_desc,$shipping_pay,$enable
    foreach($all as $k=>$v){
      $$k=$v;
    }
    $main.="
		<h3>$shipping_name</h3>
		$shipping_desc
		"._MD_CK2CART_SHIPPING_PAY._BP_FOR.$shipping_pay._MD_CK2CART_MONEY;
	}
	return $main;
}
?>
