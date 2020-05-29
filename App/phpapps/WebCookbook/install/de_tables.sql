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
(101, 'Vorspeisen'),
(102, 'Suppen und Eintöpfe'),
(103, 'Gemüse und Salate'),
(104, 'Feinkost'),
(105, 'Fisch und Meeresfrüchte'),
(106, 'Geflügel'),
(107, 'Hackfleisch'),
(108, 'Innereien'),
(109, 'Kalb'),
(110, 'Lamm'),
(111, 'Rind'),
(112, 'Schweinefleisch'),
(113, 'Wild'),
(114, 'Kochen mit Wurst'),
(115, 'Eiergerichte'),
(116, 'Kartoffeln'),
(119, 'Wursten und Konservieren'),
(120, 'Aufläufe und Gratins'),
(121, 'Saucen'),
(122, 'Desserts'),
(123, 'Backen'),
(124, 'Sonstiges'),
(201, 'Baden-Württemberg'),
(202, 'Bayern'),
(203, 'Berlin und Brandenburg'),
(204, 'Franken'),
(205, 'Hessen'),
(206, 'Niedersachsen/Hamburg/Bremen'),
(207, 'Pfalz'),
(208, 'Rheinland'),
(209, 'Schleswig-Holstein'),
(210, 'Westfalen'),
(301, 'Arabische Halbinsel'),
(302, 'Belgien'),
(303, 'China'),
(304, 'Dänemark'),
(305, 'Frankreich'),
(306, 'Griechenland'),
(307, 'Indonesien'),
(308, 'Italien'),
(309, 'Jamaika'),
(310, 'Kaukasus'),
(311, 'Kroatien/Serbien'),
(312, 'Mexiko'),
(313, 'Nah/Mittelost'),
(314, 'Niederlande'),
(315, 'Österreich'),
(316, 'Russland'),
(317, 'Schweden'),
(318, 'Schweiz'),
(319, 'Spanien'),
(320, 'Thailand'),
(321, 'Tschechien'),
(322, 'Türkei'),
(323, 'Ungarn'),
(324, 'Großbritannien'),
(325, 'USA'),
(326, 'Maghreb (Nordafrika)'),
(601, 'Alkoholfreies'),
(602, 'Biere'),
(603, 'Cocktails'),
(604, 'Punsch'),
(605, 'Moussierender Wein'),
(606, 'Spirituosen'),
(607, 'Wein'),
(608, 'Sonstiges'),
(901, 'Getränke') ;

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

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `dat_bewertung`
--

CREATE TABLE IF NOT EXISTS `dat_bewertung` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `recipe` int(11) NOT NULL,
  `user` varchar(255) NOT NULL,
  `ranking` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=15 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
