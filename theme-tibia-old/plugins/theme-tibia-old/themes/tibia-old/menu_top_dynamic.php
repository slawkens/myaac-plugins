<?php
$menus = get_template_menus();
?>
<ul>
<?php
foreach($menus[2] as $link) {
	// hide shop when disabled
	if(in_array($link['link'], array('points', 'gifts', 'gifts/history')) && !config('gifts_system')) {
		continue;
	}

	$target_blank = $link['target_blank'] ?? '';
	$style_color = $link['style_color'] ?? '';

	echo '<li><a href="' . $link['link_full'] . '" ' . $target_blank . ' ' . $style_color . '>' . $link['name'] . '</a></li>';
}
?>
</ul>
