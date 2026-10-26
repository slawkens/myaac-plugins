<?php
defined('MYAAC') or die('Direct access not allowed!');

foreach ($menus as $category => $menu) {
	if (!isset($menu) || ($category == MENU_CATEGORY_SHOP && !setting('core.gifts_system'))) {
		continue;
	}

	$categories = config('menu_categories');
	if (!isset($categories[$category])) {
		continue;
	}
	?>
	<nav class="ef-menu">
		<h3 class="ef-menu-title"><?= $categories[$category]['name']; ?></h3>
		<ul>
			<?php
			foreach ($menu as $link) {
				$active = str_contains(PAGE, $link['link']) ? ' active' : '';
				if ((str_contains(PAGE, 'news/archive') && $link['link'] == 'news') ||
					(str_contains(PAGE, 'gifts/history') && $link['link'] == 'gifts')) {
					$active = '';
				}

				echo '<li><a class="ef-menu-link' . $active . '" href="' . $link['link_full'] . '" ' . $link['target_blank'] . ' ' . $link['style_color'] . '>' . $link['name'] . '</a></li>';
			}
			?>
		</ul>
	</nav>
	<?php
}
