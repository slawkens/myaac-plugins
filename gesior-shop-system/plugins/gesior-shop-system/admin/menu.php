<?php

global $menus;

$menus[] = [
	'name' => 'Gifts System', 'icon' => 'gift', 'order' => 111, 'link' => [
		['name' => 'Offers', 'link' => 'plugins/gesior-shop-system/admin/gifts.php', 'icon' => 'list', 'order' => 10],
		['name' => 'Add Offer', 'link' => 'plugins/gesior-shop-system/admin/gifts.php&action=offer_form', 'icon' => 'plus',	'order' => 20],
	],
];
