-- phpMyAdmin SQL Dump
-- version 3.5.2.2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Mar 10, 2013 at 12:25 PM
-- Server version: 5.5.27
-- PHP Version: 5.4.7

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `TweetUdit`
--

-- --------------------------------------------------------

--
-- Table structure for table `tweet`
--

DROP TABLE IF EXISTS `tweet`;
CREATE TABLE IF NOT EXISTS `tweet` (
  `id` varchar(32) NOT NULL,
  `text` varchar(256) NOT NULL,
  `created_by` varchar(32) NOT NULL,
  `creater_profile_image` varchar(256) NOT NULL,
  `created_at` varchar(32) NOT NULL,
  `user_id` varchar(32) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tweet`
--

INSERT INTO `tweet` (`id`, `text`, `created_by`, `creater_profile_image`, `created_at`, `user_id`) VALUES
('310700139658485761', 'That was potted analysis of Rahul (like Modi last week): for more, you will have to await my writings/book. Till then, enjoy a sunday nap!', 'sardesairajdeep', 'http://a0.twimg.com/profile_images/347293067/Untitled-1_copy_normal.jpg', 'Sun Mar 10 10:34:20 +0000 2013', '116640047'),
('310700699031851008', 'Chaired a Cabinet meeting.we approved the new board of Directors for the Supreme Council for Motherhood &amp; Childhood http://t.co/hQQH3HwBoI', 'HHShkMohd', 'http://a0.twimg.com/profile_images/1259402631/MHD_4708_normal.JPG', 'Sun Mar 10 10:36:34 +0000 2013', '116640047'),
('310700939105402880', 'It will be chaired by Her Highness Sheikha Fatima bint Mubarak, known for her very important role in supporting mothers, children &amp; families', 'HHShkMohd', 'http://a0.twimg.com/profile_images/1259402631/MHD_4708_normal.JPG', 'Sun Mar 10 10:37:31 +0000 2013', '116640047'),
('310701601713180672', 'We also adopted a draft law for "International Center of Excellence on Countering Violent Extremismâ€', 'HHShkMohd', 'http://a0.twimg.com/profile_images/1259402631/MHD_4708_normal.JPG', 'Sun Mar 10 10:40:09 +0000 2013', '116640047'),
('310701867229384704', 'RT @SadhguruJV: May this Mahashivaratri not only be a night of wakefulness but of awakening. Join us for the nightlong telecast on Aasth ...', 'shekharkapur', 'http://a0.twimg.com/profile_images/2385194922/bnd4twa982tugs4xdsfi_normal.jpeg', 'Sun Mar 10 10:41:12 +0000 2013', '116640047'),
('310702153830367232', 'Extremism is religiously &amp; morally unacceptable. International cooperation is needed to confront it. UAE will remain a key partner', 'HHShkMohd', 'http://a0.twimg.com/profile_images/1259402631/MHD_4708_normal.JPG', 'Sun Mar 10 10:42:20 +0000 2013', '116640047'),
('310702274039132160', 'We also adopted a new control system for drinking water in UAE, which aims to improve water standards in quality, production, processes etc.', 'HHShkMohd', 'http://a0.twimg.com/profile_images/1259402631/MHD_4708_normal.JPG', 'Sun Mar 10 10:42:49 +0000 2013', '116640047'),
('310702642605199360', 'Water is life.. and the quality of water is the quality of life.. and its safety is the safety of all members of a society', 'HHShkMohd', 'http://a0.twimg.com/profile_images/1259402631/MHD_4708_normal.JPG', 'Sun Mar 10 10:44:17 +0000 2013', '116640047'),
('310702874000752640', 'Yasin Malik detained at Srinagar airport, put under house arrest http://t.co/xCEGdPSjNj', 'timesofindia', 'http://a0.twimg.com/profile_images/1282391388/icon_512_normal.png', 'Sun Mar 10 10:45:12 +0000 2013', '116640047'),
('310709386559500289', 'RT @lbc973: And at 12.30, Dr Polly Russell joins Iain to discuss the @britishlibrary''s fascinating Sisterhood &amp; After project.', 'britishlibrary', 'http://a0.twimg.com/profile_images/491040175/logovbigsquare_normal.gif', 'Sun Mar 10 11:11:05 +0000 2013', '116640047');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
CREATE TABLE IF NOT EXISTS `user` (
  `id` varchar(32) NOT NULL,
  `screen_name` varchar(32) NOT NULL,
  `name` varchar(32) NOT NULL,
  `profile_image_url` varchar(256) NOT NULL,
  `profile_background_image_url` varchar(256) NOT NULL,
  `profile_sidebar_fill_color` varchar(32) NOT NULL,
  `profile_background_color` varchar(32) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `screen_name` (`screen_name`),
  UNIQUE KEY `screen_name_2` (`screen_name`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `screen_name`, `name`, `profile_image_url`, `profile_background_image_url`, `profile_sidebar_fill_color`, `profile_background_color`) VALUES
('', '', '', '', '', '', ''),
('116640047', 'desaiuditd', 'Udit Desai', 'http://a0.twimg.com/profile_images/3229156149/ec8b659f0308da2006cb81acb65ad8fc_normal.jpeg', 'http://a0.twimg.com/images/themes/theme5/bg.gif', '99CC33', '352726');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
