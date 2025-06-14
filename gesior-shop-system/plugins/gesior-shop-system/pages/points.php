<?php
/**
 * This is shop system taken from Gesior, modified for MyAAC.
 *
 * @name      myaac-gesior-shop-system
 * @author    Gesior <jerzyskalski@wp.pl>
 * @author    Slawkens <slawkens@gmail.com>
 * @website   github.com/slawkens/myaac-gesior-shop-system
 */
defined('MYAAC') or die('Direct access not allowed!');
$title = 'Points';

csrfProtect();

require_once(PLUGINS . 'gesior-shop-system/libs/shop-system.php');
require_once(PLUGINS . 'gesior-shop-system/config.php');
$twig->addGlobal('config', $config);

if(!$config['gifts_system'])
{
	if(!admin())
	{
		echo 'The gifts system is disabled there, sorry.';
		return;
	}
	else
		warning("You're able to access this page but it is disabled for normal users.<br/>
		Its enabled for you so you can view/edit shop offers before displaying them to users.<br/>
		You can enable it by editing this line in myaac config.local.php file:<br/>
		<p style=\"margin-left: 3em;\"><b>\$config['gifts_system'] = true;</b></p>");
}

if(GesiorShop::getDonationType() == 'coins' && !$db->hasColumn('accounts', 'coins')) {
	error("Your server doesn't support accounts.coins. Please change back config.donation_type to points.");
	return;
}

if(!$logged) {
	$was_before = $config['friendly_urls'];
	$config['friendly_urls'] = true;

	echo 'To buy points you need to be logged. ' . generateLink(getLink('account/manage') . '?redirect=' . urlencode(getLink('points')), 'Login first') . '.';

	$config['friendly_urls'] = $was_before;
	return;
}

$enabled = array(
	'paypal' => isset($config['paypal']) && $config['paypal']['enabled'],
	'stripe' => isset($config['stripe']) && $config['stripe']['enabled'],
	'fortumo' => isset($config['fortumo']) && $config['fortumo']['enabled'],
	'cryptobox' => isset($config['cryptobox']) && $config['cryptobox']['enabled'],
	'hotpay' => isset($config['hotpay']) && $config['hotpay']['enabled'],
);

if(isset($_GET['system'])) {
	$system = $_GET['system'];
	$to_load = $system;

	if(isset($_GET['redirect'])) {
		$to_load = $system . '_redirect';
	}

	if(!ctype_alnum(str_replace(array('-', '_'), '', $_GET['system']))) {
		error('Error: System contains illegal characters.');
	}
	else {
		$file = PLUGINS . 'gesior-shop-system/payments/' . $to_load . '.php';

		$args = ['file' => $file, 'enabled' => $enabled, 'system' => $system];
		$hooks->triggerFilter(HOOK_GESIOR_SHOP_ENABLED, $args);

		$file = $args['file'];
		$enabled = $args['enabled'];
		$system = $args['system'];

		if(file_exists($file) && $enabled[$system]) {
			require($file);
		}
	}
}
else {
	$gateways = [];
	foreach ($enabled as $key => $value) {
		if (!$value) {
			continue;
		}

		$gateways[] = $twig->render('gesior-shop-system/templates/gateways/' . $key . '.html.twig');
	}

	$args = ['gateways' => $gateways];
	$hooks->triggerFilter(HOOK_GESIOR_SHOP_GATEWAYS, $args);
	$gateways = $args['gateways'];

	echo $twig->render('gesior-shop-system/templates/points.html.twig', [
		'gateways' => $gateways,
	]);
}
?>
<script type="text/javascript">
$(function() {
	$('#account-name-input').trigger('focus');
});
</script>
