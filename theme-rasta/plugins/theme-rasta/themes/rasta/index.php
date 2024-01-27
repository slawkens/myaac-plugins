<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
	<?php echo template_place_holder('head_start'); ?>
	<link rel="stylesheet" type="text/css" href="<?php echo $template_path; ?>/default.css" media="screen"/>
	<script type="text/javascript" src="tools/basic.js"></script>
	<?php echo template_place_holder('head_end'); ?>
</head>

<body>
	<?php echo template_place_holder('body_start'); ?>
<div class="outer-container">

<div class="inner-container">

	<div class="header">

		<div class="title">

			<span class="sitename">
				<a href="<?= getLink(''); ?>">
					<span style="color: green"><?php echo ucwords(configLua('serverName')); ?></span>
				</a>
			</span>
			<div class="slogan"><span style="color: black"><b>IP:</b> <?php echo configLua('ip'); ?></span></div>

		</div>

	</div>
	<div class="path">
		<a href="<?= getLink('news'); ?>"><b><span style="color: red">NEWS |</span></b></a>
		<?php if(!$logged): ?>
			<a href="<?= getLink('account/create'); ?>"><span style="color: yellow"><b>| Create Account |</b></span></a>
		<?php endif; ?>

		<a href="<?= getLink('server-info'); ?>"><b><span style="color: green">| Server Info |</span></span></b></a>
		<a href="<?= getLink('monsters'); ?>"><b><span style="color: red">| Monsters |</span></b></a>
		<a href="<?= getLink('spells'); ?>"><b><span style="color: yellow">| Spells |</span></b></a>
		<a href="<?= getLink('online'); ?>"><b><span style="color: green">| Players online: </span></b></a>
		<?php if($status['online']): ?>
			<span style="color: green"><b><?= $status['players']; ?>/<?= $status['playersMax']; ?></b></span>
		<?php else: ?>
			<span style="color: red"><b>OFFLINE</b></span>
		<?php endif; ?>
	</div>

	<div class="main">
		<div class="content">
			<?php echo template_place_holder('center_top') . $content; ?>
		</div>

		<div class="navigation">

			<h1><span style="color: green">Account</span></h1>
			<ul>
				<?php
				if($logged): ?>
					<li><a href="<?= getLink('account/manage'); ?>"><b><span style="font-size: 10px">My Account</span></b></a></li>
					<li><a href="<?= getLink('account/logout'); ?>"><b><span style="font-size: 10px">Logout</span></b></a></li>
				<?php else: ?>
					<li><a href="<?= getLink('account/manage'); ?>"><b><span style="font-size: 10px">Login</span></b></a></li>
					<li><a href="<?= getLink('account/create'); ?>"><b><span style="font-size: 10px; color: red">CreateAccount</span></b></a></li>
					<li><a href="<?= getLink('account/lost'); ?>"><b><span style="font-size: 10px">Lost Account</span></b></a></li>
				<?php endif; ?>

				<li><a href="<?= getLink('rules'); ?>"><b><span style="font-size: 10px">Server Rules</span></b></a></li>
				<li><a href="<?= getLink('downloads'); ?>"><b><span style="font-size: 10px">Download</span></b></a></li>
			</ul>

			<h1><span style="color: green">Community</span></h1>
			<ul>
				<li><a href="<?= getLink('characters'); ?>"><b><span style="font-size: 10px">Characters</b></a></li>
				<li><a href="<?= getLink('guilds'); ?>"><b><span style="font-size: 10px">Guilds</span></b></a></li>
				<li><a href="<?= getLink('highscores'); ?>"><b><span style="font-size: 10px">Highscores</span></b></a></li>
				<li><a href="<?= getLink('last-kills'); ?>"><b><span style="font-size: 10px">Last Deaths</span></b></a></li>
				<li><a href="<?= getLink('houses'); ?>"><b><span style="font-size: 10px">Houses</span></b></a></li>
				<?php if(config('forum') != ''):
					if(config('forum') == 'site'): ?>
						<li><a href="<?php echo getLink('forum'); ?>"><b><span style="font-size: 10px">Forum</span></b></a></li>
					<?php else: ?>
						<li><a href="<?php echo config('forum'); ?>" target="_blank"><b><span style="font-size: 10px">Forum</span></b></a></li>
					<?php endif; ?>
				<?php endif; ?>
				<li><a href="<?= getLink('bans'); ?>"><b><span style="font-size: 10px">Bans</span></b></a></li>
				<li><a href="<?= getLink('team'); ?>"><b><span style="font-size: 10px">Team</span></b></a></li>
			</ul>
			<h1><span style="color: green">Library</span></h1>
			<ul>
				<li><a href="<?= getLink('monsters'); ?>"><b><span style="font-size: 10px">Monsters</span></b></a></li>
				<li><a href="<?= getLink('spells'); ?>"><b><span style="font-size: 10px">Spells</span></b></a></li>
				<li><a href="<?= getLink('commands'); ?>"><b><span style="font-size: 10px">Commands</span></b></a></li>
				<li><a href="<?= getLink('exp-stages'); ?>"><b><span style="font-size: 10px">Exp stages</span></b></a></li>
				<li><a href="<?= getLink('gallery'); ?>"><b><span style="font-size: 10px">Gallery</span></b></a></li>
				<li><a href="<?= getLink('server-info'); ?>"><b><span style="font-size: 10px">Server info</span></b></a></li>
				<li>
					<a href="<?= getLink('exp-table'); ?>">
						<b>
							<span style="font-size: 10px">Exp Table</span>
						</b>
					</a>
				</li>
			</ul>

			<?php if(config('gifts_system')): ?>
				<h1><span style="color: green">Shop</span></h1>
				<ul>
					<li><a href="<?= getLink('points'); ?>">
							<b>
								<span style="font-size: 10px; color: red">Buy Premium Points</span>
							</b>
						</a>
					</li>
					<li><a href="<?= getLink('gifts'); ?>"><b><span style="font-size: 10px">Shop Offer</span></b></a></li>
					<?php if($logged): ?>
						<li><a href="<?= getLink('gifts/history'); ?>"><b><span style="font-size: 10px">Shop History</span></b></a></li>
					<?php endif; ?>
				</ul>
			<?php endif; ?>
			<?php if(config('template_allow_change')): ?>
				<h1><span style="color: green">Change template</span></h1>
				<ul>
					<li>
						<div style="text-align: center"><?= template_form(); ?></div>
					</li>
			</ul>
			<?php endif; ?>
		</div>

		<div class="clearer">&nbsp;</div>

	</div>

	<div class="footer">
		<span class="left"><?php echo template_footer(); ?></span>

		<span class="right">

			<a href="http://templates.arcsin.se">Website template</a> by <a href="http://arcsin.se">Arcsin</a>

		</span>

		<div class="clearer"></div>

	</div>

</div>

</div>
	<?php echo template_place_holder('body_end'); ?>
</body>
</html>
