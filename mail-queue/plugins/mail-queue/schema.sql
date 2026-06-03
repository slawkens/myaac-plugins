CREATE TABLE IF NOT EXISTS `myaac_mail_queue` (
	`id` int NOT NULL AUTO_INCREMENT,
	`subject` varchar(500) NOT NULL DEFAULT '',
	`body` varchar(10000) NOT NULL DEFAULT '',
	`recipient` varchar(300) NOT NULL DEFAULT '',
	`status` tinyint NOT NULL DEFAULT 0,
	`priority` tinyint NOT NULL DEFAULT 0,
	`account_id` int NOT NULL DEFAULT 0,
	`ip` varchar(45) NOT NULL DEFAULT '',
	`created_at` timestamp,
	`updated_at` timestamp,
	PRIMARY KEY (`id`)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS `myaac_mail_queue_history` (
	`id` int NOT NULL AUTO_INCREMENT,
	`account_id` int NOT NULL DEFAULT 0,
	`ip` varchar(45) NOT NULL DEFAULT '',
	`created_at` timestamp,
	`updated_at` timestamp,
	PRIMARY KEY (`id`)
) ENGINE=InnoDB;


