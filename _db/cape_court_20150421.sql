-- phpMyAdmin SQL Dump
-- version 3.4.3.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Apr 20, 2015 at 08:23 PM
-- Server version: 5.0.91
-- PHP Version: 5.3.29

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `cape_court`
--

-- --------------------------------------------------------

--
-- Table structure for table `documents`
--

CREATE TABLE IF NOT EXISTS `documents` (
  `d_id` int(11) NOT NULL auto_increment,
  `d_create_time` datetime NOT NULL,
  `field_1` varchar(500) collate utf8_unicode_ci default NULL,
  `field_2` varchar(500) collate utf8_unicode_ci default NULL,
  `field_3` varchar(500) collate utf8_unicode_ci default NULL,
  `field_4` varchar(500) collate utf8_unicode_ci default NULL,
  `field_5` varchar(500) collate utf8_unicode_ci default NULL,
  `field_6` varchar(500) collate utf8_unicode_ci default NULL,
  `field_7` varchar(500) collate utf8_unicode_ci default NULL,
  `field_8` varchar(500) collate utf8_unicode_ci default NULL,
  PRIMARY KEY  (`d_id`),
  KEY `d_create_time` (`d_create_time`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=11 ;

--
-- Dumping data for table `documents`
--

INSERT INTO `documents` (`d_id`, `d_create_time`, `field_1`, `field_2`, `field_3`, `field_4`, `field_5`, `field_6`, `field_7`, `field_8`) VALUES
(2, '2015-02-14 00:00:00', 'อย.4638/2557', 'พนักงานอัยการ สำนักงานอัยการสูงสุด (สำนักอัยการพิเศษฝ่ายคดียาเสพติด 2)', 'นางสมรัก หรือต่าย บ่อทอง', '13.31', 'ศูนย์สมานฉันท์', 'สมานฉันท์', 'รอพิจารณาคดี', NULL),
(4, '2015-02-13 00:00:00', 'อ.3687/2554', 'พนักงานอัยการ สำนักงานอัยการพิเศษฝ่ายคดีพิเศษ 1 สำนักงานอัยการสูงสุด', ' นายอัครเดช หรืออรรถเดช เบ็ญจพันธุ์ กับพวกรวม 3 คน', '13.30', '711', 'สืบพยานโจทย์', 'รอพิจารณาคดี', NULL),
(5, '2015-02-12 00:00:00', 'อ.3505/2555', 'พนักงานอัยการ สำนักงานอัยการพิเศษฝ่ายคดีอาญา 3 สำนักงานอัยการสูงสุด', ' บริษัทสุวรรณศรีทรานสปอร์ต จำกัด โดย นายรุ่งโรจน์ สุวรรณศรี กับพวกรวม 3 คน', '13.30', '813', 'สืบพยานโจทย์', 'รอพิจารณาคดี', NULL),
(6, '2015-02-11 00:00:00', 'อ.194/2556', 'นายทรรน ศิลาทอง', ' นายหรรษารมย์ โกมุทผล กับพวกรวม 2 คน', '13.30', '908', 'สืบพยานโจทย์', 'เสร็จการพิจารณาคดี', NULL),
(7, '2015-02-10 00:00:00', 'อ.495/2556', 'นายสุเทพ เทือกสุวรรณ', 'นายธาริต เพ็งดิษฐ์', '13.30', '902', 'สืบพยานโจทย์', 'รอพิจารณาคดี', NULL),
(8, '2015-02-09 00:00:00', 'อ.1526/2556', 'พนักงานอัยการ สำนักงานอัยการพิเศษฝ่ายคดีอาญา 9 สำนักงานอัยการสูงสุด', 'นางสาวณัชชา เล็กวิริยะกุล', '13.30', '713', 'สืบพยานโจทย์', 'รอพิจารณาคดี', NULL),
(10, '2015-02-08 00:00:00', 'อ.2492/2556', 'พนักงานอัยการ สำนักงานอัยการพิเศษฝ่ายคดีอาญา 9 สำนักงานอัยการสูงสุด', 'นายชวนนท์ อินทรโกมาลย์กุล', '13.30', '805', 'สืบพยานโจทย์', 'รอพิจารณาคดี', NULL);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
