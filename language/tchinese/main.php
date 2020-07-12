<?php
//  ------------------------------------------------------------------------ //
// 本模組由 tad 製作
// 製作日期：2008-12-02
// $Id:$
// ------------------------------------------------------------------------- //

define("_TO_ADMIN_PAGE","管理介面");
//分頁物件用的語系
define("_MD_HOMEPAGE","回首頁");
define("_BP_BACK_PAGE","上一頁");
define("_BP_NEXT_PAGE","下一頁");
define("_BP_FIRST_PAGE","第一頁");
define("_BP_LAST_PAGE","最後頁");
define("_BP_GO_BACK_PAGE","前 %s 頁");
define("_BP_GO_NEXT_PAGE","後 %s 頁");
define("_BP_TOOLBAR","共 %s 頁，目前在第 %s 頁：");
define("_BP_DEL_CHK","確定要刪除此資料？");
define("_BP_FUNCTION","功能");
define("_BP_EDIT","編輯");
define("_BP_DEL","刪除");
define("_BP_ADD","新增資料");
define("_BP_FOR","：");
define("_MD_INPUT_VALID","「%s」欄位檢查");
define("_MD_INPUT_VALIDATOR","請輸入「%s」欄位");
define("_MD_INPUT_VALIDATOR_ERROR","「%s」資料不正確");
define("_MD_INPUT_VALIDATOR_CHK","最少 %s 個字，最多 %s 個字");
define("_MD_INPUT_VALIDATOR_MIN","最少 %s 個字");
define("_MD_INPUT_VALIDATOR_MAX","最多 %s 個字");
define("_MD_INPUT_VALIDATOR_EQUAL","限定 %s 個字");
define("_MD_INPUT_VALIDATOR_NEED","不可空白");
define("_MD_INPUT_VALIDATOR_RANGE","範圍： %s ～ %s");
define("_MD_INPUT_VALIDATOR_RANGE_MIN","最小： %s");
define("_MD_INPUT_VALIDATOR_RANGE_MAX","最大： %s");

define("_MD_CK2CART_SMNAME1", "線上購物車");
define("_MD_CK2CART_SMNAME2", "我的購物車");
define("_MD_CK2CART_SMNAME3", "我的訂購紀錄");


/**** index.php*******/
define("_MD_CK2CART_FOR","：");
define("_MD_CK2CART_STORE_MASTER","店長");
define("_MD_CK2CART_STORE_VISITER","從 %s 開幕至今，已有 %s 人次來訪");
define("_MD_CK2CART_MONEY","元");
define("_MD_CK2CART_COM_PRICE","定價");
define("_MD_CK2CART_COM_SPRICE","特價");
define("_MD_CK2CART_COM_SPRICE_END_DATE","特價結束日");
define("_MD_CK2CART_NEW_COM","最新商品");
define("_MD_CK2CART_RAND_COM","隨機商品");
define("_MD_CK2CART_ALL_COM","所有商品");
define("_MD_CK2CART_KIND_ALL_COM","「%s」中所有商品");
define("_MD_CK2CART_COM_POST_DATE","上架日期");
define("_MD_CK2CART_COM_DETAIL","本商品詳細介紹");


/**** commodity.php*******/
define("_MD_CK2CART_MY_CART","我的購物車");
define("_MD_CK2CART_AMOUNT","訂購數量");
define("_MD_CK2CART_NOW_AMOUNT","現有數量");
define("_MD_CK2CART_PRICE","金額");
define("_MD_CK2CART_PAYMENT","付款方式");
define("_MD_CK2CART_SHIPPING","配送方式");
define("_MD_CK2CART_SHIPPING_PAY","運費");
define("_MD_CK2CART_COM_NAME","品名");
define("_MD_CK2CART_SHIPPING_DESC","配送描述");
define("_MD_CK2CART_EMPTY_AMOUNT","已售完，補貨中");
define("_MA_CK2CART_COMMODITY_PAGE","商品資訊");
define("_MA_CK2CART_ADD_COMMODITY_PAGE","新增商品");
define("_MA_CK2CART_ADD_KIND_COMMODITY_PAGE","在「%s」中新增商品");


/**** order.php*******/
define("_MA_CK2CART_ORDER_PAGE","結帳");
define("_MA_CK2CART_CUSTOMER_SN","顧客編號");
define("_MA_CK2CART_CUSTOMER_NAME","姓名");
define("_MA_CK2CART_CUSTOMER_EMAIL","Email");
define("_MA_CK2CART_CUSTOMER_TEL","聯絡電話");
define("_MA_CK2CART_CUSTOMER_ZIP","（郵遞區號）");
define("_MA_CK2CART_CUSTOMER_CITY","縣市");
define("_MA_CK2CART_CUSTOMER_TOWN","鄉鎮市");
define("_MA_CK2CART_CUSTOMER_ADDR","地址");
define("_MA_CK2CART_UID","uid");
define("_MA_CK2CART_CUSTOMER_FORM","寄件資訊");
define("_MD_CK2CART_CART_EMPTY","尚無商品");
define("_MA_CK2CART_LOGIN_FORM","您尚未登入，請先登入");
define("_MD_CK2CART_CART_SUBMIT","下一步");
define("_MD_CK2CART_SUM","小計");
define("_MD_CK2CART_TOTAL","總計");
define("_MA_CK2CART_CUSTOMER_ORDER_NOTE","補充備註");
define("_MD_CK2CART_ID","帳號");
define("_MD_CK2CART_PASSWD","密碼");
define("_MD_CK2CART_LOGIN","登入");
define("_MD_CK2CART_REMEMBER","記住我");
define("_MA_CK2CART_ORDER_MAIL_TITLE","【%s】訂單編號「%s」訂購成功通知");
define("_MA_CK2CART_ORDER_MAIL_CONTENT","<table width='640' align='center' bgcolor='#FFFFCC'>
	<tr><th bgcolor='#FF9900'>《%s》訂單通知信</th></tr>
	<tr><td>
	<p>親愛的 %s 先生/小姐 您好：</p>
	<p>已經收到您的訂購資訊，感謝您訂購《%s》的優質產品！<br>
	本通知函只是通知您本系統已經收到您的訂購訊息、並供您再次自行核對之用，不代表交易已經完成。</p>

	您的訂單資訊如下，請核對是否正確：</td></tr>
	<tr><td>%s</td></tr>
	<tr><th bgcolor='#FF9900'>付款方式</th></tr>
	<tr><td>底下是您可以使用的付款方式，付款後，記得到 <a href='".XOOPS_URL."/modules/ck2_cart/pay.php?order_sn=%s'>".XOOPS_URL."/modules/ck2_cart/pay.php?order_sn=%s</a> 填寫已付款通知，收到確認後，我們會盡快寄出您所訂購的商品。</td></tr>
	<tr><td>%s</td></tr>
	<tr><td align='right'>《<a href='".XOOPS_URL."/modules/ck2_cart'>%s</a>》敬上</td></tr>
	</table>");

define("_MA_CK2CART_ORDER_MAIL_TITLE2","【%s】訂單編號「%s」已繳費通知");
define("_MA_CK2CART_ORDER_MAIL_CONTENT2","<table width='640' align='center' bgcolor='#FFFFCC'>
	<tr><th bgcolor='#FF9900'>《%s》已繳費通知信</th></tr>
	<tr><td>
	<p>親愛的 %s 先生/小姐 您好：</p>
	<p>系統已將您的繳費資訊送出，經確認後，我們會立即為您寄出您所訂購的商品。<br>若您想查詢商品的處理進度您可以從底下連結來查詢之：
	<p><a href='".XOOPS_URL."/modules/ck2_cart/pay.php?order_sn=%s'>".XOOPS_URL."/modules/ck2_cart/pay.php?order_sn=%s</a> </p></td></tr>
	<tr><td align='right'>《<a href='".XOOPS_URL."/modules/ck2_cart'>%s</a>》敬上</td></tr>
	</table>");

/**** pay.php*******/
define("_MD_CK2CART_PAY_NOTIFY","已付款通知");
define("_MD_CK2CART_ORDER_DATE","訂購日期");
define("_MD_CK2CART_ORDER_SPEC_SN","編號");
define("_MD_CK2CART_MY_ORDER","「%s」號訂單內容");
define("_MD_CK2CART_BANK_NAME","銀行名稱");
define("_MD_CK2CART_BANK_NUM","帳號末五碼");
define("_MD_CK2CART_BANK_PAY_TIME","轉帳時間");
define("_MD_CK2CART_BANK_PAY_MONEY","轉帳金額");
define("_MD_CK2CART_BANK_PAY_OK","謝謝您，查核後，我們將儘快為您寄出訂購的商品。");
define("_MD_CK2CART_ORDER_STATUS","目前訂單處理狀態：");
define("_MD_CK2CART_PAY_NOTIFY_SUBMIT","送出已付款通知");
define("_MD_CK2CART_ORDER_STATUS_WAIT","尚待處理");
define("_MD_CK2CART_ORDER_STATUS_OK","已寄出");
define("_MD_CK2CART_ORDER_PAY_OK","已於 %s 付款完畢");


/**** history.php*******/
define("_MD_CK2CART_MY_ORDER_LIST","我的訂購紀錄");
define("_MD_CK2CART_MY_ORDER","「%s」號訂單");
define("_MD_CK2CART_ORDER_PAY","填寫「%s」號訂單已付款通知");
define("_MD_CK2CART_ORDER_SN","訂單編號");
define("_MD_CK2CART_MY_ORDER_ITEM","訂單內容");
define("_MD_CK2CART_ORDER_STATUS_NOW","訂單處理狀態");
?>
