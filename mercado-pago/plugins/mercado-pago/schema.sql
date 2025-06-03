CREATE TABLE IF NOT EXISTS `myaac_mercadopago` (
	`id` int NOT NULL AUTO_INCREMENT,
	`email` varchar(255) NOT NULL,
	`account_id` int NOT NULL,
	`idempotency_key` varchar(255) NOT NULL,
	`collector_id` varchar(255) NOT NULL,
	`price` float NOT NULL,
	`currency` varchar(10) NOT NULL,
	`points` int NOT NULL,
	`payer_status` varchar(255) NOT NULL,
	`payment_status` varchar(255) NOT NULL,
	`created` datetime NOT NULL,
	`updated` datetime,
	PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARACTER SET=utf8mb4;
