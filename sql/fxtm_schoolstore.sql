SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;


DROP TABLE IF EXISTS `categories`;
CREATE TABLE IF NOT EXISTS `categories` (
`id` int(11) NOT NULL,
  `name` varchar(45) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

INSERT INTO `categories` (`id`, `name`) VALUES
(1, 'Books'),
(2, 'Pens'),
(3, 'Notebooks');

DROP TABLE IF EXISTS `categories_options`;
CREATE TABLE IF NOT EXISTS `categories_options` (
  `category_id` int(11) NOT NULL,
  `option_id` int(11) NOT NULL,
  `option_value` varchar(255) DEFAULT NULL COMMENT 'Default option value for category'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `categories_options` (`category_id`, `option_id`, `option_value`) VALUES
(1, 1, NULL),
(1, 2, NULL),
(1, 3, NULL),
(1, 4, NULL),
(2, 5, NULL),
(2, 6, NULL),
(2, 7, NULL),
(3, 5, NULL),
(3, 6, NULL),
(3, 8, NULL);

DROP TABLE IF EXISTS `goods`;
CREATE TABLE IF NOT EXISTS `goods` (
`id` int(11) NOT NULL,
  `category_id` int(11) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8;

INSERT INTO `goods` (`id`, `category_id`) VALUES
(2, 1),
(13, 1),
(1, 2),
(16, 2),
(17, 3);

DROP TABLE IF EXISTS `goods_options`;
CREATE TABLE IF NOT EXISTS `goods_options` (
  `goods_id` int(11) NOT NULL,
  `option_id` int(11) NOT NULL,
  `option_value` varchar(255) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

INSERT INTO `goods_options` (`goods_id`, `option_id`, `option_value`) VALUES
(1, 5, 'Russia'),
(2, 2, 'russia'),
(13, 1, 'Harry Potter'),
(13, 2, 'J.K. Rowling'),
(13, 3, '2014'),
(13, 4, '1234567890'),
(13, 5, '123456'),
(16, 5, 'China'),
(16, 6, 'ch-1223-red'),
(16, 7, 'Red'),
(2, 1, 'Russia on 2018 Olympic games'),
(2, 3, '2018'),
(2, 4, '1234567890'),
(1, 6, 'ru'),
(1, 7, 'green'),
(17, 5, 'Alex Trade'),
(17, 6, 'ru-notes'),
(17, 8, 'soft');

DROP TABLE IF EXISTS `options`;
CREATE TABLE IF NOT EXISTS `options` (
`id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

INSERT INTO `options` (`id`, `name`) VALUES
(1, 'name'),
(2, 'authors'),
(3, 'year'),
(4, 'isbn'),
(5, 'manufacturer'),
(6, 'vendor code'),
(7, 'color'),
(8, 'type of cover (hard|soft)');

DROP TABLE IF EXISTS `tags`;
CREATE TABLE IF NOT EXISTS `tags` (
`id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `cnt` int(11) NOT NULL DEFAULT '1'
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8;

INSERT INTO `tags` (`id`, `name`, `cnt`) VALUES
(1, 'qwerty', 6),
(2, 'asdf', 4),
(3, 'name russ', 8),
(16, 'red pen', 1);


ALTER TABLE `categories`
 ADD PRIMARY KEY (`id`);

ALTER TABLE `categories_options`
 ADD PRIMARY KEY (`category_id`,`option_id`), ADD KEY `category_id` (`category_id`), ADD KEY `option_id` (`option_id`);

ALTER TABLE `goods`
 ADD PRIMARY KEY (`id`), ADD KEY `category_fk_idx` (`category_id`);

ALTER TABLE `goods_options`
 ADD PRIMARY KEY (`goods_id`,`option_id`), ADD FULLTEXT KEY `option_value` (`option_value`);

ALTER TABLE `options`
 ADD PRIMARY KEY (`id`);

ALTER TABLE `tags`
 ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `name` (`name`);


ALTER TABLE `categories`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;
ALTER TABLE `goods`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=18;
ALTER TABLE `options`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=9;
ALTER TABLE `tags`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=17;

ALTER TABLE `categories_options`
ADD CONSTRAINT `categories_options_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
ADD CONSTRAINT `categories_options_ibfk_2` FOREIGN KEY (`option_id`) REFERENCES `options` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `goods`
ADD CONSTRAINT `category_fk` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL ON UPDATE SET NULL;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
