<?php
define('MENU_CATEGORY_MAIN', 7);

$config['menu_categories'] = [
	MENU_CATEGORY_MAIN => ['name' => 'Main Menu'],
	MENU_CATEGORY_COMMUNITY => ['name' => 'Community'],
	MENU_CATEGORY_LIBRARY => ['name' => 'Library'],
	MENU_CATEGORY_SHOP => ['name' => 'Shop'],
];

$config['menu_default_links_color'] = '#000000';
$config['menus'] = require PLUGINS . 'theme-semantic/menus.php';
