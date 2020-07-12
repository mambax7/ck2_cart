<?php
//  ------------------------------------------------------------------------ //
// 本模組由 tad 製作
// 製作日期：2008-12-02
// $Id:$
// ------------------------------------------------------------------------- //

/*-----------引入檔案區--------------*/
include "header.php";
$xoopsOption['template_main'] = "commodity_tpl.html";
/*-----------function區--------------*/

//以流水號取得某筆ck2_stores_commodity資料
function get_ck2_stores_commodity_page($commodity_sn=""){
	global $xoopsDB,$xoopsUser;
	if(empty($commodity_sn))return;
	$sql = "select a.*,b.filename,c.kind_title,d.stores_sn,d.uid from ".$xoopsDB->prefix("ck2_stores_commodity")." as a left join ".$xoopsDB->prefix("ck2_stores_image_center")." as b on b.col_name='commodity_sn' and b.col_sn=a.commodity_sn left join ".$xoopsDB->prefix("ck2_stores_commodity_kinds")." as c on a.kinds_sn=c.kinds_sn left join ".$xoopsDB->prefix("ck2_stores")." as d on c.stores_sn=d.stores_sn  where a.enable='1' and commodity_sn='$commodity_sn'";
	$result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());
	$data=$xoopsDB->fetchArray($result);
  foreach($data as $k=>$v){
    $$k=$v;
  }
	//以下會產生這些變數： $commodity_sn,$kinds_sn,$brand_sn,$com_title,$com_summary,$com_content,$com_price,$com_sprice,$com_sprice_end_date,$com_unit,$com_post_date,$com_counter,$enable, $payment , $shipping

  $com_summary=nl2br($com_summary);

	$pic=(empty($filename))?XOOPS_URL."/modules/ck2_cart/images/no_commodities_thumb_pic.png":_CK2CART_UPLOAD_URL."/{$stores_sn}/commodity/{$commodity_sn}_{$filename}";
	
	//$price=(empty($com_sprice))?_MD_CK2CART_COM_PRICE._MD_CK2CART_FOR."<span class='price'>{$com_price}</span> "._MD_CK2CART_MONEY."/{$com_unit}":_MD_CK2CART_COM_PRICE._MD_CK2CART_FOR."<span style='text-decoration:line-through;' >{$com_price}</span> "._MD_CK2CART_MONEY."/{$com_unit}<br>"._MD_CK2CART_COM_SPRICE._MD_CK2CART_FOR."<span class='sprice'>{$com_sprice}</span> "._MD_CK2CART_MONEY."/{$com_unit}";
	
	$buy_it=buy_cart($commodity_sn);
	
	if($xoopsUser){
		$tool=($xoopsUser->getVar('uid')==$uid)?"<div align='right'><a href='admin/commodity.php?op=ck2_stores_commodity_form&commodity_sn=$commodity_sn'>"._BP_EDIT."</a> | <a href='admin/commodity.php?op=add_commodity&kinds_sn=$kinds_sn'>".sprintf(_MA_CK2CART_ADD_KIND_COMMODITY_PAGE,$kind_title)."</a> | <a href='admin/commodity.php'>"._MA_CK2CART_ADD_COMMODITY_PAGE."</a></div>":"";
	}else{
    $tool="";
	}
	
	$main="
	<script type='text/javascript' src='".XOOPS_URL."/modules/ck2_cart/class/jquery-1.3.2.min.js'></script>
  <script type='text/javascript' src='".XOOPS_URL."/modules/ck2_cart/class/jquery.tools.min.js'></script>
  <link rel='stylesheet' type='text/css' href='".XOOPS_URL."/modules/ck2_cart/class/tabs/tabs.css' />

  <script type='text/javascript'>
  $(function() {
    $('ul.tabs').tabs('div.tabs_content > div', {effect: 'ajax'});
	});
  </script>
  
	<table>
	<tr><td colspan=2 style='background-image:url(images/bg2.png);font-size:14pt;color:#ffffff;padding-left:10px;font-weight:bold;'>$com_title</td></tr>
	<tr><td style='width:250px;'><img src='$pic'></td><td style='vertical-align:top;padding:8px 12px;'>
	<div style='margin-top:8px;'>
	{$com_summary}
	<div align='right' style='color:#606060'>"._MD_CK2CART_COM_POST_DATE._MD_CK2CART_FOR."{$com_post_date}</div>
	{$buy_it}
	</div>
	</td></tr>
	</table>
	<!-- 頁籤 -->
	<ul class='tabs'>
	  <li><a href='commodity_data.php?op=content&commodity_sn=$commodity_sn'>"._MD_CK2CART_COM_DETAIL."</a></li>
	  <li><a href='commodity_data.php?op=shipping&commodity_sn=$commodity_sn'>"._MD_CK2CART_SHIPPING."</a></li>
	  <li><a href='commodity_data.php?op=payment&commodity_sn=$commodity_sn'>"._MD_CK2CART_PAYMENT."</a></li>
	</ul>

	<!-- 頁籤對應的內容 -->
	<div class='tabs_content'>
	  <div style='display:block'></div>
	</div>
	$tool
	";
	

	//raised,corners,inset
	$main=div_3d("",$main,"corners");
	
	$main=gray_border(_MA_CK2CART_COMMODITY_PAGE,$main);
	return $main;
}


//購物按鈕
function buy_cart($commodity_sn=""){
	global $xoopsDB,$xoopsUser;

	$sql = "select * from ".$xoopsDB->prefix("ck2_stores_commodity_specification")." where commodity_sn='$commodity_sn'";
	$result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());

	$data="<table>
	<tr><th>"._MD_CK2CART_COM_NAME."</th>
	<th>"._MD_CK2CART_PRICE."</th>
	<th nowrap>"._MD_CK2CART_AMOUNT."</th></tr>

	";

	while($all=$xoopsDB->fetchArray($result)){
	  //以下會產生這些變數： $specification_sn , $commodity_sn , $specification_title , $specification_amount , $specification_price , $specification_sprice , $specification_sprice_end_date
    foreach($all as $k=>$v){
      $$k=$v;
    }
		if(empty($specification_title))continue;
		
		 $price=(empty($specification_sprice) or $now > $end_date)?_MD_CK2CART_COM_PRICE._MD_CK2CART_FOR."<span class='price'>{$specification_price}</span> "._MD_CK2CART_MONEY."":_MD_CK2CART_COM_PRICE._MD_CK2CART_FOR."<span style='text-decoration:line-through;' >{$specification_price}</span> "._MD_CK2CART_MONEY."<br>"._MD_CK2CART_COM_SPRICE._MD_CK2CART_FOR."<span class='sprice'>{$specification_sprice}</span> "._MD_CK2CART_MONEY."";
		
    $specification_amount_select=(empty($specification_amount))?_MD_CK2CART_EMPTY_AMOUNT:"<select name='amount[$specification_sn]'>".amount2option($specification_amount,$_COOKIE['specification_sn'][$specification_sn])."</select>";
		
		
		$data.="<tr><td>{$specification_title}</td>
		<td nowrap>{$price}</td>
		<td>$specification_amount_select</td>
		</tr>\n";
	}

	$data.="</table>";

	$main="
	<form action='{$_SERVER['PHP_SELF']}' method='post'>
	$data
	<input type='hidden' name='commodity_sn' value='$commodity_sn'>
	<input type='hidden' name='op' value='add2cart'>
	<input type='hidden' name='mode' id='cart_mode' value=''>
	<div align='right'>
	<input type='image' src='images/cart.png'  onClick=\"document.getElementById('cart_mode').value='cart'\">
	<input type='image' src='images/pay.png'  onClick=\"document.getElementById('cart_mode').value='pay'\">
	</div>
	</form>";
	return $main;
}


//加入購物車
function add2cart($amount=""){
	foreach($amount as $specification_sn => $num){
	  setcookie("specification_sn[$specification_sn]", $num, time()+3600,"/");
	}
}



/*-----------執行動作判斷區----------*/
$op=(empty($_REQUEST['op']))?"":$_REQUEST['op'];
$commodity_sn=(empty($_REQUEST['commodity_sn']))?"":intval($_REQUEST['commodity_sn']);

switch($op){
	case "add2cart":
	add2cart($_POST['amount']);
	if($_POST['mode']=="pay"){
		header("location: order.php");
	}else{
		header("location: {$_SERVER['PHP_SELF']}?commodity_sn=$commodity_sn");
	}
	break;

	default:
	$main=get_ck2_stores_commodity_page($commodity_sn);
	break;
}

/*-----------秀出結果區--------------*/
include XOOPS_ROOT_PATH."/header.php";
$xoopsTpl->assign( "css" , "<link rel='stylesheet' type='text/css' media='screen' href='".XOOPS_URL."/modules/ck2_cart/module.css' />") ;
$xoopsTpl->assign( "toolbar" , toolbar($interface_menu)) ;
$xoopsTpl->assign( "content" , $main) ;
include_once XOOPS_ROOT_PATH.'/footer.php';

?>
