<?php
//  ------------------------------------------------------------------------ //
// 本模組由 tad 製作
// 製作日期：2008-12-02
// $Id:$
// ------------------------------------------------------------------------- //

/*-----------引入檔案區--------------*/
include "header.php";
$xoopsOption['template_main'] = "history_tpl.html";
/*-----------function區--------------*/

$uid=$xoopsUser->getVar("uid");

$sql = "select a.*,b.shipping_name,b.shipping_pay from ".$xoopsDB->prefix("ck2_stores_order")." as a left join ".$xoopsDB->prefix("ck2_stores_shipping")." as b on a.shipping_sn=b.shipping_sn where a.uid='$uid' order by a.order_date desc";

//PageBar(資料數, 每頁顯示幾筆資料, 最多顯示幾個頁數選項);
$result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());
$total=$xoopsDB->getRowsNum($result);

$navbar = new PageBar($total, 10, 10);
$mybar = $navbar->makeBar();
$bar= sprintf(_BP_TOOLBAR,$mybar['total'],$mybar['current'])."{$mybar['left']}{$mybar['center']}{$mybar['right']}";
$sql.=$mybar['sql'];
	
	
$result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());
$main="<table summary='list_table' style='width:100%;' id='tbl'>
<tr>
	<th>"._MD_CK2CART_ORDER_SN."</th>
	<th>"._MD_CK2CART_MY_ORDER_ITEM."</th>
	<th>"._MD_CK2CART_SHIPPING_PAY."</th>
	<th>"._MD_CK2CART_TOTAL."</th>
	<th colspan='2'>"._MD_CK2CART_ORDER_STATUS_NOW."</th>
	</tr>";
while($all=$xoopsDB->fetchArray($result)){
  //以下會產生這些變數： $order_sn , $uid , $order_date , $customer_addr_sn , $shipping_sn , $order_note ,$sum , $payment_sn , $bank_name , $bank_num , $pay_money , $order_pay_date , $order_status
  foreach($all as $k=>$v){
    $$k=$v;
  }

  $order_status=(empty($order_status))?_MD_CK2CART_ORDER_STATUS_WAIT:$order_status;
  $sn=mk_order_sn($order_sn,$order_date);

	$spec=list_ck2_stores_order_commodity($order_sn);
		
  $pic=(empty($pay_money))?"<a href='".XOOPS_URL."/modules/ck2_cart/pay.php?order_sn=$order_sn'><img src='".XOOPS_URL."/modules/ck2_cart/images/file_edit.png' alt='".sprintf(_MD_CK2CART_ORDER_PAY,$specification_title)."' title='".sprintf(_MD_CK2CART_ORDER_PAY,$sn)."' class='pay'></a>":"";
  
  $order_date=substr($order_date,0,10);
  
  $main.="
	<tr>
	<td><a href='".XOOPS_URL."/modules/ck2_cart/pay.php?order_sn=$order_sn' style='font-weight:normal;'>{$sn}</a><div  style='font-size:11px;color:#FF6600'>$order_date</div></td>
	<td>{$spec['all']}</td>
	<td>{$shipping_pay}</td>
	<td>{$sum}</td>
	<td>$order_status</td>
	<td nowrap>$pic</td>
	</tr>";
}
$main.="
<tr><td colspan=6 class='bar'>$bar</td></tr>
</table>
<div id='paytip'>&nbsp;</div>";


	//raised,corners,inset
	$main=div_3d("",$main,"corners","");

$main=gray_border(_MD_CK2CART_MY_ORDER_LIST,$main);

/*-----------秀出結果區--------------*/
include XOOPS_ROOT_PATH."/header.php";
$xoopsTpl->assign( "css" , "<link rel='stylesheet' type='text/css' media='screen' href='".XOOPS_URL."/modules/ck2_cart/module.css' />") ;
$xoopsTpl->assign( "toolbar" , toolbar($interface_menu)) ;
$xoopsTpl->assign( "content" , $main) ;
include_once XOOPS_ROOT_PATH.'/footer.php';

?>
