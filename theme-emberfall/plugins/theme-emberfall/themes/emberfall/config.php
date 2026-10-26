<?php
defined('MYAAC') or die('Direct access not allowed!');

$config['menu_categories'] = [
	MENU_CATEGORY_NEWS => 		['id' => 'news', 		'name' => 'Latest News'],
	MENU_CATEGORY_ACCOUNT => 	['id' => 'account', 	'name' => 'Account'],
	MENU_CATEGORY_COMMUNITY => 	['id' => 'community', 	'name' => 'Community'],
	MENU_CATEGORY_FORUM => 		['id' => 'forum', 		'name' => 'Forum'],
	MENU_CATEGORY_LIBRARY => 	['id' => 'library', 	'name' => 'Library'],
	MENU_CATEGORY_SHOP => 		['id' => 'shops', 		'name' => 'Shop'],
];

$config['menu_default_links_color'] = '#cfe6ff';

$config['menus'] = require __DIR__ . '/menus.php';
