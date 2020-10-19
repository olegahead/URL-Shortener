create database shortener;
use shortener;

CREATE TABLE IF NOT EXISTS `links` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `link` VARCHAR(2000) NOT NULL,
    `hash` VARCHAR(50) NOT NULL,
    `ttl` TIMESTAMP NOT NULL,
    KEY `link_index`(`link`),
    KEY `hash_index`(`hash`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE latin1_general_cs;

CREATE TABLE IF NOT EXISTS `statistics` (
    `link_id` INT UNSIGNED NOT NULL,
    `ip` VARCHAR(32) NOT NULL,
    `visit_datetime` DATETIME NOT NULL DEFAULT NOW(),
    FOREIGN KEY (`link_id`) REFERENCES `links` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
