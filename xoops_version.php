<?php
//  ------------------------------------------------------------------------ //
// 本模組由 tad 製作
// 製作日期：2008-12-02
// $Id:$
// ------------------------------------------------------------------------- //

//---基本設定---//
//模組名稱
$modversion['name'] = _MI_CK2CART_NAME;
//模組版次
$modversion['version']	= '1.0';
//模組作者
$modversion['author'] = _MI_CK2CART_AUTHOR;
//模組說明
$modversion['description'] = _MI_CK2CART_DESC;
//模組授權者
$modversion['credits']	= _MI_CK2CART_CREDITS;
//模組版權
$modversion['license']		= "GPL see LICENSE";
//模組是否為官方發佈1，非官方2
$modversion['official']		= 2;
//模組圖示
$modversion['image']		= "images/logo.png";
//模組目錄名稱
$modversion['dirname']		= "ck2_cart";

//---資料表架構---//
$modversion['sqlfile']['mysql'] = "sql/mysql.sql";
$modversion['tables'][1] = "ck2_stores";
$modversion['tables'][2] = "ck2_stores_bank";
$modversion['tables'][3] = "ck2_stores_brand";
$modversion['tables'][4] = "ck2_stores_commodity";
$modversion['tables'][5] = "ck2_stores_commodity_kinds";
$modversion['tables'][6] = "ck2_stores_commodity_specification";
$modversion['tables'][7] = "ck2_stores_customer";
$modversion['tables'][8] = "ck2_stores_customer_addr";
$modversion['tables'][9] = "ck2_stores_image_center";
$modversion['tables'][10] = "ck2_stores_news";
$modversion['tables'][11] = "ck2_stores_order";
$modversion['tables'][12] = "ck2_stores_order_commodity";
$modversion['tables'][13] = "ck2_stores_payment";
$modversion['tables'][14] = "ck2_stores_shipping";
$modversion['tables'][15] = "ck2_stores_contents";

//---管理介面設定---//
$modversion['hasAdmin'] = 1;
$modversion['adminindex'] = "admin/index.php";
$modversion['adminmenu'] = "admin/menu.php";

//---使用者主選單設定---//
$modversion['hasMain'] = 1;
$modversion['sub'][2]['name'] =_MI_CK2CART_SMNAME2;
$modversion['sub'][2]['url'] = "order.php";
$modversion['sub'][3]['name'] =_MI_CK2CART_SMNAME3;
$modversion['sub'][3]['url'] = "history.php";


//---樣板設定---//

$modversion['templates'][1]['file'] = 'index_tpl.html';
$modversion['templates'][1]['description'] = _MI_CK2CART_TEMPLATE_DESC1;
$modversion['templates'][2]['file'] = 'order_tpl.html';
$modversion['templates'][2]['description'] = _MI_CK2CART_TEMPLATE_DESC2;
$modversion['templates'][3]['file'] = 'shop_content_tpl.html';
$modversion['templates'][3]['description'] = _MI_CK2CART_TEMPLATE_DESC3;
$modversion['templates'][4]['file'] = 'commodity_tpl.html';
$modversion['templates'][4]['description'] = _MI_CK2CART_TEMPLATE_DESC4;
$modversion['templates'][5]['file'] = 'shop_content_tpl.html';
$modversion['templates'][5]['description'] = _MI_CK2CART_TEMPLATE_DESC5;
$modversion['templates'][6]['file'] = 'pay_tpl.html';
$modversion['templates'][6]['description'] = _MI_CK2CART_TEMPLATE_DESC6;
$modversion['templates'][7]['file'] = 'history_tpl.html';
$modversion['templates'][7]['description'] = _MI_CK2CART_TEMPLATE_DESC7;

//---區塊設定---//
$modversion['blocks'][1]['file'] = "ck2_cart_new.php";
$modversion['blocks'][1]['name'] = _MI_CK2CART_BNAME1;
$modversion['blocks'][1]['description'] = _MI_CK2CART_BDESC1;
$modversion['blocks'][1]['show_func'] = "ck2_cart_new";
$modversion['blocks'][1]['template'] = "ck2_cart_new.html";
$modversion['blocks'][1]['edit_func'] = "ck2_cart_new_edit";
$modversion['blocks'][1]['options'] = "5|1";

$modversion['blocks'][2]['file'] = "ck2_cart_hot.php";
$modversion['blocks'][2]['name'] = _MI_CK2CART_BNAME2;
$modversion['blocks'][2]['description'] = _MI_CK2CART_BDESC2;
$modversion['blocks'][2]['show_func'] = "ck2_cart_hot";
$modversion['blocks'][2]['template'] = "ck2_cart_hot.html";
$modversion['blocks'][2]['edit_func'] = "ck2_cart_hot_edit";
$modversion['blocks'][2]['options'] = "5|1";

$modversion['blocks'][3]['file'] = "ck2_cart_kind.php";
$modversion['blocks'][3]['name'] = _MI_CK2CART_BNAME3;
$modversion['blocks'][3]['description'] = _MI_CK2CART_BDESC3;
$modversion['blocks'][3]['show_func'] = "ck2_cart_kind";
$modversion['blocks'][3]['template'] = "ck2_cart_kind.html";


$modversion['blocks'][4]['file'] = "ck2_cart_rand.php";
$modversion['blocks'][4]['name'] = _MI_CK2CART_BNAME4;
$modversion['blocks'][4]['description'] = _MI_CK2CART_BDESC4;
$modversion['blocks'][4]['show_func'] = "ck2_cart_rand";
$modversion['blocks'][4]['template'] = "ck2_cart_rand.html";
$modversion['blocks'][4]['edit_func'] = "ck2_cart_rand_edit";
$modversion['blocks'][4]['options'] = "5|1";

$modversion['blocks'][5]['file'] = "ck2_cart_focus.php";
$modversion['blocks'][5]['name'] = _MI_CK2CART_BNAME5;
$modversion['blocks'][5]['description'] = _MI_CK2CART_BDESC5;
$modversion['blocks'][5]['show_func'] = "ck2_cart_focus";
$modversion['blocks'][5]['template'] = "ck2_cart_focus.html";
$modversion['blocks'][5]['edit_func'] = "ck2_cart_focus_edit";
$modversion['blocks'][5]['options'] = "1|1";

$modversion['blocks'][6]['file'] = "ck2_my_cart.php";
$modversion['blocks'][6]['name'] = _MI_CK2CART_BNAME6;
$modversion['blocks'][6]['description'] = _MI_CK2CART_BDESC6;
$modversion['blocks'][6]['show_func'] = "ck2_my_cart";
$modversion['blocks'][6]['template'] = "ck2_my_cart.html";


//---偏好設定---//

$modversion['config'][1]['name']	= 'status';
$modversion['config'][1]['title']	= '_MI_CK2CART_STATUS';
$modversion['config'][1]['description']	= '_MI_CK2CART_STATUS_DESC';
$modversion['config'][1]['formtype']	= 'textbox';
$modversion['config'][1]['valuetype']	= 'text';
$modversion['config'][1]['default']	= _MI_CK2CART_STATUS_VAL;

$modversion['config'][2]['name']	= 'commodity_list_mode';
$modversion['config'][2]['title']	= '_MI_CK2CART_COMMODITY_LIST_MODE';
$modversion['config'][2]['description']	= '_MI_CK2CART_COMMODITY_LIST_MODE_DESC';
$modversion['config'][2]['formtype']	= 'select';
$modversion['config'][2]['valuetype']	= 'text';
$modversion['config'][2]['default']	= 'rand';
$modversion['config'][2]['options']	= array(_MI_CK2CART_CONF2_OPT1 => 'rand',_MI_CK2CART_CONF2_OPT2 => 'new');


$modversion['config'][3]['name']	= 'show_num';
$modversion['config'][3]['title']	= '_MI_CK2CART_SHOW_NUM';
$modversion['config'][3]['description']	= '_MI_CK2CART_SHOW_NUM_DESC';
$modversion['config'][3]['formtype']	= 'textbox';
$modversion['config'][3]['valuetype']	= 'int';
$modversion['config'][3]['default']	= '20';

$modversion['config'][4]['name']	= 'new_commodity_height';
$modversion['config'][4]['title']	= '_MI_CK2CART_NEW_COMMODITY_HEIGHT';
$modversion['config'][4]['description']	= '_MI_CK2CART_NEW_COMMODITY_HEIGHT_DESC';
$modversion['config'][4]['formtype']	= 'textbox';
$modversion['config'][4]['valuetype']	= 'int';
$modversion['config'][4]['default']	= '300';

$modversion['config'][5]['name']	= 'new_commodity_sec';
$modversion['config'][5]['title']	= '_MI_CK2CART_NEW_COMMODITY_SEC';
$modversion['config'][5]['description']	= '_MI_CK2CART_NEW_COMMODITY_SEC_DESC';
$modversion['config'][5]['formtype']	= 'textbox';
$modversion['config'][5]['valuetype']	= 'int';
$modversion['config'][5]['default']	= '3000';

$modversion['config'][6]['name']	= 'new_commodity_num';
$modversion['config'][6]['title']	= '_MI_CK2CART_NEW_COMMODITY_NUM';
$modversion['config'][6]['description']	= '_MI_CK2CART_NEW_COMMODITY_NUM_DESC';
$modversion['config'][6]['formtype']	= 'textbox';
$modversion['config'][6]['valuetype']	= 'int';
$modversion['config'][6]['default']	= '2';



?>
