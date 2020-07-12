<?php
//  ------------------------------------------------------------------------ //
// 本模組由 tad 製作
// 製作日期：2008-12-02
// $Id:$
// ------------------------------------------------------------------------- //

//區塊主函式 (推薦商品(ck2_cart_focus))
function ck2_cart_focus($options){
	$block="";
	return $block;
}

//區塊編輯函式
function ck2_cart_focus_edit($options){
	$seled0_0=($options[0]=="1,2,3")?"selected":"";
	$chked3_0=($options[3]=="1,0")?"checked":"";

	$form="
	"._MB_CK2CART_CK2_CART_FOCUS_EDIT_BITEM0."
	<select name='options[0]'>
		<option $seled0_0 value='1,2,3'>1,2,3</option>
	</select>
	"._MB_CK2CART_CK2_CART_FOCUS_EDIT_BITEM1."
	<INPUT type='text' name='options[1]' value='{$options[1]}'>
	"._MB_CK2CART_CK2_CART_FOCUS_EDIT_BITEM2."
	<INPUT type='text' name='options[2]' value='{$options[2]}'>
	"._MB_CK2CART_CK2_CART_FOCUS_EDIT_BITEM3."
	<INPUT type='radio' $chked3_0 name='options[3]' value='1,0'>1,0
	";
	return $form;
}

?>