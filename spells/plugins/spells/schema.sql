CREATE TABLE IF NOT EXISTS `myaac_spells`
(
	`id` INT(11) NOT NULL AUTO_INCREMENT,
	`spell` varchar(255) NOT NULL DEFAULT '',
	`name` varchar(255) NOT NULL,
	`words` varchar(255) NOT NULL DEFAULT '',
	`group` tinyint NOT NULL DEFAULT 0 COMMENT '1 - attack, 2 - healing, 3 - summon, 4 - supply, 5 - support',
	`type` tinyint NOT NULL DEFAULT 0 COMMENT '1 - instant, 2 - conjure, 3 - rune',
	`level` int NOT NULL DEFAULT 0,
	`maglevel` int NOT NULL DEFAULT 0,
	`mana` int NOT NULL DEFAULT 0,
	`soul` tinyint NOT NULL DEFAULT 0,
	`conjure_id` int NOT NULL DEFAULT 0,
	`conjure_count` tinyint NOT NULL DEFAULT 0,
	`reagent` int NOT NULL DEFAULT 0,
	`rune_id` int NOT NULL DEFAULT 0,
	`premium` tinyint NOT NULL DEFAULT 0,
	`vocations` varchar(100) NOT NULL DEFAULT '',
	`hide` tinyint NOT NULL DEFAULT 0,
	`created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
	`updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
	PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARACTER SET=utf8mb4;
