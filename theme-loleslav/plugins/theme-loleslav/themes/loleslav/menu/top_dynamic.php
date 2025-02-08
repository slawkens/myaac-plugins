<?php
defined('MYAAC') or die('Direct access not allowed!');
?>
<div id="menu">
	<ul class="container">
<?php

$menus = get_template_menus();
$i = 0;
foreach($menus[0] as $menu) {
	echo '<li' . ($i++ == 0 ? ' class="first"' : '') . '><a href="' . $menu['link_full'] . '" ' . $menu['target_blank']  . ' ' .
		(
		empty($menu['color']) ? '' : 'style="color: #' . $menu['color'] . ';"'
		)
		. '>' . $menu['name'] . '</a></li>';
}
?>
	</ul>
</div>
