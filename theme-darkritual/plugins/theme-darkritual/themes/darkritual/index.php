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
<div class="container">
	<div class="header"><?php echo ucwords($config['lua']['serverName']); ?></div>

	<div class="main_right">
		<div class="padded">
			<h1>Server Info</h1>
			<?php
			if($status['online'])
				echo '<font color="green"><b>Server ONLINE</b></font><br />Players Online: '.$status['players'].' / '.$status['playersMax'].'<br />Monsters: '.$status['monsters'].'<br />Uptime: '.$status['uptimeReadable'].'<br />IP: '.$config['lua']['ip'];
			else
				echo '<font color="red"><b>Server OFFLINE</b></font>';
			?>
			<br /><br /><br /><br /><br /><br /><br />
		</div>
	</div>

	<div class="subnav">
		<h1>News</h1>
		<ul>
			<li><a href="<?= getLink('news'); ?>">Latest News</a></li>
			<li><a href="<?= getLink('news/archive'); ?>">News archive</a></li>
			<li><a href="<?= getLink('changelog'); ?>">Changelog</a></li>
		</ul>

		<h1>Account</h1>
		<ul>
				<?php
				if($logged): ?>
					<li>
						<a href="<?= getLink('account/manage'); ?>">
							<b><span style="color: green">My Account</span></b>
						</a>
					</li>
					<li>
						<a href="<?= getLink('account/logout'); ?>">
							<b><span style="color: white">Logout</span></b>
						</a>
					</li>
				<?php else: ?>
					<li>
						<a href="<?= getLink('account/manage'); ?>">
							<b><span style="color: white">Login</span></b>
						</a>
					</li>
				<?php endif; ?>

				<?php if(!$logged): ?>
					<li><a href="<?= getLink('account/create'); ?>"><b><span style="color: white">Create Account</span></b></a></li>
				<?php endif; ?>

				<li><a href="<?= getLink('account/lost'); ?>">Lost Account Interface</a></li>
				<li><a href="<?= getLink('rules'); ?>">Server Rules</a></li>
		</ul>

		<h1>Community</h1>
		<ul>
			<li><a href="<?= getLink('characters'); ?>">Characters</a></li>
			<li><a href="<?= getLink('online'); ?>">Who is online?</a></li>
			<li><a href="<?= getLink('guilds'); ?>">Guilds</a></li>
			<li><a href="<?= getLink('highscores'); ?>">Highscores</a></li>
			<li><a href="<?= getLink('last-kills'); ?>">Last kills</a></li>
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
			<li><a href="<?= getLink('monsters'); ?>">Monsters</a></li>
			<li><a href="<?= getLink('spells'); ?>">Spells</a></li>
			<li><a href="<?= getLink('commands'); ?>">Commands</a></li>
			<li><a href="<?= getLink('exp-stages'); ?>">Experience stages</a></li>
			<li><a href="<?= getLink('server-info'); ?>">Server Info</a></li>
			<li><a href="<?= getLink('gallery'); ?>">Gallery</a></li>
			<li><a href="<?= getLink('faq'); ?>">FAQ</a></li>
		</ul>

		<?php if($config['gifts_system']): ?>
		<h1>Shop</h1>
		<ul>
			<li>
				<a href="<?= getLink('points'); ?>"><b>Buy Premium Points</font></b></a>
			</li>
			<li>
				<a href="<?= getLink('gifts'); ?>">Shop Offer</a></li>
			<?php if($logged): ?>
			<li><a href="<?= getLink('gifts/history'); ?>">Shop History</a></li>
			<?php endif; ?>
		</ul>
		<?php endif; ?>
	<h1>Change style:</h1>
			<ul>
				<li><center><?php
	if($config['template_allow_change'])
		 echo template_form();
				?></center></li>
			</ul>

	</div>

?>
	<div class="main">
		<div class="padded">
			<?= tickers() . template_place_holder('center_top') ?>
			<?php echo $content; ?>
		</div>
	</div>
	<div class="clearer"><span></span></div>
	<div class="footer">
		<span class="left"><?php echo template_footer(); ?></span>
		<span class="right">Design by <a href="http://arcsin.se/" target="_blank">Arcsin</a> <a href="http://templates.arcsin.se/"target="_blank">Web Templates</a></span>
		<div class="clearer"><span></span></div>
	</div>
</div>
	<?php echo template_place_holder('body_end'); ?>
</body>
</html>
