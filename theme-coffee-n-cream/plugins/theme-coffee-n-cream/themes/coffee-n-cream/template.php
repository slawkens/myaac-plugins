<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
	<?php echo template_place_holder('head_start'); ?>
	<link rel="stylesheet" type="text/css" href="<?php echo $template_path; ?>/style.css" media="screen"/>
	<link rel="stylesheet" type="text/css" href="<?php echo $template_path; ?>/default.css" />
	<script type="text/javascript" src="tools/basic.js"></script>
	<?php echo template_place_holder('head_end'); ?>
</head>

<body>
<?php echo template_place_holder('body_start'); ?>
<div class="container">

	<div class="header">

		<div class="title">
			<h1><a href="<?= getLink(''); ?>"><?php echo ucwords(configLua('serverName')); ?></a></h1>
		</div>

	</div>

	<div class="navigation">
		<a href="<?= getLink('news'); ?>"><b>NEWS |</b></a>
		<a href="<?= getLink('server-info'); ?>">| Server Info |</a>
		<a href="<?= getLink('monsters'); ?>">| Monsters |</a>
		<a href="<?= getLink('spells'); ?>">| Spells |</a>
		<a href="<?= getLink('online'); ?>">| Players online: </a>

		<?php if($status['online'])
			echo '<FONT color="green"><b>' . $status['players'] . '/' . $status['playersMax'] . '</b></FONT>';
		else
			echo '<FONT color="red"><b>OFFLINE</b></FONT>';
		?>
			<div class="clearer"><span></span></div>
		</div>

	<div class="main">

		<div class="content">
			<?php echo template_place_holder('center_top') . $content; ?>
		</div>

		<div class="sidenav">

			<h1>Account</h1>
			<ul>
				<?php
				if($logged): ?>
					<li><a href="<?= getLink('account/manage'); ?>">My Account</a></li>
					<li><a href="<?= getLink('account/logout'); ?>">Logout</a></li>
				<?php else: ?>
					<li><a href="<?= getLink('account/manage'); ?>">Login</a></li>
					<li><a href="<?= getLink('account/create'); ?>">Create Account</a></li>
					<li><a href="<?= getLink('account/lost'); ?>">Lost Account</a></li>
				<?php endif; ?>

				<li><a href="<?= getLink('rules'); ?>">Server Rules</a></li>
				<li><a href="<?= getLink('downloads'); ?>">Download</a></li>
			</ul>

			<h1>Community</h1>
			<ul>
				<li><a href="<?= getLink('characters'); ?>">Characters</a></li>
				<li><a href="<?= getLink('guilds'); ?>">Guilds</a></li>
				<li><a href="<?= getLink('highscores'); ?>">Highscores</a></li>
				<li><a href="<?= getLink('last-kills'); ?>">Last Deaths</a></li>
				<li><a href="<?= getLink('houses'); ?>">Houses</a></li>
				<?php if(config('forum') != ''):
					if(config('forum') == 'site'): ?>
						<li><a href="<?= getLink('forum'); ?>">Forum</a></li>
					<?php else: ?>
						<li><a href="<?php echo config('forum'); ?>" target="_blank">Forum</a></li>
					<?php endif; ?>
				<?php endif; ?>
				<li><a href="<?= getLink('bans'); ?>">Bans</a></li>
				<li><a href="<?= getLink('team'); ?>">Team</a></li>
			</ul>

			<h1>Library</h1>
			<ul>
				<li><a href="<?= getLink('monsters'); ?>">Creatures</a></li>
				<li><a href="<?= getLink('spells'); ?>">Spells</a></li>
				<li><a href="<?= getLink('commands'); ?>">Commands</a></li>
				<li><a href="<?= getLink('exp-stages'); ?>">Exp stages</a></li>
				<li><a href="<?= getLink('gallery'); ?>">Gallery</a></li>
				<li><a href="<?= getLink('server-info'); ?>">Server info</a></li>
				<li><a href="<?= getLink('exp-table'); ?>">Exp table</a></li>
			</ul>

			<?php if(config('gifts_system')): ?>
				<h1>Shop</h1>
				<ul>
					<li><a href="<?= getLink('points'); ?>">Buy Premium Points</a></li>
					<li><a href="<?= getLink('gifts'); ?>">Shop Offer</a></li>
					<?php if($logged): ?>
						<li><a href="<?= getLink('gifts/history'); ?>">Shop History</a></li>
					<?php endif; ?>
				</ul>
			<?php endif; ?>
			<?php if(config('template_allow_change'))
				echo '
			<h1>Change template</h1>
			<ul>
				<li><center>' . template_form() . '
				</center></li>
			</ul>';
			?>
		</div>

		<div class="clearer"><span></span></div>

	</div>

	<div class="footer">

		<span class="left"><?php echo template_footer(); ?></span>

		<span class="right"><a href="http://templates.arcsin.se/">Website template</a> by <a href="https://arcsin.se/">Arcsin</a></span>

		<div class="clearer"><span></span></div>

	</div>

</div>
	<?php echo template_place_holder('body_end'); ?>
</body>

</html>
