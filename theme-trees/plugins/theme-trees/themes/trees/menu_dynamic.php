<?php
defined('MYAAC') or die('Direct access not allowed!');

$menus = get_template_menus();

foreach($menus as $category => $menu) {
	if(!isset($menus[$category]) || $category == 7 || ($category == 6 && !$config['gifts_system'])) { // ignore Account Menu and shop system if disabled
		continue;
	}

	$size = count($menus[$category]);

	if($size == 1) { // only one menu item, don't show full menu, just link
		echo '<li><a href="' . $menu[0]['link_full'] . '"' . $menu[0]['target_blank'] . '>' . $menu[0]['name'] . '</a></li>';
		continue;
	}

	echo '<li><a href="#">' . $config['menu_categories'][$category]['name'] . '</a><ul>';
	foreach($menus[$category] as $_menu) {
		echo '<li><a href="' . $_menu['link_full'] . '"' . $_menu['target_blank'] . '>' . $_menu['name'] . '</a></li>';
	}

	echo '</ul></li>';
}
?>
