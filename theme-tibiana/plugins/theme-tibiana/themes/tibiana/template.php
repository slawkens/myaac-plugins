<?php
defined('MYAAC') or die('Direct access not allowed!');

$menus = get_template_menus();

if(count($menus) === 0) {
	$text = "Please install the $template_name Theme in Admin Panel, so the menus will be imported too.";
	throw new RuntimeException($text);
}
?>

<!DOCTYPE html>
<html lang="en">
  <head>
		<?= template_place_holder('head_start') ?>
		<link rel="stylesheet" type="text/css" href="<?= $template_path ?>/css/bootstrap.css" />
		<link rel="stylesheet" type="text/css" href="<?= $template_path ?>/css/main.css" />
		<link rel="stylesheet" type="text/css" href="<?= $template_path ?>/css/menu.css" />
		<link rel="stylesheet" type="text/css" href="<?= $template_path ?>/css/default.css" />
		<link href="<?= $template_path ?>/images/favicon.ico" rel="shortcut icon" />
		<?= template_place_holder('head_end') ?>
  </head>
   <body>
   <center><div class="top-bar">
		<a href="<?= getLink('account/create') ?>">
		</a>
	</div></center>
   <?= template_place_holder('body_start') ?>
	<div id="pandaac">
	<div class="sidebar-right">
		<div class="players-online-wrapper">
		  <a href="?subtopic=creatures&creature=<?php echo config('logo_monster') ?>"><span class="c-monster"
																								 style="background: url(/images/monsters/<?= logo_monster() ?>.gif);"></span></a>
		  <div class="players-online-w">
			<div class="players-online-l">
				<?php if($status['online']) { ?>
					<a href="<?= getLink('online') ?>" style="color: lightgreen; text-decoration: none;">ONLINE</a>
				<?php } else { ?>
					<span style="color: red; text-decoration: none;">OFFLINE</span>
				<?php } ?>
			</div>
			 <div class="players-online-n">
			  <span class="p-online" onClick="window.location = '<?= getLink('online') ?>';"><?= $status['players']?>
			   </span> /<span class="p-total"><?= $status['playersMax']?></span>
			</div>
		  </div>
		</div>
		  <div class="box-side-wrapper download-wrapper">
		  <div class="download-wrapper-header register-wrapper-header"></div>
		  <div class="box-side-header"></div>
		  <div class="box-side-middle text-center">
			<a class="martel btn-download btn-criar-conta" href="<?= getLink('account/create') ?>">Register</a>
		  </div>
		  <div class="box-side-footer"></div>
		</div>
		<div class="box-side-wrapper download-wrapper" style="margin-top: 10px;">
		  <div class="download-wrapper-header"></div>
		  <div class="box-side-header"></div>
		  <div class="box-side-middle text-center">
			<a class="martel btn-download" href="<?= getLink('downloads') ?>">Download Client</a>
		  </div>
		  <div class="box-side-footer"></div>
		</div>
		<div class="box-side-wrapper download-wrapper box-face">
		  <div class="network-wrapper-header"></div>
		  <div class="box-side-header"></div>
		  <div class="box-side-middle text-center">
	   <div id="fb-root"></div>
					<div class="bar"></div>
					<span style="font-size: 20px; font-weight: bold;">Server Save in
					<br><span id="sshours"></span>:<span id="ssminutes"></span>:<span id="ssseconds"></span></span>
					<div class="bar"></div>
	   </div>
		  <div class="box-side-footer"></div>
		</div>
	</div>
		<a href="<?= getLink('news'); ?>" id="header"></a>
		<div id="topbar">
			<ul>
				<?= tibiana_get_links(MENU_CATEGORY_TOP) ?>
			</ul>
		</div>
		<div id="content-container">
			<div id="sidebar">
				<div id="sidebar-links">
					<?php if (admin()) { ?>
				 <div class="line"></div>
					<div class="line wide"></div>
						<a href="<?= ADMIN_URL; ?>" target="_blank" class="martel">
							<span style="color: #FF0000">Admin Panel</span></a>
					<?php } ?>
				  <div class="line"></div>
					<div class="line wide"></div>
					<ul class="my-account-sidebar">
					<li>
						<a href="<?= getLink('account/manage') ?>" class="martel">
							<span style="color: #90EE90">My Account</span></a>
						<?php if($logged) { ?>
							<a href="<?= getLink('account/logout') ?>" class="martel">Logout</a>
						<?php } ?>
					<br>
				  </ul>
				   <div class="line"></div>
					<div class="line wide"></div>
					  <ul>
						  <?= tibiana_get_links(MENU_CATEGORY_LEFT_SIDE) ?>
					</ul>
					  <div class="line wide"></div>
					   <div class="line"></div>
					 </div>
				   <div id="sidebar-misc">
						<a href="<?= getLink('online') ?>">
						</a>
					<br>
				</div>
			</div>
			<div id="main">
				<div id="content">
<div>
					<?php
						echo tickers() . template_place_holder('center_top');

						echo $content;

						?>
					</div>

					<div style="clear:both;"></div>
					<br/>
						<tr>
							<img src="<?= $template_path ?>/images/line_body.gif" align="center" height="7"
								 width="100%">
							<td><img src="<?= $template_path ?>/images/blank.gif"></td>
						</tr>

					<div align="center" style="font-face:verdana; font-size:10px">
						<?php echo template_footer();
						if(config('template_allow_change'))
							echo template_form();
						?>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- Bootstrap Core JavaScript -->
	<script src="<?= $template_path ?>/js/bootstrap.min.js"></script>

	<?php
	// Don't touch this, 2524608000 is 1 Jan 2050 in seconds (random day in distant future)
	// *1000 because javascript script (js/serversave.js) works with timestamp in milliseconds
	$save_timestamp = (2524608000 + (($config['save_hour'] * 60 * 60) + ($config['save_minute'] * 60))) * 1000;
	?>
	<script type="text/javascript">
		var target_date =  <?php echo $save_timestamp; ?>;
	</script>

	<!-- Bootstrap Core JavaScript -->
	<script src="<?= $template_path ?>/js/serversave.js"></script>
	<?= template_place_holder('body_end') ?>
</body>
</html>
<?php
function logo_monster()
{
	global $config;
	return str_replace(" ", "", trim(strtolower($config['logo_monster'])));
}

function tibiana_get_links($category)
{
	global $menus;

	$ret = '';
	foreach ($menus[$category] as $link) {
		$target_blank = $link['target_blank'] ?? '';
		$style_color = $link['style_color'] ?? '';

		$ret .= '<li><a href="' . $link['link_full'] . '" ' . $target_blank . ' ' . $style_color . '>' . $link['name'] . '</a></li>';
	}

	return $ret;
}
