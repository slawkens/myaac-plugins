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

			<span class="sitename"><a href="index.php"><font color="green"><?php echo ucwords($config['lua']['serverName']); ?></font></a></span>
			<div class="slogan"><font color="black"><b>IP:</b> <?php echo configLua('ip'); ?></font></div>

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
					<li><a href="<?= getLink('account/manage'); ?>"><b><font size="1">My Account</font></b></a></li>
					<li><a href="<?= getLink('account/logout'); ?>"><b><font size="1">Logout</font></b></a></li>
				<?php else: ?>
					<li><a href="<?= getLink('account/manage'); ?>"><b><font size="1">Login</font></b></a></li>
					<li><a href="<?= getLink('account/create'); ?>"><b><font size="1" color="red">Create Account</font></b></a></li>
					<li><a href="<?= getLink('account/lost'); ?>"><b><font size="1">Lost Account</font></b></a></li>
				<?php endif; ?>

				<li><a href="<?= getLink('rules'); ?>"><b><font size="1">Server Rules</font></b></a></li>
				<li><a href="<?= getLink('downloads'); ?>"><b><font size="1">Download</font></b></a></li>
			</ul>

			<h1><span style="color: green">Community</span></h1>
			<ul>
				<li><a href="<?= getLink('characters'); ?>"><b><font size="1">Characters</font></b></a></li>
				<li><a href="<?= getLink('guilds'); ?>"><b><font size="1">Guilds</font></b></a></li>
				<li><a href="<?= getLink('highscores'); ?>"><b><font size="1">Highscores</font></b></a></li>
				<li><a href="<?= getLink('last-kills'); ?>"><b><font size="1">Last Deaths</font></b></a></li>
				<li><a href="<?= getLink('houses'); ?>"><b><font size="1">Houses</font></b></a></li>
				<?php if(config('forum') != ''):
					if(config('forum') == 'site'): ?>
						<li><a href="<?php echo internalLayoutLink('forum'); ?>"><b><font size="1">Forum</font></b></a></li>
					<?php else: ?>
						<li><a href="<?php echo config('forum'); ?>" target="_blank"><b><font size="1">Forum</font></b></a></li>
					<?php endif; ?>
				<?php endif; ?>
				<li><a href="<?= getLink('bans'); ?>'"><b><font size="1">Bans</font></b></a></li>
				<li><a href="<?= getLink('team'); ?>"><b><font size="1">Team</font></b></a></li>
			</ul>
			<h1><span style="color: green">Library</span></h1>
			<ul>
				<li><a href="<?= getLink('creatures'); ?>"><b><font size="1">Creatures</font></b></a></li>
				<li><a href="<?= getLink('spells'); ?>"><b><font size="1">Spells</font></b></a></li>
				<li><a href="<?= getLink('commands'); ?>"><b><font size="1">Commands</font></b></a></li>
				<li><a href="<?= getLink('exp-stages'); ?>"><b><font size="1">Exp stages</font></b></a></li>
				<li><a href="<?= getLink('gallery'); ?>"><b><font size="1">Gallery</font></b></a></li>
				<li><a href="<?= getLink('server-info'); ?>"><b><font size="1">Server info</font></b></a></li>
				<li><a href="<?= getLink('exp-table'); ?>"><b><font size="1">Experience table</font></b></a></li>
			</ul>

<?php if($config['gifts_system']): ?>
			<h1><span style="color: green">Shop</span></h1>
			<ul>
				<li><a href="<?= getLink('points'); ?>"><b><font size="1" color="red"><blink>Buy Premium Points</blink></font></b></a></li>
				<li><a href="<?= getLink('gifts'); ?>"><b><font size="1">Shop Offer</font></b></a></li>
				<?php if($logged): ?>
					<li><a href="<?= getLink('gifts/history'); ?>"><b><font size="1">Shop History</font></b></a></li>
				<?php endif; ?>
			</ul>
<?php endif; ?>
<?php if(config('template_allow_change'))
echo '
			<h1><font color="green">Change template</font></h1>
			<ul>
				<li><center>' . template_form() . '
				</center></li>
			</ul>';
?>
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
