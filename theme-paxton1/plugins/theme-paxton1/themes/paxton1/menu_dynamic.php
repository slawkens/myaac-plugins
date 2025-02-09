<?php
defined('MYAAC') or die('Direct access not allowed!');

$menus = get_template_menus();

foreach($menus as $category => $menu) {
	if(!isset($menus[$category]) || ($category == MENU_CATEGORY_SHOP && !config('gifts_system'))) {
		continue;
	}
?>
	<div id="menu-top"><?= config('menu_categories')[$category]['name']; ?></div>
<div id="menu-cnt">
	<ul>
		<li>
			<ul>
<?php
	foreach($menu as $link) {
		$target_blank = $link['target_blank'] ?? '';
		$style_color = $link['style_color'] ?? '';

		echo '<li><a href="' . $link['link_full'] . '" ' . $target_blank . ' ' . $style_color . '>' . $link['name'] . '</a></li>';
	}
?>
			</ul>
		</li>
	</ul>
</div>
<?php
}
