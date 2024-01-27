<?php
$menus = get_template_menus();
?>
<ul>
<?php
foreach($menus[2] as $menu) {
	// hide shop when disabled
	if(in_array($menu['link'], array('points', 'gifts', 'gifts/history')) && !config('gifts_system')) {
		continue;
	}
?>
	<li><a href="<?= $menu['link_full']; ?>" <?= $menu['target_blank']; ?>><?= $menu['name']; ?></a></li>
<?php
}
?>
</ul>
