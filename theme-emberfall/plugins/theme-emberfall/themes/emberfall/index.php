<?php
defined('MYAAC') or die('Direct access not allowed!');

/**
 * @var string $template_name
 */

use MyAAC\Plugins;
use MyAAC\Cache\Cache;

$menus = get_template_menus();
if (count($menus) === 0) {
	Plugins::installMenus($template_name, config('menus'));

	// clear cache before calling the get_template_menus function again
	$cache = Cache::getInstance();
	$cache->delete('template_menus_' . $template_name);

	$menus = get_template_menus();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<?php echo template_place_holder('head_start'); ?>

	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@500;700&family=Lato:wght@400;700&display=swap" rel="stylesheet">
	<link rel="stylesheet" href="<?= $template_path; ?>/style.css" />
	<script type="text/javascript">
		const IMAGES = "<?= $template_path; ?>/images";
	</script>

	<?php echo template_place_holder('head_end'); ?>
</head>
<body>
	<?php echo template_place_holder('body_start'); ?>

	<div id="ef-wrapper">
		<header id="ef-header">
			<a href="<?= getLink(''); ?>" class="ef-logo"><?= configLua('serverName'); ?></a>
			<div class="ef-tagline">Forge your legend</div>

			<div class="ef-user">
				<?php if ($logged): ?>
					<a class="ef-btn" href="<?= getLink('account/manage'); ?>">My Account</a>
					<a class="ef-btn ef-btn-red" href="<?= getLink('account/logout'); ?>">Logout</a>
				<?php else: ?>
					<a class="ef-btn" href="<?= getLink('account/manage'); ?>">Login</a>
					<a class="ef-btn ef-btn-gold" href="<?= getLink('account/create'); ?>">Create Account</a>
				<?php endif; ?>
			</div>
		</header>

		<div id="ef-body">
			<aside id="ef-sidebar">
				<form class="ef-search" action="<?= getLink('characters'); ?>" method="post">
					<input name="name" type="search" placeholder="Search character..." aria-label="Search character">
				</form>
				<?php include __DIR__ . '/menu.php'; ?>
			</aside>

			<main id="ef-content">
				<div class="ef-panel">
					<h2 class="ef-panel-title"><?= $title ?? ucfirst(str_replace(['/', '-', '_'], ' ', PAGE)); ?></h2>
					<div class="ef-panel-body">
						<?php echo tickers() . template_place_holder('center_top') . $content; ?>
					</div>
				</div>
			</main>
		</div>

		<footer id="ef-footer">
			<?php echo template_footer(); ?>
			<?php if (setting('core.template_allow_change')): ?>
				<br/><br/>Change template: <?= template_form(); ?>
			<?php endif; ?>
		</footer>
	</div>

	<?php echo template_place_holder('body_end'); ?>
</body>
</html>
