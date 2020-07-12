<?php
//  ------------------------------------------------------------------------ //
// 本模組由 tad 製作
// 製作日期：2008-12-02
// $Id:$
// ------------------------------------------------------------------------- //

//區塊主函式 (最新上架商品(ck2_cart_new))
function ck2_cart_new($options){
	global $xoopsDB,$xoopsModule;
	$block="
		<style>
		.ck2_cart_new_price{
			color: rgb(0,102,255);
			font-weight: bold;
		}

		.ck2_cart_new_sprice{
			color:rgb(255,51,153);
			font-weight: bold;
		}

		</style>";

	$sql = "select a.*,b.filename,d.stores_sn from ".$xoopsDB->prefix("ck2_stores_commodity")." as a left join ".$xoopsDB->prefix("ck2_stores_image_center")." as b on b.col_name='commodity_sn' and b.col_sn=a.commodity_sn left join ".$xoopsDB->prefix("ck2_stores_commodity_kinds")." as c on a.kinds_sn=c.kinds_sn left join ".$xoopsDB->prefix("ck2_stores")." as d on c.stores_sn=d.stores_sn  where a.enable='1' order by a.com_post_date desc ,a.commodity_sn desc limit 0,{$options[0]}";
	$result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());
	while($all=$xoopsDB->fetchArray($result)){
	  //以下會產生這些變數： $commodity_sn,$kinds_sn,$brand_sn,$com_title,$com_summary,$com_content,$com_price,$com_sprice,$com_sprice_end_date,$com_unit,$com_post_date,$com_counter,$enable
    foreach($all as $k=>$v){
      $$k=$v;
    }
    
    if($options[1]=='1'){
    	$pic=(empty($filename))?XOOPS_URL."/modules/ck2_cart/images/no_commodities_thumb_pic.png":_CK2CART_UPLOAD_URL."/{$stores_sn}/commodity/thumb/{$commodity_sn}_{$filename}";
    	$com_pic="<a href='".XOOPS_URL."/modules/ck2_cart/commodity.php?commodity_sn=$commodity_sn'>
			<div style='border:1px solid #CFCFCF;width:100px;height:100px;overflow:hidden;margin:2px auto;background-image:url({$pic});background-repeat: no-repeat;background-position: center center;cursor:pointer;text-align:center;'> </div>
			</a>";
			$css="width:120px;text-align:center;float:left;";
			$css2="text-align:center;";
			$dot="";
    }else{
			$com_pic=$css=$css2="";
			$dot="<img src='".XOOPS_URL."/modules/ck2_cart/images/checkout3-green.gif' align='absmiddle' style='margin:0px 3px 0px 0px;'>";
		}

		$price=get_price($commodity_sn);

		$block.="
		<div style='{$css}font-size:12px;'>
			$com_pic

			<div style='margin:2px;'>$dot<a href='".XOOPS_URL."/modules/ck2_cart/commodity.php?commodity_sn=$commodity_sn' style='text-decoration:none;color:#336699'>{$com_title}</a></div>

			<div style='margin:2px;margin-bottom:10px;{$css2}'>{$price}</div>

		</div>

		";
  }
  
  $block.="<div style='clear:both;'></div>";
	return $block;
}

//區塊編輯函式
function ck2_cart_new_edit($options){
	$chked1_1=($options[1]=="1")?"checked":"";
	$chked1_0=($options[1]=="0")?"checked":"";

	$form="
	"._MB_CK2CART_CK2_CART_NEW_EDIT_BITEM0."
	<INPUT type='text' name='options[0]' value='{$options[0]}'><br>
	"._MB_CK2CART_CK2_CART_NEW_EDIT_BITEM1."
	<INPUT type='radio' $chked1_1 name='options[1]' value='1'>"._MB_CK2CART_YES."
	<INPUT type='radio' $chked1_0 name='options[1]' value='0'>"._MB_CK2CART_NO."
	";
	return $form;
}


if(!function_exists('get_price')){
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
	      $price=(empty($specification_sprice) or $now > $end_date)?_MB_CK2CART_COM_PRICE._MB_CK2CART_FOR."<span class='ck2_cart_new_price'>{$specification_price}</span> "._MB_CK2CART_MONEY."":_MB_CK2CART_COM_SPRICE._MB_CK2CART_FOR."<span class='ck2_cart_new_sprice'>{$specification_sprice}</span> "._MB_CK2CART_MONEY."";
			}else{
				$price=(empty($specification_sprice) or $now > $end_date)?_MB_CK2CART_COM_PRICE._MB_CK2CART_FOR."<span class='ck2_cart_new_price'>{$specification_price}</span> "._MB_CK2CART_MONEY."":_MB_CK2CART_COM_PRICE._MB_CK2CART_FOR."<span style='text-decoration:line-through;' >{$specification_price}</span>"._MB_CK2CART_MONEY."<br>"._MB_CK2CART_COM_SPRICE._MB_CK2CART_FOR."<span class='ck2_cart_new_sprice'>{$specification_sprice}</span> "._MB_CK2CART_MONEY."";
			}
		}

		return $price;

	}
}
?>
