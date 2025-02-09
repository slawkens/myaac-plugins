<?php
define('MENU_CATEGORY_TOP', 7);
define('MENU_CATEGORY_LEFT_SIDE', 8);

$config['menu_categories'] = [
	MENU_CATEGORY_TOP => ['name' => 'Top Menu (up to 8 links)'],
	MENU_CATEGORY_LEFT_SIDE => ['name' => 'Side Menu (how many you wish)'],
];

$config['menu_default_links_color'] = '#ffffff';
$config['menus'] = require PLUGINS . 'theme-tibiana/menus.php';
