<?php
defined('MYAAC') or die('Direct access not allowed!');

$menus = get_template_menus();
foreach(config('menu_categories') as $id => $cat) {
	if(!isset($menus[$id]) || ($id == MENU_CATEGORY_SHOP && !setting('core.gifts_system'))) { // ignore Account Menu and
		// shop system if disabled
		continue;
	}

	echo '<div id="' . $cat['id'] . '">
	<div class="button" onclick="menuAction(\'' . $cat['id'] . '\');">
		<div id="' . $cat['id'] . '_Status" class="status" style="background-image: url(' . $template_path . '/images/menu/open.png);"></div>
		<div id="' . $cat['id'] . '_Icon" class="icon" style="background-image: url(' . $template_path . '/images/menu/' . $cat['id'] . '_icon.png);"></div>
		<div id="' . $cat['id'] . '_Name" class="name" style="background-image: url(' . $template_path . '/images/menu/' . $cat['id'] . '.png);"></div>
	</div>
	<div id="' . $cat['id'] . '_Submenu">
		<div class="submenu">
			<ul>';
	foreach($menus[$id] as $link) {
		$target_blank = $link['target_blank'] ?? '';
		$style_color = $link['style_color'] ?? '';

		echo '<li class="menu-item"><a href="' . $link['link_full'] . '" ' . $target_blank . ' ' . $style_color .	'>' . $link['name'] . '</a></li>';
	}

	echo '</ul>
		</div>
	</div>
</div>';
}
?>
