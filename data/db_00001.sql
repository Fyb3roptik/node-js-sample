-- phpMyAdmin SQL Dump
-- version 3.1.2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Mar 03, 2009 at 10:11 AM
-- Server version: 5.0.51
-- PHP Version: 5.2.4-2ubuntu5.5

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

-- --------------------------------------------------------

--
-- Table structure for table `administrators`
--

CREATE TABLE IF NOT EXISTS `administrators` (
  `admin_id` int(11) NOT NULL auto_increment,
  `name` varchar(256) NOT NULL,
  `email` varchar(256) NOT NULL,
  `password` varchar(40) NOT NULL,
  `permission_level` int(6) NOT NULL,
  `session_token` varchar(40) NOT NULL,
  PRIMARY KEY  (`admin_id`),
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `session_token` (`session_token`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- Table structure for table `admin_password_tokens`
--

CREATE TABLE IF NOT EXISTS `admin_password_tokens` (
  `token_id` int(11) NOT NULL auto_increment,
  `admin_id` int(11) NOT NULL,
  `token` varchar(16) NOT NULL,
  `expiration` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  PRIMARY KEY  (`token_id`),
  UNIQUE KEY `token` (`token`),
  KEY `admin_id` (`admin_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE IF NOT EXISTS `categories` (
  `category_id` int(11) NOT NULL auto_increment,
  `parent_id` int(11) NOT NULL,
  `name` varchar(256) NOT NULL,
  `url` varchar(256) NOT NULL,
  `nav` tinyint(1) NOT NULL default '0',
  `sort_order` int(5) NOT NULL,
  PRIMARY KEY  (`category_id`),
  UNIQUE KEY `url` (`url`),
  KEY `parent_id` (`parent_id`),
  KEY `nav` (`nav`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1832 ;

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE IF NOT EXISTS `customers` (
  `customer_id` int(11) NOT NULL auto_increment,
  `account_type` varchar(24) NOT NULL default 'Personal',
  `name` varchar(256) NOT NULL,
  `email` varchar(256) NOT NULL,
  `password` varchar(40) NOT NULL,
  `session_token` varchar(40) NOT NULL,
  `date_registered` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `last_login` timestamp NOT NULL default '0000-00-00 00:00:00',
  `sales_rep` int(11) NOT NULL,
  PRIMARY KEY  (`customer_id`),
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `session_token` (`session_token`),
  KEY `sales_rep` (`sales_rep`),
  KEY `email_2` (`email`,`password`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=214 ;

-- --------------------------------------------------------

--
-- Table structure for table `customer_addresses`
--

CREATE TABLE IF NOT EXISTS `customer_addresses` (
  `address_id` int(11) NOT NULL auto_increment,
  `customer_id` int(11) NOT NULL,
  `nickname` varchar(256) NOT NULL,
  `company` varchar(256) NOT NULL,
  `address_1` varchar(256) NOT NULL,
  `address_2` varchar(256) NOT NULL,
  `city` varchar(256) NOT NULL,
  `state` varchar(256) NOT NULL,
  `zip_code` varchar(5) NOT NULL,
  `phone` varchar(32) NOT NULL,
  PRIMARY KEY  (`address_id`),
  KEY `customer_id` (`customer_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

-- --------------------------------------------------------

--
-- Table structure for table `customer_cart_products`
--

CREATE TABLE IF NOT EXISTS `customer_cart_products` (
  `cart_product_id` int(11) NOT NULL auto_increment,
  `customer_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(4) NOT NULL,
  PRIMARY KEY  (`cart_product_id`),
  UNIQUE KEY `customer_id_2` (`customer_id`,`product_id`),
  KEY `customer_id` (`customer_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=71 ;

-- --------------------------------------------------------

--
-- Table structure for table `customer_password_tokens`
--

CREATE TABLE IF NOT EXISTS `customer_password_tokens` (
  `token_id` int(11) NOT NULL auto_increment,
  `customer_id` int(11) NOT NULL,
  `token` varchar(16) NOT NULL,
  `expiration` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  PRIMARY KEY  (`token_id`),
  UNIQUE KEY `token` (`token`),
  KEY `customer_id` (`customer_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=10 ;

-- --------------------------------------------------------

--
-- Table structure for table `meta_tags`
--

CREATE TABLE IF NOT EXISTS `meta_tags` (
  `meta_tag_id` int(11) NOT NULL auto_increment,
  `product_id` int(11) NOT NULL,
  `name` varchar(256) NOT NULL,
  `content` text NOT NULL,
  PRIMARY KEY  (`meta_tag_id`),
  UNIQUE KEY `product_id_2` (`product_id`,`name`),
  KEY `product_id` (`product_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=11287 ;

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE IF NOT EXISTS `orders` (
  `order_id` int(11) NOT NULL auto_increment,
  `customer_id` int(11) NOT NULL,
  `date_purchased` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `status` tinyint(1) NOT NULL,
  `shipping_name` varchar(256) NOT NULL,
  `shipping_company` varchar(256) NOT NULL,
  `shipping_address_1` varchar(256) NOT NULL,
  `shipping_address_2` varchar(256) NOT NULL,
  `shipping_city` varchar(256) NOT NULL,
  `shipping_state` varchar(2) NOT NULL,
  `shipping_zip_code` varchar(32) NOT NULL,
  `shipping_phone` varchar(64) NOT NULL,
  `shipping_country` varchar(2) NOT NULL,
  `billing_name` varchar(256) NOT NULL,
  `billing_company` varchar(256) NOT NULL,
  `billing_address_1` varchar(256) NOT NULL,
  `billing_address_2` varchar(256) NOT NULL,
  `billing_city` varchar(256) NOT NULL,
  `billing_state` varchar(2) NOT NULL,
  `billing_zip_code` varchar(32) NOT NULL,
  `billing_phone` varchar(64) NOT NULL,
  `billing_country` varchar(2) NOT NULL,
  PRIMARY KEY  (`order_id`),
  KEY `customer_id` (`customer_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- Table structure for table `order_line_items`
--

CREATE TABLE IF NOT EXISTS `order_line_items` (
  `item_id` int(11) NOT NULL auto_increment,
  `order_id` int(11) NOT NULL,
  `type` varchar(16) default NULL,
  `name` varchar(256) NOT NULL,
  `sort_order` int(5) NOT NULL,
  `unit_price` decimal(15,2) NOT NULL default '0.00',
  `quantity` int(5) NOT NULL default '1',
  `taxable` tinyint(4) NOT NULL default '0',
  PRIMARY KEY  (`item_id`),
  KEY `order_id` (`order_id`),
  KEY `type` (`type`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

-- --------------------------------------------------------

--
-- Table structure for table `order_products`
--

CREATE TABLE IF NOT EXISTS `order_products` (
  `order_product_id` int(11) NOT NULL auto_increment,
  `item_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  PRIMARY KEY  (`order_product_id`),
  KEY `order_id` (`item_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

-- --------------------------------------------------------

--
-- Table structure for table `pages`
--

CREATE TABLE IF NOT EXISTS `pages` (
  `page_id` int(11) NOT NULL auto_increment,
  `title` varchar(256) NOT NULL,
  `nickname` varchar(256) NOT NULL,
  `content` text NOT NULL,
  PRIMARY KEY  (`page_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- Table structure for table `states`
--

CREATE TABLE IF NOT EXISTS `states` (
  `state_id` int(4) NOT NULL auto_increment,
  `state` varchar(32) NOT NULL,
  `abbr` varchar(8) default NULL,
  `sales_tax` decimal(6,4) NOT NULL default '0.0000',
  PRIMARY KEY  (`state_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=52 ;

-- --------------------------------------------------------

--
-- Table structure for table `wishlists`
--

CREATE TABLE IF NOT EXISTS `wishlists` (
  `wishlist_id` int(11) NOT NULL auto_increment,
  `customer_id` int(11) NOT NULL,
  `name` varchar(256) NOT NULL,
  PRIMARY KEY  (`wishlist_id`),
  KEY `customer_id` (`customer_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=42 ;

-- --------------------------------------------------------

--
-- Table structure for table `wishlist_products`
--

CREATE TABLE IF NOT EXISTS `wishlist_products` (
  `wishlist_product_id` int(11) NOT NULL auto_increment,
  `wishlist_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(4) NOT NULL,
  PRIMARY KEY  (`wishlist_product_id`),
  KEY `wishlist_id` (`wishlist_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=40 ;

