<?php
//  ------------------------------------------------------------------------ //
// 本模組由 tad 製作
// 製作日期：2008-12-02
// $Id:$
// ------------------------------------------------------------------------- //

/*-----------引入檔案區--------------*/
include "header.php";
$xoopsOption['template_main'] = "order_tpl.html";
if(!empty($_POST['amount']) and $_REQUEST['op']!='save_order')save_cookie();
/*-----------function區--------------*/


function save_cookie(){
	foreach($_POST['amount'] as $specification_sn => $amount){
	  setcookie("specification_sn[$specification_sn]", $amount, time()+3600,"/");
	}
	header("location:order.php");
}

//訂購確認表
function save_order_chk(){
	global $xoopsUser;
  $block_content="";
	$js_total="";
	$total="";
	foreach($_COOKIE['specification_sn'] as $specification_sn => $amount){

	  $commodity=get_ck2_com_from_spec($specification_sn);
	  
	  $commodity_sn=$commodity['commodity_sn'];
	  
	  $stores_sn=get_store_from_commodity_sn($commodity_sn);

		$now=time();
		$end_date=strtotime($commodity['specification_sprice_end_date']);
		
		$show_sprice=(!empty($commodity['specification_sprice']) and ($now < $end_date))?true:false;

		$price=($show_sprice)?$commodity['specification_sprice']:$commodity['specification_price'];
		
    $show_price=($show_sprice)?_MD_CK2CART_COM_PRICE._MD_CK2CART_FOR."<span style='text-decoration:line-through;' >{$commodity['specification_price']}</span> "._MD_CK2CART_MONEY." "._MD_CK2CART_COM_SPRICE._MD_CK2CART_FOR."<span class='sprice'>{$commodity['specification_sprice']}</span> "._MD_CK2CART_MONEY:_MD_CK2CART_COM_PRICE._MD_CK2CART_FOR."<span class='price'>{$commodity['specification_price']}</span> "._MD_CK2CART_MONEY."";
	  
	  $sum=$amount * $price;
	  $total+=$sum;
	  
	  $pic=(empty($commodity['filename']))?XOOPS_URL."/modules/ck2_cart/images/no_commodities_thumb_pic.png":_CK2CART_UPLOAD_URL."/{$commodity['stores_sn']}/commodity/thumb/{$commodity_sn}_{$commodity['filename']}";
	  
	  
    $block_content.="
		<tr>
    <td><div style='width:100px;margin-right:10px;'>
			<a href='".XOOPS_URL."/modules/ck2_cart/commodity.php?commodity_sn={$commodity_sn}'>
				<div style=\"border:1px solid #CFCFCF;width:100px;height:100px;overflow:hidden;margin:2px auto;background-image:url({$pic});background-repeat: no-repeat;background-position: center center;cursor:pointer;text-align:center;\"> </div>
			</a>
		</div></td>
		<td><div style='font-size:11pt;'><a href='".XOOPS_URL."/modules/ck2_cart/commodity.php?commodity_sn={$commodity_sn}'>{$commodity['specification_title']}</a>{$show_price}</div><div style='color:#909090'>{$commodity['com_summary']}</div></td>
		<td nowrap>$price "._MD_CK2CART_MONEY."</td>
		<td nowrap>x <select name='amount[$specification_sn]' onChange=\"change_val(this.value,$price,'sum_{$specification_sn}')\">".amount2option($commodity['specification_amount'],$amount)."</select> {$commodity['com_unit']}</td>
		<td align='right' nowrap><input type='text' name='sum[$specification_sn]' id='sum_{$specification_sn}' value='{$sum}' size=5 readonly style='text-align:right;border:0px;background-color: transparent;'> "._MD_CK2CART_MONEY."</td>
		</tr>";
		
		$js_total.="(document.getElementById('sum_{$specification_sn}').value * 1) + ";
		
		$new_shipping_array=explode(",",$commodity['shipping']);
		if(!empty($old_shipping_array)){
      $old_shipping_array=array_intersect($new_shipping_array, $old_shipping_array);
		}else{
      $old_shipping_array=$new_shipping_array;
		}
	}

	$hidden_total=$total;
	$total+=$_COOKIE['shipping_pay'];
	
	
	$js_total=substr($js_total,0,-3);
	
	$shipping=implode(",",$old_shipping_array);
	
	//選擇貨運
	if(!empty($shipping)){
		$select_shipping=select_shipping($shipping);
	}
	
	//顧客地址表單
	if($xoopsUser){
    $customer_form=ck2_stores_customer_form();
    $submit="<p align='center'><input type='submit' value='"._MD_CK2CART_CART_SUBMIT."'></p></form>";
	}else{
    $customer_form=ck2_stores_login_form();
    $submit="";
	}
	

	if(empty($block_content)){
    $main=_MD_CK2CART_CART_EMPTY;
	}else{
		$main="

		<script type='text/javascript' src='http://www.google.com/jsapi'></script>
	<script type='text/javascript' language='javascript'> google.load('jquery', '1.3'); </script>
		<script>
		function change_val(amount,price,name){
			var sum=amount*price;
			document.getElementById(name).value=sum;
			document.getElementById('total').value=$js_total + (document.getElementById('hidden_shipping').value)*1;
			document.getElementById('hidden_total').value=$js_total;
		}
		
		function shipping_val(price){
			document.getElementById('hidden_shipping').value=price;
			document.getElementById('total').value = (document.getElementById('hidden_total').value)*1 + (document.getElementById('hidden_shipping').value)*1;
		}
		</script>";


		$data="
			<form action='".XOOPS_URL."/modules/ck2_cart/order.php' method='post' name='myForm' id='myForm'>
			<table id='tbl' style='border:1px solid gray;'>
			<tr>
			<th colspan=2>"._MD_CK2CART_COM_NAME."</th>
			<th nowrap>"._MD_CK2CART_PRICE."</th>
			<th nowrap>"._MD_CK2CART_AMOUNT."</th>
			<th nowrap>"._MD_CK2CART_SUM."</th>
			</tr>
			$block_content
			<tr>
			<th>"._MD_CK2CART_SHIPPING."</th>
			<th colspan=3>"._MD_CK2CART_SHIPPING_DESC."</td>
			<th>"._MD_CK2CART_SHIPPING_PAY."</th>
			</tr>
			$select_shipping
			<tr><td colspan=5>$customer_form</td></tr>
			<tr style='background-color:#F0F0F0'>
			<td colspan=4 style='font-size:11pt;color:red;'>"._MD_CK2CART_TOTAL."</td>
			<td align='right'>
			<input type='hidden' name='hidden_total' id='hidden_total' value='{$hidden_total}'>
			<input type='hidden' name='hidden_shipping' id='hidden_shipping' value='{$_COOKIE['shipping_pay']}'>
			<input type='text' name='total' id='total' value='{$total}' size=5 readonly style='text-align:right;border:0px;background-color: transparent;'> "._MD_CK2CART_MONEY."</td>
			</tr>

			</table>
			<input type='hidden' name='op' value='save_order'>
			<input type='hidden' name='stores_sn' value='{$stores_sn}'>
			$submit
		";
		

		$main.=gray_border(_MA_CK2CART_ORDER_PAGE,$data);
	}
	return $main;
}


//從商品編號取得商店編號
function get_store_from_commodity_sn($commodity_sn=""){
	global $xoopsDB,$xoopsUser;
	$sql = "select b.stores_sn from ".$xoopsDB->prefix("ck2_stores_commodity")." as a
	left join ".$xoopsDB->prefix("ck2_stores_commodity_kinds")." as b on a.kinds_sn=b.kinds_sn
	where a.commodity_sn='$commodity_sn'";
	$result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, $sql);
	list($stores_sn)=$xoopsDB->fetchRow($result);
	return $stores_sn;
}

//選擇運送方式
function select_shipping($shipping=""){
	global $xoopsDB,$xoopsUser;
	$main="";
	$sql = "select * from ".$xoopsDB->prefix("ck2_stores_shipping")." where shipping_sn in ($shipping) and enable='1'";
	$result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, $sql);
	while($all=$xoopsDB->fetchArray($result)){
	  //以下會產生這些變數： $shipping_sn,$stores_sn,$shipping_name,$shipping_desc,$shipping_pay,$enable
    foreach($all as $k=>$v){
      $$k=$v;
    }
    $main.="<tr>
		<td><input type='radio' name='shipping_sn' value='$shipping_sn' onClick=\"shipping_val($shipping_pay);setCookie('shipping',this.value,3600);setCookie('shipping_pay',$shipping_pay,3600);\" ".chk($shipping_sn,$_COOKIE['shipping']).">$shipping_name</td>
		<td colspan=3>".strip_tags($shipping_desc)."</td>
		<td align='right'>".$shipping_pay._MD_CK2CART_MONEY."</td>
		</tr>";
	}
	return $main;
}



//ck2_stores_customer編輯表單
function ck2_stores_customer_form(){
	global $xoopsDB,$xoopsUser;

	if($xoopsUser){
		$uid=$xoopsUser->getVar('uid');
	}else{
		$uid="";
	}

	//抓取預設值
	if(!empty($uid)){
		$DBV=get_ck2_stores_customer($uid);
	}else{
		$DBV=array();
	}

	//預設值設定



	//設定「customer_name」欄位預設值
	$customer_name=(!isset($DBV['customer_name']))?"":$DBV['customer_name'];

	//設定「customer_email」欄位預設值
	$customer_email=(!isset($DBV['customer_email']))?"":$DBV['customer_email'];

	//設定「customer_tel」欄位預設值
	$customer_tel=(!isset($DBV['customer_tel']))?"":$DBV['customer_tel'];

	//設定「customer_zip」欄位預設值
	$customer_zip=(!isset($DBV['customer_zip']))?"":$DBV['customer_zip'];

	//設定「customer_city」欄位預設值
	$customer_city=(!isset($DBV['customer_city']))?"":$DBV['customer_city'];

	//設定「customer_town」欄位預設值
	$customer_town=(!isset($DBV['customer_town']))?"":$DBV['customer_town'];

	//設定「customer_addr」欄位預設值
	$customer_addr=(!isset($DBV['customer_addr']))?"":$DBV['customer_addr'];

	//設定「uid」欄位預設值
	$uid=(!isset($DBV['uid']))?"":$DBV['uid'];

	$op=(empty($uid))?"insert_ck2_stores_customer":"update_ck2_stores_customer";
	//$op="replace_ck2_stores_customer";

	$main="
	<link type='text/css' rel='stylesheet' href='".XOOPS_URL."/modules/ck2_cart/class/formValidator/style/validator.css'>
	<script src='".XOOPS_URL."/modules/ck2_cart/class/formValidator/jquery_last.js' type='text/javascript'></script>
	<script src='".XOOPS_URL."/modules/ck2_cart/class/formValidator/formValidator.js' type='text/javascript' charset='UTF-8'></script>
	<script src='".XOOPS_URL."/modules/ck2_cart/class/formValidator/formValidatorRegex.js' type='text/javascript' charset='UTF-8'></script>
	<script src='".XOOPS_URL."/modules/ck2_cart/class/formValidator/DateTimeMask.js' language='javascript' type='text/javascript'></script>
	
	
	<script language='javascript' src='".XOOPS_URL."/modules/ck2_cart/class/city_menu/jquery.chainedSelects.js'></script>
	
	<script language='JavaScript' type='text/javascript'>
	$(function(){
		$('#city').chainSelect('#town','".XOOPS_URL."/modules/ck2_cart/class/city_menu/combobox.php',
		{
			before:function (target){
				$(target).css('display','none');
			},
			after:function (target){
				$(target).css('display','inline');
			}, defaultValue: '{$customer_town}'
		}).change();
		

	$.formValidator.initConfig({formid:'myForm',onerror:function(msg){alert(msg)}});



	//「姓名」欄位檢查
	$('#customer_name').formValidator({
		onshow:'".sprintf(_MD_INPUT_VALIDATOR,_MA_CK2CART_CUSTOMER_NAME)."',
		onfocus:'".sprintf(_MD_INPUT_VALIDATOR_CHK,'1','50')."',
		oncorrect:'OK!'
	}).inputValidator({
		min:1,
		max:50,
		onerror:'".sprintf(_MD_INPUT_VALIDATOR_ERROR,_MA_CK2CART_CUSTOMER_NAME)."'
	});

	//「Email」欄位檢查
	$('#customer_email').formValidator({
		onshow:'".sprintf(_MD_INPUT_VALIDATOR,_MA_CK2CART_CUSTOMER_EMAIL)."',
		onfocus:'".sprintf(_MD_INPUT_VALIDATOR_CHK,'1','50')."',
		oncorrect:'OK!'
	}).inputValidator({
		min:1,
		max:50,
		onerror:'".sprintf(_MD_INPUT_VALIDATOR_ERROR,_MA_CK2CART_CUSTOMER_EMAIL)."'
	});

	//「聯絡電話」欄位檢查
	$('#customer_tel').formValidator({
		onshow:'".sprintf(_MD_INPUT_VALIDATOR,_MA_CK2CART_CUSTOMER_TEL)."',
		onfocus:'".sprintf(_MD_INPUT_VALIDATOR_CHK,'1','20')."',
		oncorrect:'OK!'
	}).inputValidator({
		min:1,
		max:20,
		onerror:'".sprintf(_MD_INPUT_VALIDATOR_ERROR,_MA_CK2CART_CUSTOMER_TEL)."'
	});

	//「郵遞區號」欄位檢查
	$('#customer_zip').formValidator({
		onshow:'".sprintf(_MD_INPUT_VALIDATOR,_MA_CK2CART_CUSTOMER_ZIP)."',
		onfocus:'".sprintf(_MD_INPUT_VALIDATOR_CHK,'1','5')."',
		oncorrect:'OK!'
	}).inputValidator({
		min:1,
		max:5,
		onerror:'".sprintf(_MD_INPUT_VALIDATOR_ERROR,_MA_CK2CART_CUSTOMER_ZIP)."'
	});

	//「縣市」欄位檢查
	$('#customer_city').formValidator({
		onshow:'".sprintf(_MD_INPUT_VALIDATOR,_MA_CK2CART_CUSTOMER_CITY)."',
		onfocus:'".sprintf(_MD_INPUT_VALIDATOR_EQUAL,'1')."',
		oncorrect:'OK!'
	}).inputValidator({
		min:1,
		max:1,
		onerror:'".sprintf(_MD_INPUT_VALIDATOR_ERROR,_MA_CK2CART_CUSTOMER_CITY)."'
	});

	//「鄉鎮市」欄位檢查
	$('#customer_town').formValidator({
		onshow:'".sprintf(_MD_INPUT_VALIDATOR,_MA_CK2CART_CUSTOMER_TOWN)."',
		onfocus:'".sprintf(_MD_INPUT_VALIDATOR_EQUAL,'1')."',
		oncorrect:'OK!'
	}).inputValidator({
		min:1,
		max:1,
		onerror:'".sprintf(_MD_INPUT_VALIDATOR_ERROR,_MA_CK2CART_CUSTOMER_TOWN)."'
	});

	//「地址」欄位檢查
	$('#customer_addr').formValidator({
		onshow:'".sprintf(_MD_INPUT_VALIDATOR,_MA_CK2CART_CUSTOMER_ADDR)."',
		onfocus:'".sprintf(_MD_INPUT_VALIDATOR_CHK,'1','100')."',
		oncorrect:'OK!'
	}).inputValidator({
		min:1,
		max:100,
		onerror:'".sprintf(_MD_INPUT_VALIDATOR_ERROR,_MA_CK2CART_CUSTOMER_ADDR)."'
	});
	});
	
	function setCookie(c_name,value,expiredays)
	{
	var exdate=new Date();
	exdate.setDate(exdate.getDate()+expiredays);
	document.cookie=c_name+ '=' +escape(value)+
	((expiredays==null) ? '' : ';expires='+exdate.toGMTString());
	}
	</script>
	<table class='form_tbl' style='width:100%;'>
	<tr><th colspan=2>"._MA_CK2CART_CUSTOMER_FORM."</th></tr>

	<!--姓名-->
	<tr><td class='title' nowrap>"._MA_CK2CART_CUSTOMER_NAME."</td>
	<td class='col'><input type='text' name='customer_name' size='10' value='{$customer_name}' id='customer_name'></td></tr>

	<!--Email-->
	<tr><td class='title' nowrap>"._MA_CK2CART_CUSTOMER_EMAIL."</td>
	<td class='col'><input type='text' name='customer_email' size='20' value='{$customer_email}' id='customer_email'></td></tr>

	<!--聯絡電話-->
	<tr><td class='title' nowrap>"._MA_CK2CART_CUSTOMER_TEL."</td>
	<td class='col'><input type='text' name='customer_tel' size='10' value='{$customer_tel}' id='customer_tel'></td></tr>

	<!--地址-->
	<tr><td class='title' nowrap>"._MA_CK2CART_CUSTOMER_ADDR."</td>
	<td class='col'>"._MA_CK2CART_CUSTOMER_ZIP."
  <input type='text' name='customer_zip' size='3' value='{$customer_zip}' id='customer_zip'>
  ".mk_city_opt($customer_city,$customer_town)."
	<input type='text' name='customer_addr' size='30' value='{$customer_addr}' id='customer_addr'></td></tr>

	<!--備註-->
	<tr><td class='title' nowrap>"._MA_CK2CART_CUSTOMER_ORDER_NOTE."</td>
	<td class='col'><textarea name='order_note' style='width:100%;height:80px;'></textarea></td></tr>
	
	
	</table>

	<!--uid-->
	<input type='hidden' name='uid' value='{$uid}'>
";

	//raised,corners,inset
	//$main=div_3d(_MA_INPUT_FORM,$main,"raised");

	return $main;
}




//登入表單
function ck2_stores_login_form(){
	$main="
	</form>
	<form action='".XOOPS_URL."/user.php' method='post'>
	<table class='form_tbl' style='width:100%;'>
	<tr><th colspan=2>"._MA_CK2CART_LOGIN_FORM."</th></tr>
	<tr><td>
	"._MD_CK2CART_ID._BP_FOR."<input name='uname' size='12' value='' maxlength='25' type='text'>
	"._MD_CK2CART_PASSWD._BP_FOR."<input name='pass' size='12' maxlength='32' type='password'>
	<input name='rememberme' value='On' class='formButton' checked='checked' type='checkbox'>"._MD_CK2CART_REMEMBER."
	<input name='xoops_redirect' value='{$_SERVER['PHP_SELF']}' type='hidden'>
	<input name='op' value='login' type='hidden'>
	<input value='"._MD_CK2CART_LOGIN."' type='submit'><br></td></tr>
	</table></form>

	";
	return $main;
}

//縣市選單
function mk_city_opt($city="",$town=""){
	if(_CHARSET=="Big5"){
		include "language/tchinese/bank_name.php";
	}else{
		include "language/tchinese_utf8/bank_name.php";
	}

  $option="
	<select id='city' name='customer_city'><option value=''>"._MA_CK2CART_CUSTOMER_CITY."</option>";
	foreach($city_array as $name){
	  $selected=($city==$name)?"selected":"";
		$option.="<option value='$name' $selected>$name</option>";
	}
	$option.="</select>
	<select name='customer_town' id='town' style='display:none'></select>";
	return $option;
}



//新增資料到ck2_stores_order_commodity中
function save_order(){
	global $xoopsDB,$xoopsUser;

  $order_sn=save_ck2_stores_order();

	foreach($_POST['amount'] as $specification_sn => $amount){
		$sql = "insert into ".$xoopsDB->prefix("ck2_stores_order_commodity")."
		(`order_sn` , `specification_sn` , `amount` , `sum`)
		values('{$order_sn}' , '{$specification_sn}' , '{$amount}' , '{$_POST['sum'][$specification_sn]}')";
		$xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());
		
		//扣除商品數量
		$sql = "update ".$xoopsDB->prefix("ck2_stores_commodity_specification")." set `specification_amount`=`specification_amount` - $amount where specification_sn='$specification_sn'";
		$xoopsDB->queryF($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());
	}
	
	//清除cookie
	setcookie("shipping","",time() - 3600,"/");
	setcookie("shipping_pay","",time() - 3600,"/");
	foreach($_COOKIE['specification_sn'] as $specification_sn => $amount){
		setcookie("specification_sn[$specification_sn]","",time() - 3600,"/");
	}
	
	//寄發通知信

	$store=get_ck2_stores($_POST['stores_sn']);
	$ck2_stores_order=list_ck2_stores_order($order_sn);
	$sn=mk_order_sn($order_sn,date("Y-m-d"));
	$show_payment=show_payment($_POST['stores_sn']);
	$title=sprintf(_MA_CK2CART_ORDER_MAIL_TITLE,$store['store_title'],$sn);
	$content=sprintf(_MA_CK2CART_ORDER_MAIL_CONTENT,$store['store_title'],$_POST['customer_name'],$store['store_title'],$ck2_stores_order,$order_sn,$order_sn,$show_payment,$store['store_title']);
	send_now($store['store_email'],$title,$content);
	send_now($_POST['customer_email'],$title,$content);

	
	return $order_sn;
}

function show_payment($stores_sn=""){
	global $xoopsDB,$xoopsUser;
	$main="<table width='100%' bgcolor='#FFFFFF'>";
	//$payment_arr=explode(",",$payment);
	$sql = "select * from ".$xoopsDB->prefix("ck2_stores_payment")." where stores_sn ='$stores_sn' and enable='1'";
	$result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());
	while($all=$xoopsDB->fetchArray($result)){
	  //以下會產生這些變數： $payment_sn , $payment_name , $payment_desc , $enable
    foreach($all as $k=>$v){
      $$k=$v;
    }
    $main.="
		<tr><th bgcolor='#F0F0F0'>$payment_name</th></tr>
		<tr><td>$payment_desc</td></tr>";
	}
	return $main;
}

//列出所有ck2_stores_order_commodity資料
function list_ck2_stores_order($order_sn=""){
	global $xoopsDB,$xoopsUser,$xoopsModule,$bank_arr;

	$uid=$xoopsUser->getVar('uid');

	$sql = "select a.*,b.* from ".$xoopsDB->prefix("ck2_stores_order")." as a left join ".$xoopsDB->prefix("ck2_stores_customer")." as b on a.uid=b.uid where a.order_sn='$order_sn' and a.uid='$uid'";
	$result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());
	$all=$xoopsDB->fetchArray($result);
  //以下會產生這些變數： $order_sn , $uid , $order_date , $customer_addr_sn , $shipping_sn , $order_note ,$sum, $payment_sn , $bank_sn , $order_pay_date , $order_status,$uid,$customer_name,$customer_email,$customer_tel,$customer_zip,$customer_city,$customer_town,$customer_addr
  foreach($all as $k=>$v){
    $$k=$v;
  }

	$money=0;


	$MDIR=$xoopsModule->getVar('dirname');
	$sql = "select a.*,b.specification_title,b.commodity_sn from ".$xoopsDB->prefix("ck2_stores_order_commodity")." as a left join ".$xoopsDB->prefix("ck2_stores_commodity_specification")." as b on a.specification_sn=b.specification_sn where a.order_sn='$order_sn'";
	$result = $xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());


	$data="
	<table bgcolor='white' width='100%' border='0' style='border-bottom:1px solid #E0E0E0;border-right:1px solid #E0E0E0;'>
	<tr><th>"._MA_CK2CART_CUSTOMER_NAME."</th><td colspan=3>$customer_name</td></tr>
	<tr><th>"._MD_CK2CART_ORDER_DATE."</th><td colspan=3>$order_date</td></tr>
	<tr><th>"._MA_CK2CART_CUSTOMER_EMAIL."</th><td colspan=3>$customer_email</td></tr>
	<tr><th>"._MA_CK2CART_CUSTOMER_TEL."</th><td colspan=3>$customer_tel</td></tr>
	<tr><th>"._MA_CK2CART_CUSTOMER_ADDR."</th><td colspan=3>{$customer_zip} {$customer_city}{$customer_town}{$customer_addr}</td></tr>
	</table>
	<br>
	<table bgcolor='white' width='100%' border='1'>
	<tr><th>"._MD_CK2CART_ORDER_SPEC_SN."</th><th>"._MD_CK2CART_COM_NAME."</th><th>"._MD_CK2CART_AMOUNT."</th><th>"._MD_CK2CART_SUM."</th></tr>
	";
	while($all=$xoopsDB->fetchArray($result)){
	  //以下會產生這些變數： $order_sn , $specification_sn , $amount , $sum
    foreach($all as $k=>$v){
      $$k=$v;
    }

		$data.="<tr>
		<td>$specification_sn</td>
		<td><a href='".XOOPS_URL."/modules/ck2_cart/commodity.php?commodity_sn=$commodity_sn'>{$specification_title}</a></td>
		<td>{$amount}</td>
		<td style='text-align:right;'>{$sum} "._MD_CK2CART_MONEY."</td>
		$fun
		</tr>";

    $money+=$sum;
	}

  $shipping=get_ck2_stores_shipping($shipping_sn);
  $money+=$shipping['shipping_pay'];

	$data.="
	<tr><th>"._MD_CK2CART_SHIPPING."</th><td>{$shipping['shipping_name']}</td><th>"._MD_CK2CART_SHIPPING_PAY."</th><td align='right'>{$shipping['shipping_pay']} "._MD_CK2CART_MONEY."</td></tr>
	<tr><th>"._MA_CK2CART_CUSTOMER_ORDER_NOTE."</th><td>$order_note</td><th>"._MD_CK2CART_TOTAL."</th><td align='right'>$money "._MD_CK2CART_MONEY."</td></tr>
	</table>";


	return $data;
}


//新增資料到ck2_stores_order中
function save_ck2_stores_order(){
	global $xoopsDB,$xoopsUser;
	//儲存客戶資料
  $uid=insert_ck2_stores_customer();
  //儲存客戶地址資料
  $customer_addr_sn=insert_ck2_stores_customer_addr($uid);
  
	$sql = "insert into ".$xoopsDB->prefix("ck2_stores_order")."
	(`stores_sn` ,`uid` , `order_date` , `customer_addr_sn` , `shipping_sn` , `order_note` , `sum` , `payment_sn` , `bank_name` , `bank_num`  , `pay_money`  , `order_pay_date` , `order_status`)
	values('{$_POST['stores_sn']}','{$uid}' , now() , '{$customer_addr_sn}' , '{$_POST['shipping_sn']}' , '{$_POST['order_note']}' , '{$_POST['total']}', '{$_POST['payment_sn']}' , '{$_POST['bank_name']}' , '{$_POST['bank_num']}' , '{$_POST['pay_money']}' , '{$_POST['order_pay_date']}' , '')";
	$xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());

	//取得最後新增資料的流水編號
	$order_sn=$xoopsDB->getInsertId();
	return $order_sn;
}


//新增資料到ck2_stores_customer中
function insert_ck2_stores_customer(){
	global $xoopsDB,$xoopsUser;

	$uid=$xoopsUser->getVar('uid');

	$sql = "replace into ".$xoopsDB->prefix("ck2_stores_customer")."
	(`uid`,`customer_name` , `customer_email` , `customer_tel` , `customer_zip` , `customer_city` , `customer_town` , `customer_addr`)
	values('$uid','{$_POST['customer_name']}' , '{$_POST['customer_email']}' , '{$_POST['customer_tel']}' , '{$_POST['customer_zip']}' , '{$_POST['customer_city']}' , '{$_POST['customer_town']}' , '{$_POST['customer_addr']}')";
	
	$xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());
	return $uid;
}

//新增資料到ck2_stores_customer_addr中
function insert_ck2_stores_customer_addr($uid=""){
	global $xoopsDB,$xoopsUser;

  //$uid=$xoopsUser->getVar("uid");
	$sql = "insert into ".$xoopsDB->prefix("ck2_stores_customer_addr")."
	(`uid` , `customer_zip` , `customer_city` , `customer_town` , `customer_addr`)
	values('{$uid}' , '{$_POST['customer_zip']}' , '{$_POST['customer_city']}' , '{$_POST['customer_town']}' , '{$_POST['customer_addr']}')";
	$xoopsDB->query($sql) or redirect_header($_SERVER['PHP_SELF'],3, mysql_error());

	//取得最後新增資料的流水編號
	$customer_addr_sn=$xoopsDB->getInsertId();
	return $customer_addr_sn;
}


/*-----------執行動作判斷區----------*/
$op=(empty($_REQUEST['op']))?"":$_REQUEST['op'];
$order_sn=(empty($_REQUEST['order_sn']))?"":intval($_REQUEST['order_sn']);

switch($op){
	case "save_order":
	$order_sn=save_order();
	header("location: {$_SERVER['PHP_SELF']}?op=ok&order_sn=$order_sn");
	break;
	
	case "ok":
	header("location: pay.php?order_sn=$order_sn");
	break;

	default:
	$main=save_order_chk();
	break;
}

/*-----------秀出結果區--------------*/
include XOOPS_ROOT_PATH."/header.php";
$xoopsTpl->assign( "css" , "<link rel='stylesheet' type='text/css' media='screen' href='".XOOPS_URL."/modules/ck2_cart/module.css' />") ;
$xoopsTpl->assign( "toolbar" , toolbar($interface_menu)) ;
$xoopsTpl->assign( "content" , $main) ;
include_once XOOPS_ROOT_PATH.'/footer.php';

?>
