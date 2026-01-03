<?php
defined('MYAAC') or die('Direct access not allowed!');

foreach($menus as $category => $menu) {
	if(!isset($menu) || $category == MENU_CATEGORY_TOP || ($category == MENU_CATEGORY_SHOP && !setting('core.gifts_system')))
	{ //
		// ignore Top
		// Menu and shop system if disabled
		continue;
	}
	?>
	<h3 class="mt-3"><?php echo config('menu_categories')[$category]['name']; ?></h3>
	<div class="list-group">
		<?php
		foreach($menu as $link) {
			$active = (str_contains(PAGE, $link['link']) ? 'active' : '');
			if ((str_contains(PAGE, 'news/archive') && $link['link'] == 'news') ||
				(str_contains(PAGE, 'gifts/history') && $link['link'] == 'gifts')) {
				$active = '';
			}

			echo '<a class="list-group-item list-group-item-action ' . $active . '" href="' . $link['link_full'] . '" ' . $link['target_blank'] . ' ' . $link['style_color'] . '>' . $link['name'] . '</a>';
		}
		?>
	</div>
	<?php
}
?>
