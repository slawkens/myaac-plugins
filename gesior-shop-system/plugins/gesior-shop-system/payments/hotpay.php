<?php
defined('MYAAC') or die('Direct access not allowed!');

require_once PLUGINS . 'gesior-shop-system/libs/shop-system.php';
require_once PLUGINS . 'gesior-shop-system/config.php';

$configHotpay = config('hotpay');
if(!isset($configHotpay) || !$configHotpay['enabled'] || !count($configHotpay['options'])) {
	die('HotPay disabled.');
}

/**
 * @var bool $logged
 */
if (!$logged) {
	echo 'You are not logged in. Login first to buy points.';
	return;
}

echo '<h2>HotPay.pl payments</h2><br>';

/**
 * @var OTS_Account $account_logged
 */
echo '<table class="myaac-table" style="width:100%"><tr><td colspan="2"><b>Select offer:</b></td></tr>';
foreach ($configHotpay['options'] as $moneyAmount => $points) {
	echo '<tr><td>Buy ' . $points . ' premium points for ' . $moneyAmount . ' PLN</td><td style="text-align:center">';

	$form = [
		'SEKRET' => $configHotpay['sekret_uslugi'],
		'NAZWA_USLUGI' => $configHotpay['nazwa_uslugi'],
		'ADRES_WWW' => $configHotpay['adres_powrotu'],
		'KWOTA' => $moneyAmount,
		'ID_ZAMOWIENIA' => $account_logged->getID(),
		'EMAIL' => $account_logged->getEMail(),
		'DANE_OSOBOWE' => "",
	];
	$hash = hash(
		'sha256',
		$configHotpay['haslo_z_ustawien'] . ';' .
		$form['KWOTA'] . ";" .
		$form['NAZWA_USLUGI'] . ";" .
		$form['ADRES_WWW'] . ";" .
		$form['ID_ZAMOWIENIA'] . ";" .
		$form['SEKRET']
	);

	echo '<form id="order" action="https://platnosc.hotpay.pl/" method="post">';
	foreach ($form as $key => $value) {
		echo '<input name="' . $key . '" value="' . $value . '" type="hidden">';
	}

	echo '<input name="HASH" required value="' . $hash . '" type="hidden">';
	echo '<button type="submit">Pay</button></form>';

	echo '</td></tr>';
}
echo '</table>';
