-- phpMyAdmin SQL Dump
-- version 3.3.10deb1
-- http://www.phpmyadmin.net
--
-- Serveur: localhost
-- Généré le : Lun 15 Août 2011 à 22:19
-- Version du serveur: 5.1.54
-- Version de PHP: 5.3.5-1ubuntu7.2

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- Base de données: `echoes_game`
--

-- --------------------------------------------------------

--
-- Structure de la table `ACHIEVEMENTS`
--

CREATE TABLE IF NOT EXISTS `ACHIEVEMENTS` (
  `ACH_ID` int(20) NOT NULL AUTO_INCREMENT,
  `ACH_NAME` varchar(20) NOT NULL,
  `ACH_DESC` varchar(50) NOT NULL,
  PRIMARY KEY (`ACH_ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Contenu de la table `ACHIEVEMENTS`
--


-- --------------------------------------------------------

--
-- Structure de la table `ACHIEVEMENTS_USERS`
--

CREATE TABLE IF NOT EXISTS `ACHIEVEMENTS_USERS` (
  `ASR_USR_ID` int(20) NOT NULL,
  `ASR_ACH_ID` int(20) NOT NULL,
  UNIQUE KEY `ASR_USR_ID` (`ASR_USR_ID`,`ASR_ACH_ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Contenu de la table `ACHIEVEMENTS_USERS`
--


-- --------------------------------------------------------

--
-- Structure de la table `SCORES_BEST`
--

CREATE TABLE IF NOT EXISTS `SCORES_BEST` (
  `SBE_USR_ID` bigint(20) NOT NULL COMMENT 'fk USERS',
  `SBE_LEVEL` varchar(20) NOT NULL COMMENT 'level name',
  `SBE_SCORE` int(20) NOT NULL COMMENT 'player best score for the level',
  `SBE_NB_CLIC` int(10) NOT NULL COMMENT 'nb clic for best score',
  `SBE_PERCENT_SATISF` int(3) NOT NULL COMMENT 'percent for best score',
  `SBE_NB_PLAYED` int(10) NOT NULL COMMENT 'nb played total',
  `SBE_NB_WIN` int(10) NOT NULL COMMENT 'nb win total',
  UNIQUE KEY `IDX_ID_LEVEL` (`SBE_USR_ID`,`SBE_LEVEL`),
  KEY `SBE_USR_ID` (`SBE_USR_ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Contenu de la table `SCORES_BEST`
--

INSERT INTO `SCORES_BEST` (`SBE_USR_ID`, `SBE_LEVEL`, `SBE_SCORE`, `SBE_NB_CLIC`, `SBE_PERCENT_SATISF`, `SBE_NB_PLAYED`, `SBE_NB_WIN`) VALUES
(559788374, 'Novice', 2820, 14, 100, 22, 5),
(559788374, 'Normal', 11200, 0, 80, 22, 1),
(559788374, 'Expert', 0, 0, 0, 0, 0),
(559788374, 'Maître', 0, 0, 0, 0, 0),
(100000831236752, 'Novice', 0, 0, 0, 0, 0),
(100000831236752, 'Normal', 0, 0, 0, 0, 0),
(100000831236752, 'Expert', 0, 0, 0, 0, 0),
(100000831236752, 'Maître', 0, 0, 0, 0, 0);

-- --------------------------------------------------------

--
-- Structure de la table `SCORES_RACE`
--

CREATE TABLE IF NOT EXISTS `SCORES_RACE` (
  `SRA_USR_ID` bigint(20) NOT NULL COMMENT 'fk USERS',
  `SRA_SCORE` int(20) NOT NULL COMMENT 'cumul score',
  `SRA_AVERAGE_NB_CLIC` int(20) NOT NULL COMMENT 'average nb clic',
  `SRA_MOST_PLAYED_LEVEL` varchar(20) NOT NULL COMMENT 'most played level',
  UNIQUE KEY `SRA_USR_ID` (`SRA_USR_ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Contenu de la table `SCORES_RACE`
--

INSERT INTO `SCORES_RACE` (`SRA_USR_ID`, `SRA_SCORE`, `SRA_AVERAGE_NB_CLIC`, `SRA_MOST_PLAYED_LEVEL`) VALUES
(559788374, 65380, 0, '1'),
(100000831236752, 0, 0, '');

-- --------------------------------------------------------

--
-- Structure de la table `USERS`
--

CREATE TABLE IF NOT EXISTS `USERS` (
  `USR_ID` bigint(20) NOT NULL COMMENT 'id fb',
  `USR_NAME` varchar(100) NOT NULL COMMENT 'name fb',
  `USR_FIRST_NAME` varchar(100) NOT NULL COMMENT 'first name fb',
  `USR_LAST_NAME` varchar(100) NOT NULL COMMENT 'last name fb',
  `USR_USERNAME` varchar(100) NOT NULL COMMENT 'username fb',
  PRIMARY KEY (`USR_ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Contenu de la table `USERS`
--

INSERT INTO `USERS` (`USR_ID`, `USR_NAME`, `USR_FIRST_NAME`, `USR_LAST_NAME`, `USR_USERNAME`) VALUES
(559788374, 'Thomas Saquet', 'Thomas', 'Saquet', 'thomas.saquet'),
(0, 'Guest', 'Guest', 'Guest', 'Guest'),
(100000831236752, 'Florent Poinsaut', 'Florent', 'Poinsaut', 'florent.poinsaut');
