<?php

global $menus;

$menus[] = [
	'name' => 'Gifts System', 'icon' => 'gift', 'order' => 111, 'link' => [
		['name' => 'Offers', 'link' => 'gifts', 'icon' => 'list', 'order' => 10],
		['name' => 'Add Offer', 'link' => 'gifts&action=offer_form', 'icon' => 'plus', 'order' => 20],
	],
];
