<?php
//  ------------------------------------------------------------------------ //
// 本模組由 tad 製作
// 製作日期：2008-12-02
// $Id:$
// ------------------------------------------------------------------------- //

include "../../mainfile.php";
include "function.php";

$interface_menu[_MD_CK2CART_SMNAME1]="index.php";
$interface_menu[_MD_CK2CART_SMNAME2]="order.php";
if($xoopsUser){
  $interface_menu[_MD_CK2CART_SMNAME3]="history.php";
}

$sql = "select * from ".$xoopsDB->prefix("ck2_stores_contents")." where in_menu='1'";
$result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());
$data="";
while($all=$xoopsDB->fetchArray($result)){
  //以下會產生這些變數： $page_sn , $page_title , $page_content , $in_menu , $post_date
  foreach($all as $k=>$v){
    $$k=$v;
  }
	$interface_menu[$page_title]="shop_content.php?page_sn=$page_sn";
}

?>
