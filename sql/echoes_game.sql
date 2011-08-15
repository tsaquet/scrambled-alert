-- phpMyAdmin SQL Dump
-- version 3.3.0
-- http://www.phpmyadmin.net
--
-- Serveur: localhost
-- Généré le : Lun 15 Août 2011 à 22:04
-- Version du serveur: 5.1.44
-- Version de PHP: 5.2.13

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de données: `echoes_game`
--

-- --------------------------------------------------------

--
-- Structure de la table `achievements`
--

CREATE TABLE IF NOT EXISTS `achievements` (
  `ACH_ID` int(20) NOT NULL AUTO_INCREMENT,
  `ACH_NAME` varchar(20) NOT NULL,
  `ACH_DESC` varchar(50) NOT NULL,
  PRIMARY KEY (`ACH_ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Contenu de la table `achievements`
--


-- --------------------------------------------------------

--
-- Structure de la table `achievements_users`
--

CREATE TABLE IF NOT EXISTS `achievements_users` (
  `ASR_USR_ID` int(20) NOT NULL,
  `ASR_ACH_ID` int(20) NOT NULL,
  UNIQUE KEY `ASR_USR_ID` (`ASR_USR_ID`,`ASR_ACH_ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Contenu de la table `achievements_users`
--


-- --------------------------------------------------------

--
-- Structure de la table `scores_best`
--

CREATE TABLE IF NOT EXISTS `scores_best` (
  `SBE_USR_ID` int(20) NOT NULL COMMENT 'fk USERS',
  `SBE_LEVEL` varchar(20) NOT NULL COMMENT 'level name',
  `SBE_SCORE` int(20) NOT NULL COMMENT 'player best score for the level',
  `SBE_NB_CLIC` int(10) NOT NULL COMMENT 'nb clic for best score',
  `SBE_PERCENT_SATISF` int(3) NOT NULL COMMENT 'percent for best score',
  `SBE_NB_PLAYED` int(10) NOT NULL COMMENT 'nb played total',
  `SBE_NB_WIN` int(10) NOT NULL COMMENT 'nb win total',
  KEY `SBE_USR_ID` (`SBE_USR_ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Contenu de la table `scores_best`
--

INSERT INTO `scores_best` (`SBE_USR_ID`, `SBE_LEVEL`, `SBE_SCORE`, `SBE_NB_CLIC`, `SBE_PERCENT_SATISF`, `SBE_NB_PLAYED`, `SBE_NB_WIN`) VALUES
(559788374, 'Novice', 2820, 14, 100, 22, 5),
(559788374, 'Normal', 11200, 0, 80, 22, 1),
(559788374, 'Expert', 0, 0, 0, 0, 0),
(559788374, 'Maître', 0, 0, 0, 0, 0);

-- --------------------------------------------------------

--
-- Structure de la table `scores_race`
--

CREATE TABLE IF NOT EXISTS `scores_race` (
  `SRA_USR_ID` int(20) NOT NULL COMMENT 'fk USERS',
  `SRA_SCORE` int(20) NOT NULL COMMENT 'cumul score',
  `SRA_AVERAGE_NB_CLIC` int(20) NOT NULL COMMENT 'average nb clic',
  `SRA_MOST_PLAYED_LEVEL` varchar(20) NOT NULL COMMENT 'most played level',
  UNIQUE KEY `SRA_USR_ID` (`SRA_USR_ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Contenu de la table `scores_race`
--

INSERT INTO `scores_race` (`SRA_USR_ID`, `SRA_SCORE`, `SRA_AVERAGE_NB_CLIC`, `SRA_MOST_PLAYED_LEVEL`) VALUES
(559788374, 65380, 0, '1');

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `USR_ID` int(20) NOT NULL COMMENT 'id fb',
  `USR_NAME` varchar(100) NOT NULL COMMENT 'name fb',
  `USR_FIRST_NAME` varchar(100) NOT NULL COMMENT 'first name fb',
  `USR_LAST_NAME` varchar(100) NOT NULL COMMENT 'last name fb',
  `USR_USERNAME` varchar(100) NOT NULL COMMENT 'username fb',
  PRIMARY KEY (`USR_ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Contenu de la table `users`
--

INSERT INTO `users` (`USR_ID`, `USR_NAME`, `USR_FIRST_NAME`, `USR_LAST_NAME`, `USR_USERNAME`) VALUES
(559788374, 'Thomas Saquet', 'Thomas', 'Saquet', 'thomas.saquet'),
(0, 'Guest', 'Guest', 'Guest', 'Guest');
