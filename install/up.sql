SET NAMES utf8;
SET foreign_key_checks = 0;
SET time_zone = 'SYSTEM';
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

ALTER TABLE `xwt_helpdesk`
ADD `prava` tinyint(1) unsigned NOT NULL DEFAULT '0',
COMMENT='';