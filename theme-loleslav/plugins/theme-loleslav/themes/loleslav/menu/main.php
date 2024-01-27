<?php
defined('MYAAC') or die('Direct access not allowed!');
?>
<div id="box6" class="box-style2">
	<h2 class="title">Home</h2>
	<ul class="list1">
		<li><a href="<?php echo getLink('news'); ?>">Latest News</a></li>
		<li><a href="<?php echo getLink('news/archive'); ?>">News Archive</a></li>
		<li><a href="<?php echo getLink('downloads'); ?>">Downloads</a></li>
		<li><a href="<?php echo getLink('rules'); ?>">Rules</a></li>
		<li><a href="<?php echo getLink('team'); ?>">Support</a></li>
		<li><a href="<?php echo getLink('faq'); ?>">FAQ</a></li>
	<?php
		if(admin()): ?>
			<li><a href="<?= ADMIN_URL; ?>"><b><font color="red">Admin Panel</font></b></a></li>';
		<?php endif; ?>
	</ul>
</div>

<div id="box7" class="box-style2">
	<h2 class="title">Community</h2>
	<ul class="list1">
		<li><a href="<?php echo getLink('characters'); ?>">Characters</a></li>
		<li><a href="<?php echo getLink('online'); ?>">Online</a></li>
		<li><a href="<?php echo getLink('highscores'); ?>">Highscores</a></li>
		<li><a href="<?php echo getLink('last-kills'); ?>">Last Kills</a></li>
		<li><a href="<?php echo getLink('houses'); ?>">Houses</a></li>
		<li><a href="<?php echo getLink('guilds'); ?>">Guilds</a></li>
		<li><a href="<?php echo getLink('bans'); ?>">Bans</a></li>
		<li><?php echo $template['link_forum']; ?>Forum</a></li>
	</ul>
</div>
<div id="box8" class="box-style2">
	<h2 class="title">Library</h2>
	<ul class="list1">
		<li><a href="<?php echo getLink('creatures'); ?>">Creatures</a></li>
		<li><a href="<?php echo getLink('spells'); ?>">Spells</a></li>
		<li><a href="<?php echo getLink('commands'); ?>">Commands</a></li>
		<li><a href="<?php echo getLink('server-info'); ?>">Server Info</a></li>
		<li><a href="<?php echo getLink('gallery'); ?>">Gallery</a></li>
		<li><a href="<?php echo getLink('exp-table'); ?>">Experience Table</a></li>
	</ul>
</div>
<div id="box9" class="box-style2">
	<h2 class="title">Highscores</h2>
	<ul class="list1">
		<li><a href="<?php echo getLink('highscores/experience'); ?>">Level</a></li>
		<li><a href="<?php echo getLink('highscores/magic'); ?>">Magic</a></li>
		<li><a href="<?php echo getLink('highscores/shielding'); ?>">Shielding</a></li>
		<li><a href="<?php echo getLink('highscores/distance'); ?>">Distance</a></li>
		<li><a href="<?php echo getLink('highscores/club'); ?>">Club</a></li>
		<li><a href="<?php echo getLink('highscores/sword'); ?>">Sword</a></li>
		<li><a href="<?php echo getLink('highscores/axe'); ?>">Axe</a></li>
		<li><a href="<?php echo getLink('highscores/fist'); ?>">Fist</a></li>
		<li><a href="<?php echo getLink('highscores/fishing'); ?>">Fishing</a></li>
	</ul>
</div>
<?php if($config['gifts_system']): ?>
<div id="box9" class="box-style2">
	<h2 class="title">Shop</h2>
	<ul class="list1">
		<li><a href="<?php echo getLink('points'); ?>">Buy points</a></li>
		<li><a href="<?php echo getLink('gifts'); ?>">Gifts</a></li>
		<li><a href="<?php echo getLink('gifts/history'); ?>">History</a></li>
	</ul>
</div>
<?php endif; ?>
