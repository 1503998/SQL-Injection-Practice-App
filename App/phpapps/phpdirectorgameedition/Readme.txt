Upgrading for highscore.

Just upload all the files to your webserver and add this to tables to your database

 -- phpMyAdmin SQL Dump
-- version 3.2.0.1
-- http://www.phpmyadmin.net
--
-- Serveur: localhost
-- Généré le : Mar 27 Juillet 2010 à 16:09
-- Version du serveur: 5.1.36
-- Version de PHP: 5.3.0

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- Base de données: `com`
--

-- --------------------------------------------------------

--
-- Structure de la table `pp_leaderboard`
--

CREATE TABLE IF NOT EXISTS `pp_leaderboard` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) NOT NULL DEFAULT '',
  `thescore` decimal(20,0) DEFAULT NULL,
  `gamename` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `pp_scores`
--

CREATE TABLE IF NOT EXISTS `pp_scores` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) NOT NULL DEFAULT '',
  `thescore` decimal(20,0) DEFAULT NULL,
  `gameidname` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`),
  KEY `username` (`username`),
  KEY `gameidname` (`gameidname`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;



To install phpdirector Game Edition just go to yoursiteur/install and follow the instruction.

You need to CHMOD icon and swf folder to 777.