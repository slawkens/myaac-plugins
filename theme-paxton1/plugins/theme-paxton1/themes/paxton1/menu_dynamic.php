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
	foreach($menu as $_menu) { ?>
			<li><a href="<?= $_menu['link_full']; ?>" <?= ($_menu['blank'] ? ' target="_blank"' : '');
			?>><?= $_menu['name']; ?></a></li>
<?php
	}
?>
			</ul>
		</li>
	</ul>
</div>
<?php
}
