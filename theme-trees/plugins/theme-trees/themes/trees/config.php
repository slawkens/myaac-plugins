<?php
$config['menu_categories'] = array(
	1 => ['name' => 'Home'],
	2 => ['name' => 'Community'],
	3 => ['name' => 'Library'],
	4 => ['name' => 'Forum'],
	5 => ['name' => 'Help'],
	6 => ['name' => 'Shop'],
	7 => ['name' => 'Account Menu', 'default_links_color' => '#555555'],
);

$config['menu_default_links_color'] = '#ffffff';
$config['menus'] = require PLUGINS . 'theme-trees/menus.php';
