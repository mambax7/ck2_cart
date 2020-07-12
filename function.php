<?php
//  ------------------------------------------------------------------------ //
// 本模組由 tad 製作
// 製作日期：2008-12-02
// $Id:$
// ------------------------------------------------------------------------- //


define("_CK2CART_UPLOAD_DIR",XOOPS_ROOT_PATH."/uploads/ck2_cart");
define("_CK2CART_UPLOAD_URL",XOOPS_URL."/uploads/ck2_cart");

//立即寄出
function send_now($email="",$title="",$content=""){
	global $xoopsConfig,$xoopsDB,$xoopsModuleConfig,$xoopsModule;

	$xoopsMailer =& getMailer();
	$xoopsMailer->multimailer->ContentType="text/html";
	$xoopsMailer->addHeaders("MIME-Version: 1.0");

	$msg.=($xoopsMailer->sendMail($email,$title, $content,$headers))?"<div>mail to {$email} OK~</div>":"<div>mail to {$email} error!</div>";
	return $msg;
}

//以流水號取得某筆ck2_stores_customer資料
function get_ck2_stores_customer($uid=""){
	global $xoopsDB;
	if(empty($uid))return;
	$sql = "select * from ".$xoopsDB->prefix("ck2_stores_customer")." where uid='$uid'";
	$result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());
	$data=$xoopsDB->fetchArray($result);
	return $data;
}



//列出所有ck2_stores_order_commodity資料
function list_ck2_stores_order_commodity($order_sn=""){
	global $xoopsDB,$xoopsModule;

	$money=0;


	$MDIR=$xoopsModule->getVar('dirname');
	$sql = "select a.*,b.specification_title,b.commodity_sn from ".$xoopsDB->prefix("ck2_stores_order_commodity")." as a left join ".$xoopsDB->prefix("ck2_stores_commodity_specification")." as b on a.specification_sn=b.specification_sn where a.order_sn='$order_sn'";
	$result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());

	$data="
	<table  style='width:100%;border:0px;'>";
	while($all=$xoopsDB->fetchArray($result)){
	  //以下會產生這些變數： $order_sn , $specification_sn , $amount , $sum
    foreach($all as $k=>$v){
      $$k=$v;
    }

		$data.="<tr style='border:0px;'>
		<td style='width:150px;border:0px;'><a href='".XOOPS_URL."/modules/ck2_cart/commodity.php?commodity_sn=$commodity_sn' style='font-weight:normal;font-size:11px;'>{$specification_title}</a></td>
		<td style='width:20px;border:0px;'>{$amount}</td>
		<td style='width:50px;text-align:right;border:0px;'>{$sum}</td>
		$fun
		</tr>";

    $money+=$sum;
	}

	$data.="
	</table>";


	$main['all']=$data;
	$main['money']=$money;

	return $main;
}

//訂單編號
function mk_order_sn($order_sn="",$order_date=""){
  $order_date=substr($order_date,2,8);
  $order_date=str_replace("-","",$order_date);
	$sn=$order_date.sprintf("%04s",$order_sn);
	return $sn;
}

//取得最新售價
function get_price($commodity_sn="",$mode=""){
	global $xoopsDB,$xoopsUser;

	$sql = "select * from ".$xoopsDB->prefix("ck2_stores_commodity_specification")." where commodity_sn='$commodity_sn' order by specification_price limit 0,1";
	$result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());

	while($all=$xoopsDB->fetchArray($result)){
	  //以下會產生這些變數： $specification_sn , $commodity_sn , $specification_title , $specification_amount , $specification_price , $specification_sprice , $specification_sprice_end_date
    foreach($all as $k=>$v){
      $$k=$v;
    }
    
    $now=time();
		$end_date=strtotime($specification_sprice_end_date);
    
    if($mode=="short"){
      $price=(empty($specification_sprice) or $now > $end_date)?_MD_CK2CART_COM_PRICE._MD_CK2CART_FOR."<span class='price'>{$specification_price}</span> "._MD_CK2CART_MONEY."":_MD_CK2CART_COM_SPRICE._MD_CK2CART_FOR."<span class='sprice'>{$specification_sprice}</span> "._MD_CK2CART_MONEY."";
		}else{
			$price=(empty($specification_sprice) or $now > $end_date)?_MD_CK2CART_COM_PRICE._MD_CK2CART_FOR."<span class='price'>{$specification_price}</span> "._MD_CK2CART_MONEY."":_MD_CK2CART_COM_PRICE._MD_CK2CART_FOR."<span style='text-decoration:line-through;' >{$specification_price}</span> "._MD_CK2CART_MONEY." "._MD_CK2CART_COM_SPRICE._MD_CK2CART_FOR."<span class='sprice'>{$specification_sprice}</span> "._MD_CK2CART_MONEY."";
		}
	}

	return $price;

}



//以流水號取得某筆ck2_stores_commodity資料
function get_ck2_stores_commodity($commodity_sn=""){
	global $xoopsDB;
	if(empty($commodity_sn))return;
	$sql = "select * from ".$xoopsDB->prefix("ck2_stores_commodity")." where commodity_sn='$commodity_sn'";
	$result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());
	$data=$xoopsDB->fetchArray($result);
	return $data;
}

//從規格取得商品資料
function get_ck2_com_from_spec($specification_sn=""){
	global $xoopsDB;
	if(empty($specification_sn))return;
	$sql = "select a.*,b.commodity_sn,b.com_title,b.com_unit,b.com_summary,b.payment,b.shipping,c.filename,d.stores_sn
	from ".$xoopsDB->prefix("ck2_stores_commodity_specification")." as a
	left join ".$xoopsDB->prefix("ck2_stores_commodity")." as b
		on a.commodity_sn=b.commodity_sn
	left join ".$xoopsDB->prefix("ck2_stores_image_center")." as c
		on c.col_name='commodity_sn' and c.col_sn=b.commodity_sn
	left join ".$xoopsDB->prefix("ck2_stores_commodity_kinds")." as d
		on d.kinds_sn=b.kinds_sn
	where a.specification_sn='$specification_sn'";
	$result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());
	$data=$xoopsDB->fetchArray($result);
	return $data;
}



//取得ck2_stores_commodity_kinds所有資料陣列
function get_ck2_stores_commodity_kinds_all($stores_sn=""){
	global $xoopsDB;
	$sql = "select * from ".$xoopsDB->prefix("ck2_stores_commodity_kinds")." where stores_sn='{$stores_sn}'";
	$result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());
	while($data=$xoopsDB->fetchArray($result)){
		$kinds_sn=$data['kinds_sn'];
		$data_arr[$kinds_sn]=$data;
	}
	return $data_arr;
}



//把數量變成選項
function amount2option($amount="",$default_amount=""){
  $main="<option></option>";
	for($i=1;$i<=$amount;$i++){
	  $selected=($default_amount==$i)?"selected":"";
		$main.="<option value='$i' $selected>$i</option>";
	}
	return $main;
}


//取得ck2_stores_brand所有資料陣列
function get_ck2_stores_brand_all(){
	global $xoopsDB;
	$stores_sn=get_stores_sn();
	$sql = "select * from ".$xoopsDB->prefix("ck2_stores_brand")." where stores_sn='$stores_sn'";
	$result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());
	while($data=$xoopsDB->fetchArray($result)){
		$brand_sn=$data['brand_sn'];
		$data_arr[$brand_sn]=$data;
	}
	return $data_arr;
}


//上傳圖檔，$col_name=store,commodities
function upload_pic($col_name="",$col_sn="",$sort="",$update_sql="insert",$images_sn=""){
	global $xoopsDB,$xoopsUser;
  include_once XOOPS_ROOT_PATH."/modules/ck2_cart/class/upload/class.upload.php";

	
	$stores_sn=get_stores_sn();


  set_time_limit(0);
  ini_set('memory_limit', '50M');

  $img_handle = new upload($_FILES['image'],"zh_TW");

  if($col_name=="stores_sn"){
    $image_dir=_CK2CART_UPLOAD_DIR."/{$stores_sn}/stores";
    $thumb_image_dir=_CK2CART_UPLOAD_DIR."/{$stores_sn}/stores/thumb";
  }elseif($col_name=="commodity_sn"){
    $image_dir=_CK2CART_UPLOAD_DIR."/{$stores_sn}/commodity";
    $thumb_image_dir=_CK2CART_UPLOAD_DIR."/{$stores_sn}/commodity/thumb";
  }else{
    $image_dir=_CK2CART_UPLOAD_DIR."/{$stores_sn}/image";
    $thumb_image_dir=_CK2CART_UPLOAD_DIR."/{$stores_sn}/image/thumb";
  }
  
  if ($img_handle->uploaded) {
	    $name=strtolower(substr($_FILES['image']['name'],0,-4));
      $img_handle->file_safe_name = false;
      $img_handle->file_new_name_body   = "{$col_sn}_{$name}";
      $img_handle->image_resize         = true;
      $img_handle->image_x              = 250;
      $img_handle->image_ratio_y        = true;
      $img_handle->process($image_dir);
      $img_handle->auto_create_dir = true;

      //製作縮圖
      $img_handle->file_safe_name = false;
      $img_handle->file_new_name_body   = "{$col_sn}_{$name}";
      $img_handle->image_resize         = true;
      $img_handle->image_x              = 100;
      $img_handle->image_ratio_y        = true;
      $img_handle->process($thumb_image_dir);
      $img_handle->auto_create_dir = true;
      
      if ($img_handle->processed) {
          $img_handle->clean();
          $image_name=strtolower($_FILES['image']['name']);
          
          if($update_sql=="insert"){
  	        $sql = "insert into ".$xoopsDB->prefix("ck2_stores_image_center")." (`col_name`, `col_sn`, `filename`, `sort`) values('$col_name','$col_sn','{$image_name}','$sort')";
  					$xoopsDB->queryF($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());
          }elseif($update_sql=="update"){
  	         $sql = "replace into ".$xoopsDB->prefix("ck2_stores_image_center")." (`col_name`, `col_sn`, `filename`, `sort`) values('$col_name','$col_sn','{$image_name}','$sort')";
  					$xoopsDB->queryF($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());
          }

					return true;
      } else {
					redirect_header($_SERVER['PHP_SELF'],3, "Error:".$img_handle->error);
      }
  }
}

//取得所有商品分類選單(商店編號,父分類搜尋編號,目前分類的所屬編號,目前分類編號)
function commodity_kinds_select($stores_sn="",$start_search_sn="0",$default_of_kinds_sn="0",$default_kinds_sn="0",$level=0){
	global $xoopsDB,$xoopsModule;
	$sql = "select kinds_sn,kind_title from ".$xoopsDB->prefix("ck2_stores_commodity_kinds")." where stores_sn='{$stores_sn}' and of_kinds_sn='{$start_search_sn}' order by kind_sort";
	$result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());
	
	//die($sql);
	$prefix=str_repeat("__",$level);
	$level++;

	$main="";
	while(list($kinds_sn,$kind_title)=$xoopsDB->fetchRow($result)){
	  $selected=($kinds_sn==$default_of_kinds_sn)?"selected":"";
	  if($kinds_sn==$default_kinds_sn){
	  	continue;
	  }else{
	  	$main.="<option value=$kinds_sn $selected>{$prefix}{$kind_title}</option>";
      $main.=commodity_kinds_select($stores_sn,$kinds_sn,$default_of_kinds_sn,$default_kinds_sn,$level);
		}
	}
	return $main;
}

//以uid取得商店編號
function get_stores_sn(){
	global $xoopsDB,$xoopsUser;
	$uid=$xoopsUser->getVar('uid');
	$sql = "select stores_sn from ".$xoopsDB->prefix("ck2_stores")." where uid='$uid'";
	$result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());
	list($stores_sn)=$xoopsDB->fetchRow($result);
	return $stores_sn;
}

//以流水號取得某筆ck2_stores_shipping資料
function get_ck2_stores_shipping($shipping_sn=""){
	global $xoopsDB;
	if(empty($shipping_sn))return;
	$sql = "select * from ".$xoopsDB->prefix("ck2_stores_shipping")." where shipping_sn='$shipping_sn'";
	$result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());
	$data=$xoopsDB->fetchArray($result);
	return $data;
}


//計數器
function add_counter($tbl="",$counter_col="",$col="",$col_sn=""){
	global $xoopsDB;
	$sql = "update ".$xoopsDB->prefix($tbl)." set  `{$counter_col}` = `{$counter_col}`+1 where `{$col}`='{$col_sn}'";
	$xoopsDB->queryF($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());
}

//把模組設定項目轉為選項
function mc2arr($name="",$def="",$kind="option"){
	global $xoopsModuleConfig;
	$arr=explode(";",$xoopsModuleConfig[$name]);
	if($kind=="checkbox"){
		$opt=arr2checkbox($name,$arr,$def,true);
	}else{
		$opt=arr2opt($arr,$def,true);
	}
	return $opt;
}


//把陣列轉為option選項
function arr2opt($arr,$def="",$v_as_k=false){
	foreach($arr as $k=>$v){
	  if($v_as_k)$k=$v;
	  $selected=($k==$def)?"selected":"";
		$main.="<option value='$k' $selected>$v</option>";
	}
	return $main;
}


//以流水號取得某筆ck2_stores資料
function get_ck2_stores($stores_sn="",$uid=""){
	global $xoopsDB;
	if(!empty($stores_sn)){
	  $w="stores_sn='$stores_sn'";
	}elseif(!empty($uid)){
		$w="uid='$uid'";
	}else{
		return;
	}
	$sql = "select * from ".$xoopsDB->prefix("ck2_stores")." where $w";
	$result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());
	$data=$xoopsDB->fetchArray($result);
	return $data;
}


/********************* 預設函數 *********************/
//圓角文字框
function div_3d($title="",$main="",$kind="raised",$style=""){
	$main="<table style='width:auto;{$style}'><tr><td>
	<div class='{$kind}'>
	<h1>$title</h1>
	<b class='b1'></b><b class='b2'></b><b class='b3'></b><b class='b4'></b>
	<div class='boxcontent'>
 	$main
	</div>
	<b class='b4b'></b><b class='b3b'></b><b class='b2b'></b><b class='b1b'></b>
	</div>
	</td></tr></table>";
	return $main;
}

//灰底外框
function gray_border($title="",$data=""){
	$main="
	<div style='background-color: #F2F2F2;	padding:10px;'>
		  <div style='color:#D2D2D2;font-size:24px;font-weight:bold;margin-bottom:8px;'>$title</div>
		  $data
	</div>";
	return $main;
}


//管理介面的選單
function menu_interface($show=1){
global $xoopsModule,$xoopsModuleConfig;
	if(empty($show))return;
	$dirname=$xoopsModule->getVar('dirname');
	include_once("".XOOPS_ROOT_PATH."/modules/{$dirname}/language/tchinese/modinfo.php");
	include("menu.php");
	$page=explode("/",$_SERVER['PHP_SELF']);
	$n=sizeof($page)-1;
	if(is_array($adminmenu)){
		foreach($adminmenu as $m){
			$td.="<a href='".XOOPS_URL."/modules/{$dirname}/{$m['link']}'>{$m['title']}</a>";
		}
	}else{
		$td="<td></td>";
	}
	$main="
	<style type='text/css'>
	#admtool{
		margin-bottom:10px;
	}
	#admtool a:link, #admtool a:visited {
		font-size: 12px;
		background-image: url(".XOOPS_URL."/modules/{$dirname}/images/bbg.jpg);
		margin-right: 0px;
		padding: 3px 10px 2px 10px;
		color: rgb(80,80,80);
		background-color: #FCE6EA;
		text-decoration: none;
		border-top: 1px solid #FFFFFF;
		border-left: 1px solid #FFFFFF;
		border-bottom: 1px solid #717171;
		border-right: 1px solid #717171;
	}
	#admtool a:hover {
		background-image: url(".XOOPS_URL."/modules/{$dirname}/images/bbg2.jpg);
		color: rgb(255,0,128);
		border-top: 1px solid #717171;
		border-left: 1px solid #717171;
		border-bottom: 1px solid #FFFFFF;
		border-right: 1px solid #FFFFFF;
	}
	</style>
	<div id='admtool'>{$td}<a href='".XOOPS_URL."/modules/{$dirname}/'>"._BACK_MODULES_PAGE."</a>
	</div>";
	return $main;
}

//首頁的連結工具
function toolbar($interface_menu=array()){
	global $xoopsModule,$xoopsModuleConfig,$xoopsUser;
	if(empty($interface_menu))return;
	$dirname=$xoopsModule->getVar('dirname');
	$moduleperm_handler = & xoops_gethandler( 'groupperm' );
	//判斷是否有管理權限
	if ( $xoopsUser) {
		if ($moduleperm_handler->checkRight( 'module_admin', $xoopsModule->getVar( 'mid' ), $xoopsUser->getGroups() ) ) {
			$admin_tools="<a href='".XOOPS_URL."/modules/{$dirname}/admin/index.php'>"._TO_ADMIN_PAGE."</a>";
		}
	}
	if(is_array($interface_menu)){
		foreach($interface_menu as $title => $url){
			$td.="<a href='".XOOPS_URL."/modules/{$dirname}/{$url}'>{$title}</a>";
		}
	}else{
		return;
	}
	$main="
	<style type='text/css'>
	#toolbar{
		margin-bottom:10px;
	}
	#toolbar a:link, #toolbar a:visited {
		font-size: 11px;
		background-image: url(".XOOPS_URL."/modules/{$dirname}/images/bbg.jpg);
		margin-right: 0px;
		padding: 3px 10px 2px 10px;
		color: rgb(80,80,80);
		background-color: #FCE6EA;
		text-decoration: none;
		border-top: 1px solid #FFFFFF;
		border-left: 1px solid #FFFFFF;
		border-bottom: 1px solid #717171;
		border-right: 1px solid #717171;
	}
	#toolbar a:hover {
		background-image: url(".XOOPS_URL."/modules/{$dirname}/images/bbg2.jpg);
		color: rgb(255,0,128);
		border-top: 1px solid #717171;
		border-left: 1px solid #717171;
		border-bottom: 1px solid #FFFFFF;
		border-right: 1px solid #FFFFFF;
	}
	</style>
	<div id='toolbar'>{$td}{$admin_tools}</div>";
	return $main;
}

//單選回復原始資料函數
function chk($DBV="",$NEED_V="",$defaul="",$return="checked"){
	if($DBV==$NEED_V){
		return $return;
	}elseif(empty($DBV) && $defaul=='1'){
		return $return;
	}
	return "";
}

//複選回復原始資料函數
function chk2($default_array="",$NEED_V="",$default=1){
	if(in_array($NEED_V,$default_array)){
		return "checked";
	}elseif($default=='1'){
		return "checked";
	}

	return "";
}


//細部權限判斷
function power_chk($perm_name="",$psn=""){
	global $xoopsUser,$xoopsModule;

	//取得目前使用者的群組編號
	if($xoopsUser) {
		$groups = $xoopsUser->getGroups();
	}else{
		$groups = XOOPS_GROUP_ANONYMOUS;
	}

	//取得模組編號
	$module_id = $xoopsModule->getVar('mid');
	//取得群組權限功能
	$gperm_handler =& xoops_gethandler('groupperm');

	//權限項目編號
	$perm_itemid = intval($psn);
	//依據該群組是否對該權限項目有使用權之判斷 ，做不同之處理
	if($gperm_handler->checkRight($perm_name, $perm_itemid, $groups, $module_id)) {
		return true;
	}
	return false;
}

//單選判斷
function is_checked($v1="",$v2="",$default=""){
	if(isset($v1) and $v1==$v2){
		return "checked";
	}elseif($default=="default"){
		return "checked";
	}
}



//分頁物件
class PageBar{
	// 目前所在頁碼
	var $current;
	// 所有的資料數量 (rows)
	var $total;
	// 每頁顯示幾筆資料
	var $limit;
	// 目前在第幾層的頁數選項？
	var $pCurrent;
	// 總共分成幾頁？
	var $pTotal;
	// 每一層最多有幾個頁數選項可供選擇，如：3 = {[1][2][3]}
	var $pLimit;
	var $prev;
	var $next;
	var $prev_layer = ' ';
	var $next_layer = ' ';
	var $first;
	var $last;
	var $bottons = array();
	// 要使用的 URL 頁數參數名？
	var $url_page = "g2p";
	// 要使用的 URL 讀取時間參數名？
	var $url_loadtime = "loadtime";
	// 會使用到的 URL 變數名，給 process_query() 過濾用的。
	var $used_query = array();
	// 目前頁數顏色
	var $act_color = "#990000";
	var $query_str; // 存放 URL 參數列

	function PageBar($total, $limit, $page_limit){
		$mydirname = basename( dirname( __FILE__ ) ) ;
		$this->prev = "<img src='".XOOPS_URL."/modules/{$mydirname}/images/1leftarrow.gif' alt='"._BP_BACK_PAGE."' align='absmiddle' hspace=3>"._BP_BACK_PAGE;
		$this->next = "<img src='".XOOPS_URL."/modules/{$mydirname}/images/1rightarrow.gif' alt='"._BP_NEXT_PAGE."' align='absmiddle' hspace=3>"._BP_NEXT_PAGE;
		$this->first = "<img src='".XOOPS_URL."/modules/{$mydirname}/images/2leftarrow.gif' alt='"._BP_FIRST_PAGE."' align='absmiddle' hspace=3>"._BP_FIRST_PAGE;
		$this->last = "<img src='".XOOPS_URL."/modules/{$mydirname}/images/2rightarrow.gif' alt='"._BP_LAST_PAGE."' align='absmiddle' hspace=3>"._BP_LAST_PAGE;

		$this->limit = $limit;
		$this->total = $total;
		$this->pLimit = $page_limit;
	}

	function init(){
		$this->used_query = array($this->url_page, $this->url_loadtime);
		$this->query_str = $this->processQuery($this->used_query);
		$this->glue = ($this->query_str == "")?'?':
		'&';
		$this->current = (isset($_GET["$this->url_page"]))? $_GET["$this->url_page"]:
		1;
		$this->pTotal = ceil($this->total / $this->limit);
		$this->pCurrent = ceil($this->current / $this->pLimit);
	}

	//初始設定
	function set($active_color = "none", $buttons = "none"){
		if ($active_color != "none"){
			$this->act_color = $active_color;
		}

		if ($buttons != "none"){
			$this->buttons = $buttons;
			$this->prev = $this->buttons['prev'];
			$this->next = $this->buttons['next'];
			$this->prev_layer = $this->buttons['prev_layer'];
			$this->next_layer = $this->buttons['next_layer'];
			$this->first = $this->buttons['first'];
			$this->last = $this->buttons['last'];
		}
	}

	// 處理 URL 的參數，過濾會使用到的變數名稱
	function processQuery($used_query){
		// 將 URL 字串分離成二維陣列
		$vars = explode("&", $_SERVER['QUERY_STRING']);
		for($i = 0; $i < count($vars); $i++){
			$var[$i] = explode("=", $vars[$i]);
		}

		// 過濾要使用的 URL 變數名稱
		for($i = 0; $i < count($var); $i++){
			for($j = 0; $j < count($used_query); $j++){
				if (isset($var[$i][0]) && $var[$i][0] == $used_query[$j]) $var[$i] = array();
			}
		}

		// 合併變數名與變數值
		for($i = 0; $i < count($var); $i++){
			$vars[$i] = implode("=", $var[$i]);
		}

		// 合併為一完整的 URL 字串
		$processed_query = "";
		for($i = 0; $i < count($vars); $i++){
			$glue = ($processed_query == "")?'?':
			'&';
			// 開頭第一個是 '?' 其餘的才是 '&'
			if ($vars[$i] != "") $processed_query .= $glue.$vars[$i];
		}
		return $processed_query;
	}

	// 製作 sql 的 query 字串 (LIMIT)
	function sqlQuery(){
		$row_start = ($this->current * $this->limit) - $this->limit;
		$sql_query = " LIMIT {$row_start}, {$this->limit}";
		return $sql_query;
	}


	// 製作 bar
	function makeBar($url_page = "none"){
		if ($url_page != "none"){
			$this->url_page = $url_page;
		}
		$this->init();

		// 取得目前時間
		$loadtime = '&loadtime='.time();

		// 取得目前頁框(層)的第一個頁數啟始值，如 6 7 8 9 10 = 6
		$i = ($this->pCurrent * $this->pLimit) - ($this->pLimit - 1);

		$bar_center = "";
		while ($i <= $this->pTotal && $i <= ($this->pCurrent * $this->pLimit)){
			if ($i == $this->current){
				$bar_center = "{$bar_center}<font color='{$this->act_color}'>[{$i}]</font>";
			}else{
				$bar_center .= " <a href='{$_SERVER['PHP_SELF']}{$this->query_str}{$this->glue}{$this->url_page}={$i}{$loadtime}'' title='{$i}'>{$i}</a> ";
			}
			$i++;
		}
		$bar_center = $bar_center . "";

		// 往前跳一頁
		if ($this->current <= 1){
			$bar_left = " {$this->prev} ";
			$bar_first = " {$this->first} ";
		}	else{
			$i = $this->current-1;
			$bar_left = " <a href='{$_SERVER['PHP_SELF']}{$this->query_str}{$this->glue}{$this->url_page}={$i}{$loadtime}' title='"._BP_BACK_PAGE."'>{$this->prev}</a> ";
			$bar_first = " <a href='{$_SERVER['PHP_SELF']}{$this->query_str}{$this->glue}{$this->url_page}=1{$loadtime}' title='"._BP_FIRST_PAGE."'>{$this->first}</a> ";
		}

		// 往後跳一頁
		if ($this->current >= $this->pTotal){
			$bar_right = " {$this->next} ";
			$bar_last = " {$this->last} ";
		}	else{
			$i = $this->current + 1;
			$bar_right = " <a href='{$_SERVER['PHP_SELF']}{$this->query_str}{$this->glue}{$this->url_page}={$i}{$loadtime}' title='"._BP_NEXT_PAGE."'>{$this->next}</a> ";
			$bar_last = " <a href='{$_SERVER['PHP_SELF']}{$this->query_str}{$this->glue}{$this->url_page}={$this->pTotal}{$loadtime}' title='"._BP_LAST_PAGE."'>{$this->last}</a> ";
		}

		// 往前跳一整個頁框(層)
		if (($this->current - $this->pLimit) < 1){
			$bar_l = " {$this->prev_layer} ";
		}	else{
			$i = $this->current - $this->pLimit;
			$bar_l = " <a href='{$_SERVER['PHP_SELF']}{$this->query_str}{$this->glue}{$this->url_page}={$i}{$loadtime}' title='".sprintf($this->pLimit,_BP_GO_BACK_PAGE)."'>{$this->prev_layer}</a> ";
		}

		//往後跳一整個頁框(層)
		if (($this->current + $this->pLimit) > $this->pTotal){
			$bar_r = " {$this->next_layer} ";
		}	else{
			$i = $this->current + $this->pLimit;
			$bar_r = " <a href='{$_SERVER['PHP_SELF']}{$this->query_str}{$this->glue}{$this->url_page}={$i}{$loadtime}' title='".sprintf($this->pLimit,_BP_GO_NEXT_PAGE)."'>{$this->next_layer}</a> ";
		}

		$page_bar['center'] = $bar_center;
		$page_bar['left'] = $bar_first . $bar_l . $bar_left;
		$page_bar['right'] = $bar_right . $bar_r . $bar_last;
		$page_bar['current'] = $this->current;
		$page_bar['total'] = $this->pTotal;
		$page_bar['sql'] = $this->sqlQuery();
		return $page_bar;
	}

}

?>
