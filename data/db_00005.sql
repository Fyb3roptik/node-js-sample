-- phpMyAdmin SQL Dump
-- version 3.1.2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Mar 05, 2009 at 10:53 AM
-- Server version: 5.0.51
-- PHP Version: 5.2.4-2ubuntu5.5

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

-- --------------------------------------------------------

--
-- Table structure for table `page_meta_tags`
--

CREATE TABLE IF NOT EXISTS `page_meta_tags` (
  `page_meta_tag_id` int(11) NOT NULL auto_increment,
  `page_id` int(11) NOT NULL,
  `meta_tag_id` int(11) NOT NULL,
  PRIMARY KEY  (`page_meta_tag_id`),
  KEY `page_id` (`page_id`),
  KEY `meta_tag_id` (`meta_tag_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

