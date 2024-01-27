<?php
defined('MYAAC') or die('Direct access not allowed!');
?>
<div id="menu-top">News</div>
<div id="menu-cnt">
	<ul>
		<li>
			<ul>
				<li><a href="<?= getLink('news'); ?>">Latest News</a></li>
				<li><a href="<?= getLink('news/archive'); ?>">News Archive</a></li>
			</ul>
		</li>
	</ul>
</div>
<div id="menu-top">Account</div>
<div id="menu-cnt">
	<ul>
		<li>
			<ul>
				<?php if($logged): ?>
					<li><a href="<?= getLink('account/manage'); ?>">My Account</a></li>
					<li><a href="<?= getLink('account/logout'); ?>">Logout</a></li>
				<?php else: ?>
					<li><a href="<?= getLink('account/manage'); ?>">Login</a></li>
					<li><a href="<?= getLink('account/create'); ?>">Create Account</a></li>
					<li><a href="<?= getLink('account/lost'); ?>">Lost Account</a></li>
				<?php endif; ?>
				<li><a href="<?= getLink('rules'); ?>">Server Rules</a></li>
			</ul>
		</li>
	</ul>
</div>
<div id="menu-top">Community</div>
<div id="menu-cnt">
	<ul>
		<li>
			<ul>
				<li><a href="<?= getLink('characters'); ?>">Characters</a></li>
				<li><a href="<?= getLink('online'); ?>">Who is online?</a></li>
				<li><a href="<?= getLink('highscores'); ?>">Highscores</a></li>
				<li><a href="<?= getLink('last-kills'); ?>">Last Kills</a></li>
				<li><a href="<?= getLink('houses'); ?>">Houses</a></li>
				<li><a href="<?= getLink('guilds'); ?>">Guilds</a></li>
				<li><a href="<?= getLink('bans'); ?>">Bans</a></li>
				<?php
				if(config('forum') != ''):
					if(config('forum') == 'site'): ?>
						<li><a href="<?php echo internalLayoutLink('forum'); ?>">Forum</a></li>
					<?php else: ?>
						<li><a href="<?php echo config('forum'); ?>" target="_blank">Forum</a></li>
					<?php endif; ?>
				<?php endif; ?>
				<li><a href="<?= getLink('team'); ?>">Team</a></li>
			</ul>
		</li>
	</ul>
</div>
<div id="menu-top">Library</div>
<div id="menu-cnt">
	<ul>
		<li>
			<ul>
				<li><a href="<?= getLink('monsters'); ?>">Monsters</a></li>
				<li><a href="<?= getLink('spells'); ?>">Spells</a></li>
				<li><a href="<?= getLink('commands'); ?>">Commands</a></li>
				<li><a href="<?= getLink('server-info'); ?>">Server Info</a></li>
				<li><a href="<?= getLink('downloads'); ?>">Downloads</a></li>
				<li><a href="<?= getLink('gallery'); ?>">Gallery</a></li>
				<li><a href="<?= getLink('exp-table'); ?>">Exp table</a></li>
				<li><a href="<?= getLink('exp-stages'); ?>">Exp stages</a></li>
				<li><a href="<?= getLink('faq'); ?>">FAQ</a></li>
			</ul>
		</li>
	</ul>
</div>
<?php if(config('gifts_system')): ?>
	<div id="menu-top">Shop</div>
	<div id="menu-cnt">
		<ul>
			<li>
				<ul>
					<li><a href="<?= getLink('points'); ?>">Points</a></li>
					<li><a href="<?= getLink('gifts'); ?>">Gifts</a></li>
					<?php if($logged): ?>
						<li><a href="<?= getLink('gifts/history'); ?>">Shop History</a></li>
					<?php endif; ?>
				</ul>
			</li>
		</ul>
	</div>
<?php endif; ?>
