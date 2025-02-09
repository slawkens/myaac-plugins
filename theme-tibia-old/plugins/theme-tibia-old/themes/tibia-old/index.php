<!DOCTYPE html>
<html lang="en">
<head>
	<?php echo template_place_holder('head_start'); ?>
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<link rel="icon" type="image/png" href="<?php echo $template_path; ?>/images/favicon.png">

	<link href="<?php echo $template_path; ?>/css/app.css" rel="stylesheet" media="all">
	<link href="<?php echo $template_path; ?>/css/trumbowyg.min.css" rel="stylesheet" media="all">

	<?php echo template_place_holder('head_end'); ?>
</head>
<body>
<?php echo template_place_holder('body_start'); ?>
<section id="pandaac">
	<header id="header">
		<a href="<?php getLink('news'); ?>"><img src="<?php echo $template_path; ?>/images/header-left.png" alt="Tibia"></a>
	</header>

	<aside id="topbar">
		<?php
			require_once(__DIR__ . '/menu_top_dynamic.php');
		?>
	</aside>

	<div id="content-container">
		<aside id="sidebar">
			<section id="sidebar-links">
				<div class="line"></div>
				<div class="line wide"></div>

				<?php
				require_once(__DIR__ . '/menu_left_dynamic.php');

				if ($logged) { ?>
					<div class="line wide"></div>
					<?php echo $twig->render('widgets/logged-in.html.twig');
					if (admin()) include __DIR__ . '/widgets/admin.php';
				}
				?>
				<div class="line wide"></div>
				<div class="line"></div>
			</section>

			<section id="sidebar-misc">
				<?php include __DIR__ . '/widgets/server-info.php'; ?>
				<?php include __DIR__ . '/widgets/top-players.php'; ?>
			</section>
		</aside>

		<div id="main-container">
			<main id="main">
				<div id="content">
<?php echo tickers() . template_place_holder('center_top') . $content; ?>
				</div>
			</main>

			<div id="copyright">
				<p><?php echo template_footer(); ?>
					<br/>Design: <a href="http://www.cipsoft.com">CipSoft GmbH</a></p>
			</div>
		</div>
	</div>
</section>
<?php echo template_place_holder('body_end'); ?>
</body>
</html>
