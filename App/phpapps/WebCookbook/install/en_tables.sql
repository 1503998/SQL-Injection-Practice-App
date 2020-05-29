-- phpMyAdmin SQL Dump
-- version 3.4.5deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Erstellungszeit: 29. Feb 2012 um 22:17
-- Server Version: 5.1.58
-- PHP-Version: 5.3.6-13ubuntu3.6

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Datenbank: `rezepte`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `das_rezept`
--

CREATE TABLE IF NOT EXISTS `das_rezept` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `servings` int(11) NOT NULL,
  `prefix` text,
  `preparation` text NOT NULL,
  `postfix` text,
  `tipp` text,
  `arbeitszeit` varchar(10) DEFAULT NULL,
  `brennwert` int(11) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `title` (`title`),
  FULLTEXT KEY `prefix` (`prefix`),
  FULLTEXT KEY `preparation` (`preparation`),
  FULLTEXT KEY `postfix` (`postfix`),
  FULLTEXT KEY `tipp` (`tipp`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=353 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `dat_ingredient`
--

CREATE TABLE IF NOT EXISTS `dat_ingredient` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `amount` float DEFAULT NULL,
  `value` varchar(10) DEFAULT NULL,
  `description` varchar(255) NOT NULL,
  `recipe` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3280 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `dat_category`
--

CREATE TABLE IF NOT EXISTS `dat_category` (
  `id` int(11) NOT NULL,
  `text` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Daten für Tabelle `dat_category`
--

INSERT INTO `dat_category` (`id`, `text`) VALUES
(101, 'Appetizer'),
(102, 'Soups and stews'),
(103, 'Vegetables and salads'),
(104, 'Deli'),
(105, 'Seafood'),
(106, 'Poltry'),
(107, 'Minced meat'),
(108, 'Heart, Kidney &amp; co'),
(109, 'Calf'),
(110, 'Lamb'),
(111, 'Beef'),
(112, 'Pork'),
(113, 'Game'),
(114, 'Sausage'),
(115, 'Egg dishes'),
(116, 'Potatoes'),
(119, 'Conserves and sausages'),
(120, 'Casseroles'),
(121, 'Sauces'),
(122, 'Desserts'),
(123, 'Baking'),
(124, 'Others'),
(301, 'Arabian peninsula'),
(302, 'Belgium'),
(303, 'China'),
(304, 'Denmark'),
(305, 'France'),
(306, 'Greece'),
(307, 'Indonesia'),
(308, 'Italia'),
(309, 'Jamaica'),
(310, 'Caucasus'),
(311, 'Kroatia/Serbia'),
(312, 'Mexico'),
(313, 'Near/Middleeast'),
(314, 'Nethererlands'),
(315, 'Austrich'),
(316, 'Russia'),
(317, 'Sweden'),
(318, 'Switzerland'),
(319, 'Spain'),
(320, 'Thailand'),
(321, 'Tschechia'),
(322, 'Turkey'),
(323, 'Hungary'),
(324, 'Great Britain'),
(325, 'USA'),
(326, 'Maghreb (Northern Africa)'),
(601, 'Alcohol-free'),
(602, 'Beer'),
(603, 'Cocktails'),
(604, 'Punsch'),
(605, 'Sparkling wine'),
(606, 'Spirits'),
(607, 'Wine'),
(608, 'Others'),
(901, 'Beverages');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `dat_rezepttocat`
--

CREATE TABLE IF NOT EXISTS `dat_rezepttocat` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `recipe` int(11) NOT NULL,
  `category` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=608 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `dat_slides`
--

CREATE TABLE IF NOT EXISTS `dat_slides` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `image` varchar(255) NOT NULL,
  `text` text,
  `recipe` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=150 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `dat_texte`
--

CREATE TABLE IF NOT EXISTS `dat_texte` (
  `id` int(11) NOT NULL,
  `chapter` int(11) DEFAULT NULL,
  `text` text NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `position` varchar(1) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `sys_zaehler`
--

CREATE TABLE IF NOT EXISTS `sys_zaehler` (
  `id` int(11) NOT NULL,
  `zaehler` int(11) NOT NULL,
  `datum` date NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle dat_comments
--

CREATE TABLE IF NOT EXISTS `dat_comments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `recipe` int(11) NOT NULL,
  `comment` text NOT NULL,
  `datum` date NOT NULL,
  `user` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
