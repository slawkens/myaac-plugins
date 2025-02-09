<div class="sidebar">
	<h2>Welcome, <?php echo (USE_ACCOUNT_NAME ? $account_logged->getName() : $account_logged->getNumber()); ?>.</h2>
	<div class="inner">
		<ul>
			<?php
				foreach($menus[7] as $menu) {
					if (strpos(trim($menu['link']), 'http') === 0) {
						echo '<li><a href="' . $menu['link'] . '" target="_blank">' . $menu['name'] . '</a></li>';
					} else {
						if($menu['link'] != 'account/register' || empty($account_logged->getCustomField('key'))) {
							echo '<li><a href="' . getLink($menu['link']) . '">' . $menu['name'] . '</a></li>';
						}
					}
				}
			?>
		</ul>
	</div>
</div>
