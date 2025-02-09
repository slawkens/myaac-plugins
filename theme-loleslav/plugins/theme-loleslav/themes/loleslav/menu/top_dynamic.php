<?php
defined('MYAAC') or die('Direct access not allowed!');
?>
<div id="menu">
	<ul class="container">
<?php

$menus = get_template_menus();
$i = 0;
foreach($menus[0] as $link) {
	$target_blank = $link['target_blank'] ?? '';
	$style_color = $link['style_color'] ?? '';

	echo '<li' . ($i++ == 0 ? ' class="first"' : '') . '><a href="' . $link['link_full'] . '" ' . $link['target_blank']  . ' ' . $style_color . '>' . $link['name'] . '</a></li>';
}
?>
	</ul>
</div>
