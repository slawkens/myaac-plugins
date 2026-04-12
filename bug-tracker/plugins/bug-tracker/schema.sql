CREATE TABLE IF NOT EXISTS `myaac_bug_tracker`
(
	`id` INT(11) NOT NULL AUTO_INCREMENT,
	`uid` INT(11) NOT NULL,
	`account_id` INT(11) NOT NULL,
	`type` INT(11) NOT NULL DEFAULT 0,
	`status` INT(11) NOT NULL DEFAULT 0,
	`subject` VARCHAR(255) NOT NULL DEFAULT '',
	`text` varchar(10000) NOT NULL DEFAULT '',
	`reply` INT(11) NOT NULL DEFAULT 0,
	`who` INT(11) NOT NULL DEFAULT 0,
	`tag` INT(11) NOT NULL DEFAULT 0,
	`created_at` datetime NOT NULL,
	`updated_at` datetime,
	PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARACTER SET=utf8mb4;
