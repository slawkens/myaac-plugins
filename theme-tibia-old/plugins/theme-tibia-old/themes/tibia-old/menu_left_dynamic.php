<?php
$menus = get_template_menus();
?>
<ul>
<?php
foreach($menus[1] as $menu) {
	// hide shop when disabled
	if(in_array($menu['link'], array('points', 'gifts', 'gifts/history')) && !$config['gifts_system']) {
		continue;
	}
?>
	<li><a href="<?= $menu['full_link']; ?>" <?= ($menu['blank'] ? ' target="_blank"' : ''); ?>><?= $menu['name']; ?></a></li>
<?php
}
?>
</ul>
