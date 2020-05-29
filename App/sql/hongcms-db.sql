-- phpMyAdmin SQL Dump
-- version 4.6.6
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Feb 19, 2020 at 11:14 AM
-- Server version: 5.6.35
-- PHP Version: 5.6.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `hongcms`
--

-- --------------------------------------------------------

--
-- Table structure for table `hong_acat`
--

CREATE TABLE `hong_acat` (
  `cat_id` int(11) NOT NULL,
  `p_id` int(11) NOT NULL DEFAULT '0',
  `sort` int(11) NOT NULL DEFAULT '0',
  `is_show` tinyint(1) NOT NULL DEFAULT '1',
  `show_sub` tinyint(1) NOT NULL DEFAULT '1',
  `name` varchar(255) NOT NULL DEFAULT '',
  `name_en` varchar(255) NOT NULL DEFAULT '',
  `keywords` varchar(255) NOT NULL DEFAULT '',
  `keywords_en` varchar(255) NOT NULL DEFAULT '',
  `desc_cn` text NOT NULL,
  `desc_en` text NOT NULL,
  `counts` int(11) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `hong_admin`
--

CREATE TABLE `hong_admin` (
  `userid` int(11) NOT NULL,
  `activated` tinyint(1) NOT NULL DEFAULT '0',
  `username` varchar(64) NOT NULL DEFAULT '',
  `password` varchar(64) NOT NULL DEFAULT '',
  `joindate` int(11) NOT NULL DEFAULT '0',
  `lastdate` int(11) NOT NULL DEFAULT '0',
  `joinip` varchar(64) NOT NULL DEFAULT '',
  `lastip` varchar(64) NOT NULL DEFAULT '',
  `loginnum` int(11) NOT NULL DEFAULT '0',
  `nickname` varchar(64) NOT NULL DEFAULT ''
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `hong_admin`
--

INSERT INTO `hong_admin` (`userid`, `activated`, `username`, `password`, `joindate`, `lastdate`, `joinip`, `lastip`, `loginnum`, `nickname`) VALUES
(1, 1, 'admin', '5f4dcc3b5aa765d61d8327deb882cf99', 1582110686, 1582110736, 'unknown', '127.0.0.1', 1, '系统管理员');

-- --------------------------------------------------------

--
-- Table structure for table `hong_article`
--

CREATE TABLE `hong_article` (
  `a_id` int(30) NOT NULL,
  `sort` int(30) NOT NULL DEFAULT '0',
  `cat_id` int(11) NOT NULL DEFAULT '0',
  `is_show` tinyint(1) NOT NULL DEFAULT '0',
  `is_best` tinyint(1) NOT NULL DEFAULT '0',
  `userid` int(11) NOT NULL DEFAULT '0',
  `username` varchar(64) NOT NULL DEFAULT '',
  `title` varchar(255) NOT NULL DEFAULT '',
  `title_en` varchar(255) NOT NULL DEFAULT '',
  `content` text NOT NULL,
  `content_en` text NOT NULL,
  `keywords` varchar(255) NOT NULL DEFAULT '',
  `keywords_en` varchar(255) NOT NULL DEFAULT '',
  `clicks` int(11) NOT NULL DEFAULT '0',
  `created` int(11) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `hong_content`
--

CREATE TABLE `hong_content` (
  `c_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL DEFAULT '',
  `title_en` varchar(255) NOT NULL DEFAULT '',
  `content` text NOT NULL,
  `content_en` text NOT NULL,
  `keywords` varchar(255) NOT NULL DEFAULT '',
  `keywords_en` varchar(255) NOT NULL DEFAULT '',
  `created` int(11) NOT NULL DEFAULT '0',
  `r_id` int(11) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `hong_content`
--

INSERT INTO `hong_content` (`c_id`, `title`, `title_en`, `content`, `content_en`, `keywords`, `keywords_en`, `created`, `r_id`) VALUES
(1, '关于我们', 'About Us', '请在后台管理中自定义关于我们的详细内容.', 'please edit your content.', '关于,我们', 'about us', 1265951785, 1),
(2, '联系我们', 'Contact Us', '请在后台管理中自定义联系我们的详细内容.', 'please edit your content.', '联系,我们', 'contact us', 1265951785, 2),
(3, '首页常态内容', 'Homepage content', '请在后台管理中自定义首页常态内容.', 'please edit your homepage content.', 'hongcms中英文企业网站系统', 'hongcms,website system', 1265951785, 3),
(4, '第一个公司', 'The first Company', '请在后台管理常态内容中自定义第一个公司详细介绍.', 'please edit The first Company content on back-end.', 'hongcms,website system', 'hongcms,website system', 1265951785, 11),
(5, '第二个公司', 'The second Company', '请在后台管理常态内容中自定义第二个公司详细介绍.', 'please edit The second Company content on back-end.', 'hongcms,website system', 'hongcms,website system', 1265951785, 12),
(6, '第三个公司', 'The third Company', '请在后台管理常态内容中自定义第三个公司详细介绍.', 'please edit The third Company content on back-end.', 'hongcms,website system', 'hongcms,website system', 1265951785, 13),
(7, '企业文化', 'Our Culture', '请在后台管理常态内容中自定义企业文化详细内容.', 'please edit Our Culture content on back-end.', 'hongcms,website system', 'hongcms,website system', 1265951785, 14),
(8, '组织结构', 'Organization', '请在后台管理常态内容中自定义组织结构详细内容.', 'please edit Organization content on back-end.', 'hongcms,website system', 'hongcms,website system', 1265951785, 15);

-- --------------------------------------------------------

--
-- Table structure for table `hong_gimage`
--

CREATE TABLE `hong_gimage` (
  `g_id` int(11) NOT NULL,
  `pro_id` int(30) NOT NULL DEFAULT '0',
  `is_show` tinyint(1) NOT NULL DEFAULT '1',
  `path` varchar(255) NOT NULL DEFAULT '',
  `filename` varchar(255) NOT NULL DEFAULT ''
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `hong_news`
--

CREATE TABLE `hong_news` (
  `n_id` int(11) NOT NULL,
  `sort` int(11) NOT NULL DEFAULT '0',
  `is_show` tinyint(1) NOT NULL DEFAULT '1',
  `title` varchar(255) NOT NULL DEFAULT '',
  `title_en` varchar(255) NOT NULL DEFAULT '',
  `linkurl` varchar(255) NOT NULL DEFAULT '',
  `linkurl_en` varchar(255) NOT NULL DEFAULT '',
  `keywords` varchar(255) NOT NULL DEFAULT '',
  `keywords_en` varchar(255) NOT NULL DEFAULT '',
  `content` text NOT NULL,
  `content_en` text NOT NULL,
  `clicks` int(30) NOT NULL DEFAULT '0',
  `created` int(11) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `hong_pcat`
--

CREATE TABLE `hong_pcat` (
  `cat_id` int(11) NOT NULL,
  `p_id` int(11) NOT NULL DEFAULT '0',
  `sort` int(11) NOT NULL DEFAULT '0',
  `is_show` tinyint(1) NOT NULL DEFAULT '1',
  `show_sub` tinyint(1) NOT NULL DEFAULT '1',
  `name` varchar(255) NOT NULL DEFAULT '',
  `name_en` varchar(255) NOT NULL DEFAULT '',
  `keywords` varchar(255) NOT NULL DEFAULT '',
  `keywords_en` varchar(255) NOT NULL DEFAULT '',
  `desc_cn` text NOT NULL,
  `desc_en` text NOT NULL,
  `counts` int(11) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `hong_product`
--

CREATE TABLE `hong_product` (
  `pro_id` int(11) NOT NULL,
  `cat_id` int(11) NOT NULL DEFAULT '0',
  `sort` int(11) NOT NULL DEFAULT '0',
  `is_show` tinyint(1) NOT NULL DEFAULT '1',
  `is_best` tinyint(1) NOT NULL DEFAULT '0',
  `userid` int(11) NOT NULL DEFAULT '0',
  `username` varchar(64) NOT NULL DEFAULT '',
  `path` varchar(255) NOT NULL DEFAULT '',
  `filename` varchar(255) NOT NULL DEFAULT '',
  `price` varchar(36) NOT NULL DEFAULT '',
  `price_en` varchar(36) NOT NULL DEFAULT '',
  `title` varchar(255) NOT NULL DEFAULT '',
  `title_en` varchar(255) NOT NULL DEFAULT '',
  `keywords` varchar(255) NOT NULL DEFAULT '',
  `keywords_en` varchar(255) NOT NULL DEFAULT '',
  `content` text NOT NULL,
  `content_en` text NOT NULL,
  `clicks` int(11) NOT NULL DEFAULT '0',
  `created` int(11) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `hong_sessions`
--

CREATE TABLE `hong_sessions` (
  `sessionid` char(64) NOT NULL DEFAULT '',
  `userid` int(11) NOT NULL DEFAULT '0',
  `ipaddress` varchar(64) NOT NULL DEFAULT '',
  `useragent` varchar(255) NOT NULL DEFAULT '',
  `created` int(11) NOT NULL DEFAULT '0',
  `admin` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `hong_sessions`
--

INSERT INTO `hong_sessions` (`sessionid`, `userid`, `ipaddress`, `useragent`, `created`, `admin`) VALUES
('f0424005c65262f3a88d717a7cb22255', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/79.0.3945.130 Safari/537.36', 1582110736, 1);

-- --------------------------------------------------------

--
-- Table structure for table `hong_vvc`
--

CREATE TABLE `hong_vvc` (
  `vvcid` int(30) NOT NULL,
  `code` varchar(9) NOT NULL DEFAULT '',
  `created` int(11) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `hong_acat`
--
ALTER TABLE `hong_acat`
  ADD PRIMARY KEY (`cat_id`),
  ADD KEY `p_id` (`p_id`),
  ADD KEY `sort` (`sort`);

--
-- Indexes for table `hong_admin`
--
ALTER TABLE `hong_admin`
  ADD PRIMARY KEY (`userid`),
  ADD KEY `joindate` (`joindate`);

--
-- Indexes for table `hong_article`
--
ALTER TABLE `hong_article`
  ADD PRIMARY KEY (`a_id`),
  ADD KEY `sort` (`sort`),
  ADD KEY `created` (`created`),
  ADD KEY `cat_id` (`cat_id`);

--
-- Indexes for table `hong_content`
--
ALTER TABLE `hong_content`
  ADD PRIMARY KEY (`c_id`),
  ADD KEY `r_id` (`r_id`),
  ADD KEY `created` (`created`);

--
-- Indexes for table `hong_gimage`
--
ALTER TABLE `hong_gimage`
  ADD PRIMARY KEY (`g_id`),
  ADD KEY `pro_id` (`pro_id`);

--
-- Indexes for table `hong_news`
--
ALTER TABLE `hong_news`
  ADD PRIMARY KEY (`n_id`),
  ADD KEY `sort` (`sort`),
  ADD KEY `created` (`created`);

--
-- Indexes for table `hong_pcat`
--
ALTER TABLE `hong_pcat`
  ADD PRIMARY KEY (`cat_id`),
  ADD KEY `p_id` (`p_id`),
  ADD KEY `sort` (`sort`);

--
-- Indexes for table `hong_product`
--
ALTER TABLE `hong_product`
  ADD PRIMARY KEY (`pro_id`),
  ADD KEY `sort` (`sort`),
  ADD KEY `created` (`created`),
  ADD KEY `cat_id` (`cat_id`);

--
-- Indexes for table `hong_sessions`
--
ALTER TABLE `hong_sessions`
  ADD PRIMARY KEY (`sessionid`),
  ADD KEY `userid` (`userid`),
  ADD KEY `created` (`created`);

--
-- Indexes for table `hong_vvc`
--
ALTER TABLE `hong_vvc`
  ADD PRIMARY KEY (`vvcid`),
  ADD KEY `created` (`created`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `hong_acat`
--
ALTER TABLE `hong_acat`
  MODIFY `cat_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `hong_admin`
--
ALTER TABLE `hong_admin`
  MODIFY `userid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `hong_article`
--
ALTER TABLE `hong_article`
  MODIFY `a_id` int(30) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `hong_content`
--
ALTER TABLE `hong_content`
  MODIFY `c_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
--
-- AUTO_INCREMENT for table `hong_gimage`
--
ALTER TABLE `hong_gimage`
  MODIFY `g_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `hong_news`
--
ALTER TABLE `hong_news`
  MODIFY `n_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `hong_pcat`
--
ALTER TABLE `hong_pcat`
  MODIFY `cat_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `hong_product`
--
ALTER TABLE `hong_product`
  MODIFY `pro_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `hong_vvc`
--
ALTER TABLE `hong_vvc`
  MODIFY `vvcid` int(30) NOT NULL AUTO_INCREMENT;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
