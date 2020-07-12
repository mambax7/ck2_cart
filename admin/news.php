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
//
function f1(){
	global $xoopsDB;
	$main="Hi~ admin~";
  return $main;
}

//
function f2(){
	$main="";
	return $main;
}


/*-----------執行動作判斷區----------*/
$op = (!isset($_REQUEST['op']))? "main":$_REQUEST['op'];

switch($op){
	case "f2":
	f2();
	header("location: {$_SERVER['PHP_SELF']}");
	break;

	default:
	$main=f1();
	break;
}

/*-----------秀出結果區--------------*/
xoops_cp_header();
echo "<link rel='stylesheet' type='text/css' media='screen' href='../module.css' />";
echo menu_interface();
echo $main;
xoops_cp_footer();

?>