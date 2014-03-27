SET NAMES utf8;
SET foreign_key_checks = 0;
SET time_zone = '-07:00';
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

DROP DATABASE IF EXISTS `approach`;
CREATE DATABASE `approach` /*!40100 DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci */;
USE `approach`;


DROP TABLE IF EXISTS `components`;
CREATE TABLE `components` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `composition` bigint(20) unsigned NOT NULL DEFAULT '1',
  `type` bigint(20) unsigned NOT NULL DEFAULT '2',
  `instance` bigint(20) unsigned NOT NULL,
  `content` bigint(20) unsigned DEFAULT NULL,
  `meta` bigint(20) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `composition` (`composition`),
  KEY `type` (`type`),
  CONSTRAINT `components_ibfk_1` FOREIGN KEY (`composition`) REFERENCES `compositions` (`id`),
  CONSTRAINT `components_ibfk_4` FOREIGN KEY (`type`) REFERENCES `types` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `components` (`id`, `composition`, `type`, `instance`, `content`, `meta`) VALUES
(1,	1,	13,	0,	1,	NULL)
ON DUPLICATE KEY UPDATE `id` = VALUES(`id`), `composition` = VALUES(`composition`), `type` = VALUES(`type`), `instance` = VALUES(`instance`), `content` = VALUES(`content`), `meta` = VALUES(`meta`);

DROP TABLE IF EXISTS `compositions`;
CREATE TABLE `compositions` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `owner` bigint(20) unsigned NOT NULL DEFAULT '1',
  `meta` bigint(20) unsigned DEFAULT NULL,
  `parent` bigint(20) unsigned NOT NULL DEFAULT '1',
  `scope` bigint(20) unsigned NOT NULL DEFAULT '1',
  `self` int(10) unsigned DEFAULT NULL,
  `root` bit(1) NOT NULL DEFAULT b'0',
  `active` bit(1) NOT NULL DEFAULT b'1',
  `error` bit(1) NOT NULL DEFAULT b'0',
  `update` bit(1) NOT NULL DEFAULT b'0',
  `privacy` bit(1) NOT NULL DEFAULT b'0',
  `cache` bit(1) NOT NULL DEFAULT b'1',
  `migrate` bit(1) NOT NULL DEFAULT b'0',
  `lock` bit(1) NOT NULL DEFAULT b'0',
  `title` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `alias` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `tags` varchar(1023) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `scope` (`scope`),
  CONSTRAINT `compositions_ibfk_1` FOREIGN KEY (`scope`) REFERENCES `types` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `compositions` (`id`, `owner`, `meta`, `parent`, `scope`, `self`, `root`, `active`, `error`, `update`, `privacy`, `cache`, `migrate`, `lock`, `title`, `alias`, `tags`) VALUES
(1,	1,	NULL,	1,	1,	0,	CONV('1', 2, 10) + 0,	CONV('1', 2, 10) + 0,	CONV('0', 2, 10) + 0,	CONV('0', 2, 10) + 0,	CONV('0', 2, 10) + 0,	CONV('1', 2, 10) + 0,	CONV('0', 2, 10) + 0,	CONV('0', 2, 10) + 0,	'An organic approach to software',	'',	'approach, home, app, business, critical'),
(2,	1,	NULL,	2,	1,	0,	CONV('1', 2, 10) + 0,	CONV('1', 2, 10) + 0,	CONV('0', 2, 10) + 0,	CONV('0', 2, 10) + 0,	CONV('0', 2, 10) + 0,	CONV('1', 2, 10) + 0,	CONV('0', 2, 10) + 0,	CONV('0', 2, 10) + 0,	'Source++ | Developing organic ecosystems.',	'',	'tutorials, documentation, reference, code, samples, examples, help, programming, software'),
(3,	1,	NULL,	2,	1,	1,	CONV('1', 2, 10) + 0,	CONV('1', 2, 10) + 0,	CONV('0', 2, 10) + 0,	CONV('0', 2, 10) + 0,	CONV('0', 2, 10) + 0,	CONV('1', 2, 10) + 0,	CONV('0', 2, 10) + 0,	CONV('0', 2, 10) + 0,	'Cloud++ | Organize organically.',	'',	'Cloud++, community, free, jobs, cooperative, designers, users, developers'),
(4,	1,	NULL,	2,	1,	2,	CONV('1', 2, 10) + 0,	CONV('1', 2, 10) + 0,	CONV('0', 2, 10) + 0,	CONV('0', 2, 10) + 0,	CONV('0', 2, 10) + 0,	CONV('1', 2, 10) + 0,	CONV('0', 2, 10) + 0,	CONV('0', 2, 10) + 0,	'Prosper++ | Organic marketplace for buyers, sellers and producers',	'',	'Team building, time-to-market, quality support, innovation, cost savings, productivity, profit'),
(5,	1,	NULL,	1,	15,	0,	CONV('1', 2, 10) + 0,	CONV('1', 2, 10) + 0,	CONV('0', 2, 10) + 0,	CONV('0', 2, 10) + 0,	CONV('0', 2, 10) + 0,	CONV('1', 2, 10) + 0,	CONV('0', 2, 10) + 0,	CONV('0', 2, 10) + 0,	'Approach Developer Blog',	'blog',	'approach, developers, blog, bi-weekly, opinions, garet, ranting'),
(6,	1,	NULL,	5,	1,	1,	CONV('0', 2, 10) + 0,	CONV('1', 2, 10) + 0,	CONV('0', 2, 10) + 0,	CONV('0', 2, 10) + 0,	CONV('0', 2, 10) + 0,	CONV('1', 2, 10) + 0,	CONV('0', 2, 10) + 0,	CONV('0', 2, 10) + 0,	'Race Conditions in Async & Parallel Programming',	'',	'race conditions,asynchronous,parallel,programming,blog,approach,garet,first blog,first,featured'),
(7,	1,	NULL,	5,	1,	2,	CONV('0', 2, 10) + 0,	CONV('1', 2, 10) + 0,	CONV('0', 2, 10) + 0,	CONV('0', 2, 10) + 0,	CONV('0', 2, 10) + 0,	CONV('1', 2, 10) + 0,	CONV('0', 2, 10) + 0,	CONV('0', 2, 10) + 0,	'The virtues of deleting good and bad code',	'',	'good,bad,code,delete,virtue,approach, programming, developer, blog, garet'),
(8,	1,	NULL,	5,	1,	3,	CONV('0', 2, 10) + 0,	CONV('1', 2, 10) + 0,	CONV('0', 2, 10) + 0,	CONV('0', 2, 10) + 0,	CONV('0', 2, 10) + 0,	CONV('1', 2, 10) + 0,	CONV('0', 2, 10) + 0,	CONV('0', 2, 10) + 0,	'Deflate economies with valuable Intellectual Property',	'deflation',	'economy,deflation,intellectual property,IP,rights,approach,blog,garet,prosper,market,opinion peice'),
(9,	1,	NULL,	1,	1,	1,	CONV('0', 2, 10) + 0,	CONV('1', 2, 10) + 0,	CONV('0', 2, 10) + 0,	CONV('0', 2, 10) + 0,	CONV('0', 2, 10) + 0,	CONV('1', 2, 10) + 0,	CONV('0', 2, 10) + 0,	CONV('0', 2, 10) + 0,	'Approach',	'approach',	'Approach,homefront'),
(10,	1,	NULL,	9,	1,	0,	CONV('0', 2, 10) + 0,	CONV('1', 2, 10) + 0,	CONV('0', 2, 10) + 0,	CONV('0', 2, 10) + 0,	CONV('0', 2, 10) + 0,	CONV('1', 2, 10) + 0,	CONV('0', 2, 10) + 0,	CONV('0', 2, 10) + 0,	'Foundation Profile',	'profile',	'approach,foundation,profile,about us,garet')
ON DUPLICATE KEY UPDATE `id` = VALUES(`id`), `owner` = VALUES(`owner`), `meta` = VALUES(`meta`), `parent` = VALUES(`parent`), `scope` = VALUES(`scope`), `self` = VALUES(`self`), `root` = VALUES(`root`), `active` = VALUES(`active`), `error` = VALUES(`error`), `update` = VALUES(`update`), `privacy` = VALUES(`privacy`), `cache` = VALUES(`cache`), `migrate` = VALUES(`migrate`), `lock` = VALUES(`lock`), `title` = VALUES(`title`), `alias` = VALUES(`alias`), `tags` = VALUES(`tags`);

DROP TABLE IF EXISTS `featured`;
CREATE TABLE `featured` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `img` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `headline` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `content` text COLLATE utf8_unicode_ci NOT NULL,
  `active` bit(1) NOT NULL DEFAULT b'1',
  `node` bigint(20) unsigned DEFAULT NULL,
  `self_id` bigint(20) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `node` (`node`),
  CONSTRAINT `featured_ibfk_1` FOREIGN KEY (`node`) REFERENCES `compositions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `featured` (`id`, `img`, `headline`, `content`, `active`, `node`, `self_id`) VALUES
(1,	'http://static.approachfoundation.org/img/logo.png',	'<h1>Introducing: Organic Source!</h1><h2>A new way to approach software</h2>',	'<article>\r\n<em>Approach is a new kind of open source</em> coming together over everyone involved in the process.<br /<br />\r\n\r\nExperience awesome new workflows while getting the most out of your infrastructure. Turn any current works into fullblown cloud apps near instantly!<br /<br />\r\n\r\nCreate, grow and extend your reach with new directions to interact.<br /<br />\r\n\r\nWhether running on a single processor or thousands of clusters globally, Approach makes orchestration simpler - and we\'re getting even simpler all the time.<br /<br />\r\n\r\nWe\'re just getting started, so please excuse our dust. We\'re a small team of myself, two current contributors, a marketer and a site admin.<br /<br />\r\n\r\n\r\nHello, world! Nice to meet ya.\r\n<br /><br />\r\n<span class=\"morelink\">Read On:</span><a href=\"#intromore\" class=\"morelink\">Intro to the Foundation</a>\r\n\r\n</article>',	CONV('1', 2, 10) + 0,	NULL,	NULL)
ON DUPLICATE KEY UPDATE `id` = VALUES(`id`), `img` = VALUES(`img`), `headline` = VALUES(`headline`), `content` = VALUES(`content`), `active` = VALUES(`active`), `node` = VALUES(`node`), `self_id` = VALUES(`self_id`);

INSERT INTO `links` (`id`, `parent`, `from`, `to`, `fromType`, `toType`, `fromURI`, `toURI`, `codename`) VALUES
(0,	0,	0,	0,	0,	0,	'',	'',	'unclassified'),
(1,	1,	1,	1,	0,	0,	'',	'',	'classified'),
(128,	1,	NULL,	NULL,	0,	0,	NULL,	NULL,	'uri.tree')
ON DUPLICATE KEY UPDATE `id` = VALUES(`id`), `parent` = VALUES(`parent`), `from` = VALUES(`from`), `to` = VALUES(`to`), `fromType` = VALUES(`fromType`), `toType` = VALUES(`toType`), `fromURI` = VALUES(`fromURI`), `toURI` = VALUES(`toURI`), `codename` = VALUES(`codename`);

DROP TABLE IF EXISTS `types`;
CREATE TABLE `types` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `parent` bigint(20) unsigned NOT NULL,
  `pointer` bigint(20) unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=256 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `types` (`id`, `parent`, `pointer`, `name`) VALUES
(0,	0,	0,	''),
(1,	7,	0,	'composition'),
(2,	7,	0,	'component'),
(3,	7,	0,	'service'),
(4,	7,	0,	'binding'),
(5,	7,	0,	'dataset'),
(6,	7,	0,	'renderable'),
(7,	0,	0,	'system'),
(8,	7,	0,	'guide'),
(9,	1,	0,	'category'),
(10,	1,	0,	'search'),
(11,	1,	0,	'relate'),
(12,	1,	0,	'system'),
(13,	2,	0,	'mime'),
(14,	2,	0,	'mask'),
(15,	2,	0,	'live'),
(16,	2,	0,	'filter'),
(17,	1,	0,	'application'),
(18,	2,	0,	'application'),
(19,	3,	0,	'application'),
(20,	7,	0,	'application'),
(21,	1,	0,	'document'),
(22,	2,	13,	'document'),
(23,	5,	0,	'document'),
(24,	6,	13,	'document'),
(25,	1,	0,	'code'),
(26,	2,	0,	'code'),
(27,	6,	0,	'code'),
(28,	20,	0,	'code'),
(29,	0,	0,	'uint'),
(30,	0,	0,	'spectra'),
(31,	0,	0,	'string'),
(32,	0,	0,	'reference'),
(122,	20,	0,	'Desktop'),
(123,	20,	0,	'Server'),
(124,	20,	0,	'Mobile'),
(125,	20,	0,	'Browser'),
(126,	20,	0,	'Console'),
(127,	20,	0,	'Mechanical'),
(128,	20,	129,	'All'),
(129,	20,	0,	'Cloud');
ON DUPLICATE KEY UPDATE `id` = VALUES(`id`), `Parent` = VALUES(`Parent`), `Name` = VALUES(`Name`);
