<?php
defined('MYAAC') or die('Direct access not allowed!');
?>
<!DOCTYPE HTML>
<html>
<head>
	<?php echo template_place_holder('head_start'); ?>
	<link rel="stylesheet" type="text/css" href="<?php echo $template_path; ?>/css/style.css" />
	<link rel="stylesheet" type="text/css" href="<?php echo $template_path; ?>/css/default.css" />
	<!-- modernizr enables HTML5 elements and feature detects -->
	<script type="text/javascript" src="<?php echo $template_path; ?>/js/modernizr-1.5.min.js"></script>
	<?php echo template_place_holder('head_end'); ?>
</head>

<body>
	<?php echo template_place_holder('body_start'); ?>
	<div id="main">
	<header>
	  <div id="logo">
		<div id="logo_text">
		<h1><a href="<?php echo getLink('news'); ?>">My<span class="logo_colour">AAC</span></a></h1>
		<h2>The best AAC and CMS ever made!</h2>
		</div>
	</div>
	<nav>
		<div id="menu_container">
		  <ul class="sf-menu" id="nav">
			<?php
			require_once(__DIR__ . '/menu_dynamic.php');
			?>
		  </ul>
		</div>
	</nav>
	</header>
	<div id="site_content">
		<div id="sidebar_container">
			<?php
			if($logged) {
				include(__DIR__ . '/widgets/logged-in.php');
			} else {
				include(__DIR__ . '/widgets/login.php');
			}
			if(admin()) {
				include(__DIR__ . '/widgets/admin.php');
			}

			foreach(glob( __DIR__ . '/widgets/*.php') as $file) {
				$filename = pathinfo($file, PATHINFO_FILENAME);
				if($filename != 'login' && $filename != 'logged-in' && $filename != "admin") {
					include($file);
				}
			}
			?>
		</div>
		<div class="content">
			<?php echo template_place_holder('center_top') . $content; ?>
		</div>
	</div>
	<div id="scroll">
		<a title="Scroll to the top" class="top" href="#"><img src="images/top.png" alt="top" /></a>
	</div>
	<footer>
		<p><?php echo template_footer(); ?><br/><a href="http://www.css3templates.co.uk">design from css3templates.co.uk</a></p>
	</footer>
	</div>
	<!-- javascript at the bottom for fast page loading -->
	<script type="text/javascript" src="js/jquery.easing-sooper.js"></script>
	<script type="text/javascript" src="js/jquery.sooperfish.js"></script>
	<script type="text/javascript">
	$(function() {
	 	$('ul.sf-menu').sooperfish();
		$('.top').on('click', function() {$('html, body').animate({scrollTop:0}, 'fast'); return false;});
	});
	</script>
	<?php echo template_place_holder('body_end'); ?>
</body>
</html>
