<?php
defined('MYAAC') or die('Direct access not allowed!');

$menus = get_template_menus();

$configMenuCategories = config('menu_categories');
foreach($menus as $category => $menu) {
	if(!isset($menus[$category]) || $category == 7 || ($category == 6 && !config('gifts_system'))) { // ignore Account Menu and shop system if disabled
		continue;
	}

	$size = count($menus[$category]);

	if($size == 1) { // only one menu item, don't show full menu, just link
		echo '<li><a href="' . $menu[0]['link_full'] . '"' . $menu[0]['target_blank'] . '>' . $menu[0]['name'] . '</a></li>';
		continue;
	}

	echo '<li><a href="#">' . $configMenuCategories[$category]['name'] . '</a><ul>';
	foreach($menus[$category] as $link) {
		$target_blank = $link['target_blank'] ?? '';
		$style_color = $link['style_color'] ?? '';

		echo '<li><a href="' . $link['link_full'] . '" ' . $target_blank . ' ' . $style_color . '>' . $link['name'] . '</a></li>';
	}

	echo '</ul></li>';
}
?>
