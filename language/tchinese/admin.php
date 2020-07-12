<?php
//  ------------------------------------------------------------------------ //
// 本模組由 tad 製作
// 製作日期：2008-12-02
// $Id:$
// ------------------------------------------------------------------------- //

define("_BACK_MODULES_PAGE","回模組首頁");
//分頁物件用的語系
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

define("_MA_INPUT_FORM","輸入表單");
define("_MA_SAVE","儲存");
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
define("_MI_CK2CART_ADMENU1", "訂單管理");
define("_MI_CK2CART_ADMENU2", "商店設定");
define("_MI_CK2CART_ADMENU3", "新聞管理");
define("_MI_CK2CART_ADMENU4", "商品管理");
define("_MI_CK2CART_ADMENU5", "配送方式管理");
define("_MI_CK2CART_ADMENU6", "付款方式管理");
define("_MI_CK2CART_ADMENU7", "品牌管理");
define("_MI_CK2CART_ADMENU8", "頁面管理");

/**** store.php*******/
define("_MA_CK2CART_STORES_INPUT_FORM","商店管理");
define("_MA_CK2CART_STORES_SN","商店編號");
define("_MA_CK2CART_STORE_TITLE","商店名稱");
define("_MA_CK2CART_STORE_IMAGE","商店Logo");
define("_MA_CK2CART_STORE_DESC","商店簡介");
define("_MA_CK2CART_STORE_COUNTER","人氣");
define("_MA_CK2CART_STORE_MASTER","店主姓名");
define("_MA_CK2CART_STORE_EMAIL","聯繫Email");
define("_MA_CK2CART_ENABLE","狀態");
define("_MA_CK2CART_UID","uid");
define("_MA_CK2CART_OPEN_DATE","開店日期");
define("_MA_CK2CART_STORES_ENABLE","開門營業");
define("_MA_CK2CART_STORES_UNABLE","關門整修");

/**** commodity.php*******/
define("_MA_CK2CART_COMMODITY_KIND_INPUT_FORM","商品分類管理");
define("_MA_CK2CART_COMMODITY_INPUT_FORM","商品管理");
define("_MA_CK2CART_KINDS_SN","商品分類");
define("_MA_CK2CART_OF_KINDS_SN","父分類");
define("_MA_CK2CART_STORES_SN","商店編號");
define("_MA_CK2CART_KIND_TITLE","商品分類名稱");
define("_MA_CK2CART_KIND_DESC","商品分類描述");
define("_MA_CK2CART_KIND_SORT","排序");
define("_MA_CK2CART_KIND_COUNTER","人氣");
define("_MA_CK2CART_COMMODITY_ENABLE","狀態");
define("_MA_CK2CART_COMMODITY_IS_ENABLE","使用中");
define("_MA_CK2CART_COMMODITY_IS_UNABLE","不使用");
define("_MA_CK2CART_ADD_COMMODITY","新增商品");
define("_MA_CK2CART_COMMODITY_SN","商品編號");
define("_MA_CK2CART_KINDS_SN","分類");
define("_MA_CK2CART_BRAND_SN","品牌");
define("_MA_CK2CART_COM_TITLE","商品名稱");
define("_MA_CK2CART_COM_IMAGE","商品圖片");
define("_MA_CK2CART_COM_SUMMARY","商品摘要");
define("_MA_CK2CART_COM_CONTENT","商品描述");
define("_MA_CK2CART_COM_PRICE","定價");
define("_MA_CK2CART_COM_SPRICE","特價");
define("_MA_CK2CART_COM_SPRICE_END_DATE","特價結束日");
define("_MA_CK2CART_COM_UNIT","商品單位");
define("_MA_CK2CART_COM_POST_DATE","上架日期");
define("_MA_CK2CART_COM_COUNTER","人氣");
define("_MA_CK2CART_COM_ENABLE","狀態");
define("_MA_CK2CART_COM_IS_ENABLE","上架");
define("_MA_CK2CART_COM_IS_UNABLE","下架");
define("_MA_CK2CART_PAYMENT","設定可用的付款方式");
define("_MA_CK2CART_SHIPPING","設定可用的運送方式");
define("_MA_CK2CART_SPECIFICATION","規格及數量");
define("_MA_CK2CART_SPECIFICATION_SN","商品規格");
define("_MA_CK2CART_COMMODITY_SN","所屬商品");
define("_MA_CK2CART_SPECIFICATION_TITLE","規格名稱（顏色、尺寸...等）");
define("_MA_CK2CART_SPECIFICATION_AMOUNT","數量");
define("_MA_CK2CART_SPECIFICATION_PRICE","價錢");
define("_MA_CK2CART_SPECIFICATION_SPRICE","特價");
define("_MA_CK2CART_SPECIFICATION_SPRICE_END_DATE","特價結束日期");
define("_MA_CK2CART_SPECIFICATION_ADD","新增一列");
define("_MA_CK2CART_COMMODITY_NUM","商品數量");
define("_MA_CK2CART_SALE_LOG","銷售紀錄");


/**** brand.php*******/
define("_MA_CK2CART_BRAND_INPUT_FORM","品牌管理");
define("_MA_CK2CART_BRAND_NAME","品牌名稱");
define("_MA_CK2CART_BRAND_DESC","品牌描述");
define("_MA_CK2CART_BRAND_URL","品牌網址");
define("_MA_CK2CART_BRAND_ENABLE","品牌狀態");
define("_MA_CK2CART_BRAND_IS_ENABLE","啟用");
define("_MA_CK2CART_BRAND_IS_UNABLE","關閉");
define("_MA_CK2CART_BRAND_IMAGE","品牌圖片");


/**** shipping.php*******/
define("_MA_SHIPPING_INPUT_FORM","配送設定");
define("_MA_CK2CART_SHIPPING_SN","配送編號");
define("_MA_CK2CART_SHIPPING_NAME","配送名稱");
define("_MA_CK2CART_SHIPPING_DESC","配送描述");
define("_MA_CK2CART_SHIPPING_PAY","配送費用");
define("_MA_CK2CART_SHIPPING_ENABLE","狀態");
define("_MA_CK2CART_SHIPPING_IS_ENABLE","啟用");
define("_MA_CK2CART_SHIPPING_IS_UNABLE","關閉");

/**** payment.php*******/
define("_MA_PAYMENT_INPUT_FORM","設定付款方式");
define("_MA_CK2CART_PAYMENT_SN","付款方式編號");
define("_MA_CK2CART_PAYMENT_NAME","付款方式");
define("_MA_CK2CART_PAYMENT_DESC","相關說明");
define("_MA_CK2CART_PAYMENT_ENABLE","狀態");
define("_MA_CK2CART_PAYMENT_IS_ENABLE","啟用");
define("_MA_CK2CART_PAYMENT_IS_UNABLE","關閉");

/**** mk_content.php*******/
define("_MA_CK2CART_PAGE_SN","頁面");
define("_MA_CK2CART_PAGE_TITLE","標題");
define("_MA_CK2CART_PAGE_CONTENT","內容");
define("_MA_CK2CART_IN_MENU","出現在工具列上？");
define("_MA_CK2CART_POST_DATE","最後修改日期");


/**** index.php*******/
define("_MA_CK2CART_ORDER_SN","訂單編號");
define("_MA_CK2CART_CUSTOMER_SN","顧客編號");
define("_MA_CK2CART_ORDER_DATE","訂購日期");
define("_MA_CK2CART_CUSTOMER_ADDR_SN","顧客地址編號");
define("_MA_CK2CART_SHIPPING_SN","運送方式");
define("_MA_CK2CART_ORDER_NOTE","備註事項");
define("_MA_CK2CART_PAYMENT_SN","付款方式");
define("_MA_CK2CART_BANK_SN","付款資訊");
define("_MA_CK2CART_ORDER_PAY_DATE","付款日期");
define("_MA_CK2CART_ORDER_STATUS","處理狀態");
define("_MA_CK2CART_TOTAL","總計");
define("_MA_CK2CART_ORDER_STATUS_WAIT","尚待處理");
define("_MA_CK2CART_ORDER_STATUS_OK","已寄出");

define("_MA_CK2CART_CUSTOMER_NAME","姓名");
define("_MD_CK2CART_MY_ORDER","「%s」號訂單內容");
define("_MD_CK2CART_ORDER_DATE","訂購日期");
define("_MA_CK2CART_CUSTOMER_EMAIL","Email");
define("_MA_CK2CART_CUSTOMER_TEL","聯絡電話");
define("_MA_CK2CART_CUSTOMER_ADDR","地址");
define("_MD_CK2CART_ORDER_SPEC_SN","編號");
define("_MD_CK2CART_MY_ORDER","「%s」號訂單內容");
define("_MD_CK2CART_BANK_NAME","銀行名稱");
define("_MD_CK2CART_BANK_NUM","帳號末五碼");
define("_MD_CK2CART_BANK_PAY_TIME","轉帳時間");
define("_MD_CK2CART_BANK_PAY_MONEY","轉帳金額");
define("_MD_CK2CART_COM_NAME","品名");
define("_MD_CK2CART_SHIPPING_DESC","配送描述");
define("_MD_CK2CART_AMOUNT","訂購數量");
define("_MD_CK2CART_SUM","小計");
define("_MD_CK2CART_TOTAL","總計");
define("_MD_CK2CART_SHIPPING","配送方式");
define("_MD_CK2CART_SHIPPING_PAY","運費");
define("_MA_CK2CART_CUSTOMER_ORDER_NOTE","補充備註");
define("_MD_CK2CART_PAY_NOTIFY","已付款通知");
define("_MD_CK2CART_PAY_NOT_YET","目前買家尚未付款");
define("_MD_CK2CART_STATUS","變更訂單處理狀態：");
define("_MD_CK2CART_STATUS_SUBMIT","儲存處理狀態變更");
define("_MA_CK2CART_ORDER_MAIL_TITLE3","【%s】訂單編號「%s」已寄出通知");
define("_MA_CK2CART_ORDER_MAIL_CONTENT3","<table width='640' align='center' bgcolor='#FFFFCC'>
	<tr><th bgcolor='#FF9900'>《%s》已寄出通知信</th></tr>
	<tr><td>
	<p>親愛的 %s 先生/小姐 您好：</p>
	<p>您訂購的商品已經寄出，商品會寄至「%s」。預計這幾天內就會寄到，請密切注意。</td></tr>
	<tr><td align='right'>《<a href='".XOOPS_URL."/modules/ck2_cart'>%s</a>》敬上</td></tr>
	</table>");
?>
