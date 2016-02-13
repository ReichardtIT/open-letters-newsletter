-- phpMyAdmin SQL Dump
-- version 3.2.2.1deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Erstellungszeit: 10. April 2010 um 18:05
-- Server Version: 5.1.37
-- PHP-Version: 5.2.10-2ubuntu6.4

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- Datenbank: `newsletter`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `newsletter`
--

CREATE TABLE IF NOT EXISTS `newsletter` (
  `email_id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(128) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `anrede` varchar(8) NOT NULL,
  `aktiv` tinyint(1) NOT NULL DEFAULT 0,
  `double_optin_token` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`email_id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Daten für Tabelle `newsletter`
--

INSERT INTO `newsletter` (`email_id`, `email`, `name`, `anrede`) VALUES
(1, 'webmaster@open-letters.de', 'Stefan Rank-Kunitz', 'Herr');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `newsletterCont`
--

CREATE TABLE IF NOT EXISTS `newsletterCont` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date` int(11) NOT NULL DEFAULT '0',
  `sent` int(11) NOT NULL DEFAULT '0',
  `templatefile` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=15 ;

--
-- Daten für Tabelle `newsletterCont`
--

INSERT INTO `newsletterCont` (`id`, `date`, `sent`, `templatefile`) VALUES
(10, 1259314905, 0, 'template_02_xmas'),
(13, 1270915082, 4, 'template_01');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `newsletterEntries`
--

CREATE TABLE IF NOT EXISTS `newsletterEntries` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `newsletterContId` int(11) NOT NULL DEFAULT '0',
  `headline` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `ordering` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=19 ;

--
-- Daten für Tabelle `newsletterEntries`
--

INSERT INTO `newsletterEntries` (`id`, `newsletterContId`, `headline`, `content`, `ordering`) VALUES
(13, 10, 'Neuer Eintrag 2', '<p style="text-align:justify;">Sed ut perspi<strong>ciatis </strong><span><strong>unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab ill</strong>o in</span>ventore veritatis et quasi architecto beatae vitae dicta sunt explicabo. Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt.</p>', 1),
(12, 10, 'Ein neuer Newslettereintrag:', '<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>', 0),
(16, 13, 'Ein neues Newslettersystem:', '<p style="text-align:center;"><img src="http://localhost/~kermit/newsletter/uploaded/newslettersystem_versand_300x284.jpg" alt="" /></p>\n<p>Seit heute hat Open-Letters ein neues Newslettersystem! FÃ¼hlen Sie sich frei, dieses Newslettersystem herunterzuladen und zu nutzen.</p>\n<p>Wenn Sie VerÃ¤nderungen am System vornehmen, also zum Beispiel:</p>\n<ul><li>neue Templates entwickeln</li>\n<li>Funktionserweiterungen vornehmen</li>\n<li>Fehler finden und beheben</li>\n</ul><p>so bitten wir Sie, diese Ã„nderungen an uns zurÃ¼ckzugeben: Wir wÃ¼rden Sie gern allen anderen Nutzern zur VerfÃ¼gung stellen!</p>', 0),
(14, 10, 'Frohe Weihnachten!', '<p style="text-align:right;">Ihr Open-Letters-Team wÃ¼nscht Ihnen<br />ein gesegnetes Weihnachtsfest</p>', 2),
(18, 13, 'Vielen Dank!', '<p>Ihr Open-Letters-Team</p>', 1);
