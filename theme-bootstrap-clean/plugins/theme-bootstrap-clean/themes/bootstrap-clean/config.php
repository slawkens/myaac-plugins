<?php
defined('MYAAC') or die('Direct access not allowed!');

$config['bgColor']		= 'bg-light'; //choose between bg-dark and bg-light
$config['navbarColor']	= 'navbar-light'; //choose between navbar-dark and navbar-light
$config['tableColor']	= 'table-light'; //choose between table-dark and table-light
$config['btnColor']		= 'btn-primary'; //choose between btn-secondary and btn-primary
$config['textColor']	= 'text-dark';

const MENU_CATEGORY_TOP = 7;

$config['menu_categories'] = [
	MENU_CATEGORY_TOP => 		['id' => 'top', 		'name' => 'Top'],
	MENU_CATEGORY_NEWS => 		['id' => 'news', 		'name' => 'Latest News'],
	MENU_CATEGORY_ACCOUNT => 	['id' => 'account', 	'name' => 'Account'],
	MENU_CATEGORY_COMMUNITY => 	['id' => 'community', 	'name' => 'Community'],
	MENU_CATEGORY_FORUM => 		['id' => 'forum', 		'name' => 'Forum'],
	MENU_CATEGORY_LIBRARY => 	['id' => 'library', 	'name' => 'Library'],
	MENU_CATEGORY_SHOP => 		['id' => 'shops', 		'name' => 'Shop'],
];

$config['menu_default_links_color'] = '#000000';

$config['menus'] = require __DIR__ . '/menus.php';
