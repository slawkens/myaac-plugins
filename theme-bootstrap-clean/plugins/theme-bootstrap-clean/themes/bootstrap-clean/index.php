<?php defined('MYAAC') or die('Direct access not allowed!'); ?>

<!DOCTYPE html>
<html lang="en">
<head>
	<?php echo template_place_holder('head_start'); ?>

	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<link rel="stylesheet" href="<?= $template_path; ?>/assets/dist/bootstrap.min.css" />
	<link rel="stylesheet" href="<?= $template_path; ?>/assets/dist/bootstrap-icons.min.css" />
	<link rel="stylesheet" href="<?= $template_path; ?>/assets/style.css"/>

	<?php echo template_place_holder('head_end'); ?>
</head>
<body>
	<?php echo template_place_holder('body_start'); ?>

	<div class="container">
		<header
			class="d-flex flex-wrap align-items-center justify-content-center justify-content-md-between py-3 mb-4 border-bottom">
			<div class="col-md-2 mb-2 mb-md-0">
				<a href="/" class="d-inline-flex link-body-emphasis text-decoration-none">
					<i class="bi bi-fire" style="margin-left: 80px; font-size: 32px;"></i>
				</a>
			</div>

			<div class="form-check form-switch">
				<input class="form-check-input" type="checkbox" id="darkModeSwitch" checked>
				<label class="form-check-label" for="darkModeSwitch">Dark Mode</label>
			</div>

			<?php
			$menus = get_template_menus();

			if (isset($menus[MENU_CATEGORY_TOP])): ?>
			<ul class="nav col-12 col-md-auto mb-2 justify-content-center mb-md-0">
				<?php foreach ($menus[MENU_CATEGORY_TOP] as $menu): ?>
					<li><a href="<?= $menu['link']; ?>" class="nav-link px-2"<?= $menu['target_blank']; ?> <?= $menu['style_color']; ?>><?= $menu['name']; ?></a></li>
				<?php endforeach; ?>
			</ul>
			<?php endif; ?>

			<form class="col-12 col-lg-auto mb-3 mb-lg-0 me-lg-3" role="search" action="<?= getLink('characters');
			?>" method="post">
				<input name="name" type="search" class="form-control" placeholder="Search characters"
					   aria-label="Search">
			</form>

			<div class="col-md-3 text-end">
				<?php if ($logged): ?>
					<a href="<?= getLink('account/manage'); ?>">
						<button type="button" class="btn btn-outline-primary me-2">My Account</button>
					</a>
					<a href="<?= getLink('account/logout'); ?>">
						<button type="button" class="btn btn-danger me-2">Logout</button>
					</a>
				<?php else: ?>
					<a href="<?= getLink('account/manage'); ?>">
						<button type="button" class="btn btn-outline-primary me-2">Login</button>
					</a>
					<a href="<?= getLink('account/create'); ?>">
						<button type="button" class="btn btn-primary">Sign-up</button>
					</a>
				<?php endif; ?>
			</div>
		</header>
	</div>

	<div class="container">
		<header class="text-center text-white">
			<h1 class="bg-secondary mt-3 p-3"><?= configLua('serverName'); ?></h1>
		</header>
		<main>
			<div class="row">
				<div class="col-md-2">
					<?php include __DIR__ . '/menu.php'; ?>
				</div>
				<div class="col-md-10 p-3">
					<?php echo tickers() . template_place_holder('center_top') . $content; ?>
				</div>
			</div>
		</main>

		<footer>

			<div class="text-center text-body-secondary">
				<?php echo template_footer(); ?>
				<?php if(setting('core.template_allow_change')): ?>
					<br/><br/>Change template: <?= template_form(); ?>
				<?php endif; ?>
			</div>

		</footer>
	</div>

	<script src="<?= $template_path; ?>/assets/dist/bootstrap.bundle.min.js"></script>
	<script src="<?= $template_path; ?>/assets/script.js"></script>

	<?php if (PAGE == 'account/lost' || PAGE == 'commands' || PAGE == 'online' || PAGE == 'polls' || str_contains
	(PAGE, 'gifts') || str_contains(PAGE, 'forum/board') ||
		(isset($_REQUEST['subtopic']) && $_REQUEST['subtopic'] == 'forum' && ACTION == 'edit_post')): ?>
	<script src="<?= $template_path; ?>/assets/dark-fixer.js"></script>
	<?php endif; ?>

	<?php echo template_place_holder('body_end'); ?>
</body>
</html>
