<?php
defined('MYAAC') or die('Direct access not allowed!');

foreach($menus as $category => $menu) {
	if(!isset($menus[$category]) || $category == 0 || ($category == 5 && !config('gifts_system'))) { // ignore Top
		// Menu and shop system if disabled
		continue;
	}
?>
<div id="box9" class="box-style2">
	<h2 class="title"><?php echo config('menu_categories')[$category]['name']; ?></h2>
	<ul class="list1">
<?php
	foreach($menus[$category] as $link) {
		$target_blank = $link['target_blank'] ?? '';
		$style_color = $link['style_color'] ?? '';

		echo '<li><a href="' . $link['link_full'] . '" ' . $target_blank . ' ' . $style_color . '>' . $link['name'] . '</a></li>';
	}
?>
	</ul>
</div>
<?php
}
?>
