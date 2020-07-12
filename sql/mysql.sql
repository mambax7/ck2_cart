CREATE TABLE `ck2_stores` (
  `stores_sn` smallint(5) unsigned NOT NULL auto_increment,
  `store_title` varchar(255) NOT NULL,
  `store_desc` text NOT NULL,
  `store_counter` smallint(5) unsigned NOT NULL,
  `store_master` varchar(255) NOT NULL,
  `store_email` varchar(255) NOT NULL,
  `enable` enum('0','1') NOT NULL default '0',
  `uid` smallint(5) unsigned NOT NULL,
  `open_date` date NOT NULL default '0000-00-00',
  PRIMARY KEY  (`stores_sn`)
) ENGINE=MyISAM;



CREATE TABLE `ck2_stores_bank` (
  `bank_sn` smallint(5) unsigned NOT NULL auto_increment,
  `uid` smallint(5) unsigned NOT NULL,
  `order_bank` varchar(255) NOT NULL,
  `order_bank_num` varchar(255) NOT NULL,
  PRIMARY KEY  (`bank_sn`),
  KEY `uid` (`uid`)
) ENGINE=MyISAM;



CREATE TABLE `ck2_stores_brand` (
  `brand_sn` smallint(5) unsigned NOT NULL auto_increment,
  `stores_sn` smallint(5) unsigned NOT NULL,
  `brand_name` varchar(255) NOT NULL,
  `brand_desc` text NOT NULL,
  `brand_url` varchar(255) NOT NULL,
  `enable` enum('1','0') NOT NULL default '1',
  PRIMARY KEY  (`brand_sn`),
  KEY `stores_sn` (`stores_sn`)
) ENGINE=MyISAM;



CREATE TABLE `ck2_stores_commodity` (
  `commodity_sn` smallint(5) unsigned NOT NULL auto_increment,
  `kinds_sn` smallint(5) unsigned NOT NULL,
  `brand_sn` smallint(5) unsigned NOT NULL,
  `com_title` varchar(255) NOT NULL,
  `com_summary` text NOT NULL,
  `com_content` text NOT NULL,
  `com_unit` varchar(255) NOT NULL,
  `com_post_date` datetime NOT NULL default '0000-00-00 00:00:00',
  `com_counter` smallint(5) unsigned NOT NULL,
  `enable` enum('1','0') NOT NULL default '1',
  `payment` varchar(255) NOT NULL,
  `shipping` varchar(255) NOT NULL,
  PRIMARY KEY  (`commodity_sn`),
  KEY `cksn` (`kinds_sn`)
) ENGINE=MyISAM;



CREATE TABLE `ck2_stores_commodity_kinds` (
  `kinds_sn` mediumint(8) unsigned NOT NULL auto_increment,
  `of_kinds_sn` smallint(5) unsigned NOT NULL,
  `stores_sn` smallint(5) unsigned NOT NULL,
  `kind_title` varchar(255) NOT NULL,
  `kind_desc` text NOT NULL,
  `kind_sort` smallint(5) unsigned NOT NULL,
  `kind_counter` smallint(5) unsigned NOT NULL,
  `enable` enum('1','0') NOT NULL default '1',
  PRIMARY KEY  (`kinds_sn`),
  KEY `stores_sn` (`stores_sn`)
) ENGINE=MyISAM;



CREATE TABLE `ck2_stores_commodity_specification` (
  `specification_sn` smallint(5) unsigned NOT NULL auto_increment,
  `commodity_sn` smallint(5) unsigned NOT NULL,
  `specification_title` varchar(255) NOT NULL,
  `specification_amount` smallint(5) unsigned NOT NULL,
  `specification_price` mediumint(8) unsigned NOT NULL,
  `specification_sprice` mediumint(8) unsigned NOT NULL,
  `specification_sprice_end_date` date NOT NULL,
  PRIMARY KEY  (`specification_sn`)
) ENGINE=MyISAM;


CREATE TABLE `ck2_stores_customer` (
  `uid` smallint(5) unsigned NOT NULL,
  `customer_name` varchar(255) NOT NULL,
  `customer_email` varchar(255) NOT NULL,
  `customer_tel` varchar(255) NOT NULL,
  `customer_zip` varchar(10) NOT NULL,
  `customer_city` varchar(255) NOT NULL,
  `customer_town` varchar(255) NOT NULL,
  `customer_addr` varchar(255) NOT NULL,
  PRIMARY KEY  (`uid`)
) ENGINE=MyISAM;


CREATE TABLE `ck2_stores_customer_addr` (
  `customer_addr_sn` smallint(5) unsigned NOT NULL auto_increment,
  `uid` smallint(5) unsigned NOT NULL,
  `customer_zip` varchar(10) NOT NULL,
  `customer_city` varchar(255) NOT NULL,
  `customer_town` varchar(255) NOT NULL,
  `customer_addr` varchar(255) NOT NULL,
  PRIMARY KEY  (`customer_addr_sn`)
) ENGINE=MyISAM;



CREATE TABLE `ck2_stores_image_center` (
  `images_sn` smallint(5) unsigned NOT NULL auto_increment,
  `col_name` varchar(255) NOT NULL,
  `col_sn` smallint(6) NOT NULL,
  `filename` varchar(255) NOT NULL,
  `sort` tinyint(4) NOT NULL,
  PRIMARY KEY  (`images_sn`),
  UNIQUE KEY `col_name` (`col_name`,`col_sn`,`sort`)
) ENGINE=MyISAM;



CREATE TABLE `ck2_stores_news` (
  `news_sn` smallint(5) unsigned NOT NULL,
  `stores_sn` smallint(5) unsigned NOT NULL,
  `news_title` varchar(255) NOT NULL,
  `news_content` text NOT NULL,
  `news_counter` smallint(5) unsigned NOT NULL,
  `news_start_date` date NOT NULL default '0000-00-00',
  `news_end_date` date NOT NULL default '0000-00-00',
  `news_kind` varchar(255) NOT NULL,
  PRIMARY KEY  (`news_sn`)
) ENGINE=MyISAM;



CREATE TABLE `ck2_stores_order` (
  `order_sn` smallint(5) unsigned NOT NULL auto_increment,
  `stores_sn` smallint(5) unsigned NOT NULL,
  `uid` smallint(5) unsigned NOT NULL,
  `order_date` datetime NOT NULL default '0000-00-00 00:00:00',
  `customer_addr_sn` smallint(5) unsigned NOT NULL,
  `shipping_sn` smallint(5) unsigned NOT NULL,
  `order_note` text NOT NULL,
  `sum` mediumint(8) unsigned NOT NULL,
  `payment_sn` smallint(5) unsigned NOT NULL,
  `bank_name` varchar(255) NOT NULL,
  `bank_num` varchar(255) NOT NULL,
  `pay_money` smallint(5) unsigned NOT NULL,
  `order_pay_date` datetime NOT NULL default '0000-00-00 00:00:00',
  `order_status` varchar(255) NOT NULL,
  PRIMARY KEY  (`order_sn`),
  KEY (`uid`),
  KEY `stores_sn` (`stores_sn`)
) ENGINE=MyISAM;



CREATE TABLE `ck2_stores_order_commodity` (
  `order_sn` smallint(5) unsigned NOT NULL,
  `specification_sn` smallint(5) unsigned NOT NULL,
  `amount` smallint(5) unsigned NOT NULL,
  `sum` int(10) unsigned NOT NULL,
  PRIMARY KEY  (`order_sn`,`specification_sn`)
) ENGINE=MyISAM;



CREATE TABLE `ck2_stores_payment` (
  `payment_sn` smallint(5) unsigned NOT NULL auto_increment,
  `stores_sn` smallint(5) unsigned NOT NULL,
  `payment_name` varchar(255) NOT NULL,
  `payment_desc` text NOT NULL,
  `enable` enum('1','0') NOT NULL default '1',
  PRIMARY KEY  (`payment_sn`),
  KEY `stores_sn` (`stores_sn`)
) ENGINE=MyISAM;



CREATE TABLE `ck2_stores_shipping` (
  `shipping_sn` smallint(5) unsigned NOT NULL auto_increment,
  `stores_sn` smallint(5) unsigned NOT NULL,
  `shipping_name` varchar(255) NOT NULL,
  `shipping_desc` text NOT NULL,
  `shipping_pay` smallint(5) unsigned NOT NULL,
  `enable` enum('1','0') NOT NULL default '1',
  PRIMARY KEY  (`shipping_sn`),
  KEY `stores_sn` (`stores_sn`)
) ENGINE=MyISAM;

CREATE TABLE `ck2_stores_contents` (
	`page_sn` SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
	`page_title` VARCHAR( 255 ) NOT NULL,
	`page_content` TEXT NOT NULL,
	`in_menu` ENUM( '1', '0' ) NOT NULL,
	`post_date` DATETIME NOT NULL
) ENGINE = MYISAM ;
