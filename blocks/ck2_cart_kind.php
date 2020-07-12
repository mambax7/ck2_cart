<?php
//  ------------------------------------------------------------------------ //
// 本模組由 tad 製作
// 製作日期：2008-12-02
// $Id:$
// ------------------------------------------------------------------------- //

//區塊主函式 (商品種類(ck2_cart_kind))
function ck2_cart_kind($options){
	$block=list_ck2_stores_commodity_kinds_loop(0);
	return $block;
}


//取得ck2_stores_commodity_kinds無窮分類列表
function list_ck2_stores_commodity_kinds_loop($show_function=1,$show_of_cate_sn=0,$i=0){
	global $xoopsDB;
	
	$sql = "select kinds_sn,count(*) from ".$xoopsDB->prefix("ck2_stores_commodity")." where enable='1' group by kinds_sn ";
	$result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());
	while(list($kinds_sn,$count)=$xoopsDB->fetchRow($result)){
	  $counter[$kinds_sn]=$count;
	}

	$sql = "select * from ".$xoopsDB->prefix("ck2_stores_commodity_kinds")." where of_kinds_sn='{$show_of_cate_sn}' and enable='1' order by kind_sort";

	$result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());

	$function_title=($show_function)?"<th>"._BP_FUNCTION."</th>":"";

	$prefix=str_repeat("--",$i);
  $i++;

	$data="";
	while($all=$xoopsDB->fetchArray($result)){
	  //以下會產生這些變數： $kinds_sn , $of_kinds_sn , $stores_sn , $kind_title , $kind_desc , $kind_sort , $kind_counter , $enable
    foreach($all as $k=>$v){
      $$k=$v;
    }

		$kind_counter=(empty($counter[$kinds_sn]))?0:$counter[$kinds_sn];
		$data.="<tr>
		<td>$prefix<a href='".XOOPS_URL."/modules/ck2_cart/index.php?kinds_sn=$kinds_sn'>{$kind_title}</a>({$kind_counter})</td>
		</tr>";

		$data.=list_ck2_stores_commodity_kinds_loop($show_function,$kinds_sn,$i);
	}

	if($show_of_cate_sn!='0'){
		return $data;
	}else{
		$main="
		<table>
		$data
		</table>";

	}

	return $main;
}

?>
