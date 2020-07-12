<?php
//  ------------------------------------------------------------------------ //
// 本模組由 tad 製作
// 製作日期：2008-12-02
// $Id:$
// ------------------------------------------------------------------------- //

/*-----------引入檔案區--------------*/
include "header.php";
$xoopsOption['template_main'] = "index_tpl.html";
/*-----------function區--------------*/


//列出所有ck2_stores資料
function show_stores($stores_sn=1){
	global $xoopsDB,$xoopsModule,$xoopsModuleConfig;

	add_counter("ck2_stores","store_counter","stores_sn",$stores_sn);
	
	$store_title=store_title($stores_sn);
	$show_new_commodities=show_new_commodities(true);
	$show_commodities=show_commodities("",$xoopsModuleConfig['commodity_list_mode'],$xoopsModuleConfig['show_num']);

  $data="
  $store_title
	$show_new_commodities
	$show_commodities
	";

	//raised,corners,inset
	//$main=div_3d("",$data,"corners");

	return $data;

}


//秀出最新商品
function show_new_commodities($nodiv=false,$limit=20){
	global $xoopsDB,$xoopsModule,$xoopsModuleConfig;
	
	if(empty($xoopsModuleConfig['new_commodity_height']))return;
	
	$MDIR=$xoopsModule->getVar('dirname');


	$sql = "select a.*,b.filename,d.stores_sn from ".$xoopsDB->prefix("ck2_stores_commodity")." as a left join ".$xoopsDB->prefix("ck2_stores_image_center")." as b on b.col_name='commodity_sn' and b.col_sn=a.commodity_sn left join ".$xoopsDB->prefix("ck2_stores_commodity_kinds")." as c on a.kinds_sn=c.kinds_sn left join ".$xoopsDB->prefix("ck2_stores")." as d on c.stores_sn=d.stores_sn  where a.enable='1' order by a.com_post_date desc ,a.commodity_sn desc limit 0,$limit";

	//PageBar(資料數, 每頁顯示幾筆資料, 最多顯示幾個頁數選項);
	$result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());

	$data="
	<script type='text/javascript' src='http://www.google.com/jsapi'></script>
	<script type='text/javascript' language='javascript'> google.load('jquery', '1.3'); </script>
  <script type='text/javascript' src='class/jquery.tools.min.js'></script>


	<script>
	$(function() {
		$('div.scrollable').scrollable({vertical:true,size: {$xoopsModuleConfig['new_commodity_num']}}).circular().mousewheel().autoscroll({ steps: 1, interval: {$xoopsModuleConfig['new_commodity_sec']}});
	});
	</script>

  <link rel='stylesheet' type='text/css' href='scrollable-vertical.css?v=0' />
  <style>
	.vertical {height: {$xoopsModuleConfig['new_commodity_height']}px;}
  </style>
  <table id='tbl' style='width:100%;'>
	<tr><td style='background-image:url(images/bg5.png);font-size:11pt;color:#404040;padding-left:10px;font-weight:bold;'><img src='images/cup_add.png' hspace=4> "._MD_CK2CART_NEW_COM."</td></tr>
	<tr>
		<td>
  <!-- 左邊按鈕 -->
	<div id='actions'>
		<a class='prevPage'>&laquo; "._BP_BACK_PAGE."</a>
		<a class='nextPage'>"._BP_NEXT_PAGE." &raquo;</a>
	</div>


	<!-- 捲軸區塊 -->
	<div class='scrollable vertical'>

		<!-- 項目區 -->
		<div class='items'>

	<!-- 右邊按鈕 -->
	<a class='nextPage browse right'></a>

	";

	while($all=$xoopsDB->fetchArray($result)){
	  //以下會產生這些變數： $commodity_sn,$kinds_sn,$brand_sn,$com_title,$com_summary,$com_content,$com_price,$com_sprice,$com_sprice_end_date,$com_unit,$com_post_date,$com_counter,$enable
    foreach($all as $k=>$v){
      $$k=$v;
    }
    
    $com_summary=nl2br($com_summary);

     $pic=(empty($filename))?XOOPS_URL."/modules/ck2_cart/images/no_commodities_thumb_pic.png":_CK2CART_UPLOAD_URL."/{$stores_sn}/commodity/thumb/{$commodity_sn}_{$filename}";

		$price=get_price($commodity_sn);

		$data.="
	  <table style='width:100%;border-bottom:1px dotted black;padding:0px;margin:0px;'>
		<tr>
		<td style='border-bottom:0px;vertical-align:top;width:120px;height:150px;ovewflow:hidden;text-align:center;'>
			<a href='commodity.php?commodity_sn=$commodity_sn'>
			<img src='$pic'>
			</a>
		</td>
		<td style='vertical-align:top;border-bottom:0px;'>
			<p style='float:right;font-size:12px;color:gray;margin:4px;0px;'>{$price}</p>
			<h3><a href='commodity.php?commodity_sn=$commodity_sn' style='text-decoration:none;color:#336699'>{$com_title}</a></h3>

			<p style='width:100%;font-size:12px;color:#202020;line-height:1.4;overflow:hidden;'>{$com_summary}</p>
	</td></tr></table>
		";
	}


	$data.="

		</div>
	</div></td></tr>
	</table>";

	if($nodiv)return $data;

	//raised,corners,inset
	$main=div_3d("",$data,"corners","width:100%");

	return $main;
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



//秀出商品
function show_commodities($kinds_sn="",$mode="rand",$show_num=20,$nodiv=true){
	global $xoopsDB,$xoopsModule;
	$MDIR=$xoopsModule->getVar('dirname');

	$and_kinds_sn=(empty($kinds_sn))?"":"and c.kinds_sn='{$kinds_sn}'";
	
	if(!empty($kinds_sn)){
		$kind=get_ck2_stores_commodity_kinds($kinds_sn);
	}

	
	if($mode=="rand"){
	  $title=_MD_CK2CART_RAND_COM;
		$orderby="order by rand()";
	}elseif($mode=="new"){
	  $title=(empty($kinds_sn))?_MD_CK2CART_ALL_COM:sprintf(_MD_CK2CART_KIND_ALL_COM,$kind['kind_title']);
    $orderby="order by a.com_post_date desc";
	}else{
	  $title=(empty($kinds_sn))?_MD_CK2CART_ALL_COM:sprintf(_MD_CK2CART_KIND_ALL_COM,$kind['kind_title']);
    $orderby="order by a.com_post_date desc";
	}
	
	$sql = "select a.*,b.filename,d.stores_sn from ".$xoopsDB->prefix("ck2_stores_commodity")." as a left join ".$xoopsDB->prefix("ck2_stores_image_center")." as b on b.col_name='commodity_sn' and b.col_sn=a.commodity_sn left join ".$xoopsDB->prefix("ck2_stores_commodity_kinds")." as c on a.kinds_sn=c.kinds_sn left join ".$xoopsDB->prefix("ck2_stores")." as d on c.stores_sn=d.stores_sn  where a.enable='1' $and_kinds_sn $orderby";
	

	//PageBar(資料數, 每頁顯示幾筆資料, 最多顯示幾個頁數選項);
	$result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());
	$total=$xoopsDB->getRowsNum($result);

	$navbar = new PageBar($total, $show_num, 10);
	$mybar = $navbar->makeBar();
	$bar= sprintf(_BP_TOOLBAR,$mybar['total'],$mybar['current'])."{$mybar['left']}{$mybar['center']}{$mybar['right']}";
	$sql.=$mybar['sql'];

	$result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());

	$data="
	<table id='tbl' style='width:100%;'>

	<tr><td colspan=2 style='background-image:url(images/bg2.png);font-size:11pt;color:#ffffff;padding-left:10px;font-weight:bold;'><img src='images/cup_add.png' hspace=4>$title</td></tr>
	<tbody><tr><td>";

	while($all=$xoopsDB->fetchArray($result)){
	  //以下會產生這些變數： $commodity_sn,$kinds_sn,$brand_sn,$com_title,$com_summary,$com_content,$com_price,$com_sprice,$com_sprice_end_date,$com_unit,$com_post_date,$com_counter,$enable
    foreach($all as $k=>$v){
      $$k=$v;
    }
    
     $pic=(empty($filename))?XOOPS_URL."/modules/ck2_cart/images/no_commodities_thumb_pic.png":_CK2CART_UPLOAD_URL."/{$stores_sn}/commodity/thumb/{$commodity_sn}_{$filename}";

		$price=get_price($commodity_sn,"short");

		$data.="
		<div style='width:120px;float:left;font-size:12px;'>
		
			<a href='commodity.php?commodity_sn=$commodity_sn'>
				<div style=\"border:1px solid #CFCFCF;width:100px;height:100px;overflow:hidden;margin:2px auto;background-image:url('{$pic}');background-repeat: no-repeat;background-position: center center;cursor:pointer;text-align:center;\"> </div>
			</a>

			<div style='margin:2px;height:50px;overflow:hidden;'><a href='commodity.php?commodity_sn=$commodity_sn' style='text-decoration:none;color:#336699'>{$com_title}</a></div>

			<div style='margin:2px;text-align:center;'>{$price}</div>
		
		</div>
		
		";
	}


	$data.="</td></tr>
	<tr><td>$bar</td></tr>
	</tbody>
	</table>";
	
	if($nodiv)return $data;

	//raised,corners,inset
	$main=div_3d("",$data,"corners");

	return $main;
}


//列出所有ck2_stores資料
function show_kind_stores($kinds_sn="",$stores_sn=1){
	global $xoopsDB,$xoopsModule,$xoopsModuleConfig;

	add_counter("ck2_stores_commodity_kinds","kind_counter","kinds_sn",$kinds_sn);

  $store_title=store_title($stores_sn);
	$show_commodities=show_commodities($kinds_sn,$xoopsModuleConfig['commodity_list_mode'],$xoopsModuleConfig['show_num']);

  $data="
  $store_title
	$show_commodities
	";

	//raised,corners,inset
	//$main=div_3d("",$data,"corners");

	return $data;

}


//商店標題
function store_title($stores_sn=""){
	global $xoopsDB,$xoopsModule;

	$sql = "select a.*,b.filename from ".$xoopsDB->prefix("ck2_stores")." as a left join ".$xoopsDB->prefix("ck2_stores_image_center")." as b on b.col_name='stores_sn' and b.col_sn=a.stores_sn  where a.stores_sn='{$stores_sn}' and a.enable='1'";
	$result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());


	$all=$xoopsDB->fetchArray($result);
  //以下會產生這些變數： $stores_sn,$store_title,$store_desc,$store_counter,$store_master,$store_email,$enable,$uid,$open_date
  foreach($all as $k=>$v){
    $$k=$v;
  }

	$store_desc_s=substr($store_desc,0,3);

	if($store_desc_s=="<p>"){
    $store_desc=substr($store_desc,3,-4);
	}

	$data="
	<table id='tbl' style='width:100%;'>

	<tr><td style='background-image:url(images/bg1.png);font-size:11pt;color:#ffffff;padding-left:10px;font-weight:bold;'>$store_title</td></tr>

	<tr><td style='vertical-align:top;font-size:12px;color:#404040;'>
	<img src='"._CK2CART_UPLOAD_URL."/{$stores_sn}/stores/thumb/{$stores_sn}_{$filename}' align='left' style='margin-right:6px;'>{$store_desc}
	<p style='float:right;font-size:12px;color:#a0a0a0;'>
	".sprintf(_MD_CK2CART_STORE_VISITER,$open_date,$store_counter)." |
	"._MD_CK2CART_STORE_MASTER._MD_CK2CART_FOR."<a href='mailto:{$store_email}'>{$store_master}</a>
	</p>
	</td></tr>
	</table>";
	return $data;
}


/*-----------執行動作判斷區----------*/
$op=(empty($_REQUEST['op']))?"":$_REQUEST['op'];
$kinds_sn=(empty($_REQUEST['kinds_sn']))?"":intval($_REQUEST['kinds_sn']);
switch($op){
	case "f2":
	f2();
	header("location: {$_SERVER['PHP_SELF']}");
	break;

	default:
	if(!empty($kinds_sn)){
		$main=show_kind_stores($kinds_sn);
	}else{
		$main=show_stores();
	}
	break;
}

/*-----------秀出結果區--------------*/
include XOOPS_ROOT_PATH."/header.php";
$xoopsTpl->assign( "css" , "<link rel='stylesheet' type='text/css' media='screen' href='".XOOPS_URL."/modules/ck2_cart/module.css' />") ;
$xoopsTpl->assign( "toolbar" , toolbar($interface_menu)) ;
$xoopsTpl->assign( "content" , $main) ;
include_once XOOPS_ROOT_PATH.'/footer.php';

?>
