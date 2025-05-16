<?php
defined('MYAAC') or die('Direct access not allowed!');

require_once PLUGINS . 'gesior-shop-system/libs/shop-system.php';
require_once PLUGINS . 'gesior-shop-system/config.php';

// debug config problems: file_put_contents(microtime(true) . '.log', json_encode($_POST, JSON_PRETTY_PRINT));

function hotpayValidateRequest($HASLO_Z_USTAWIEN): bool
{
	if (empty($_POST['KWOTA']) ||
		empty($_POST['ID_PLATNOSCI']) ||
		empty($_POST['ID_ZAMOWIENIA']) ||
		empty($_POST['STATUS']) ||
		empty($_POST['SEKRET']) ||
		empty($_POST['SECURE']) ||
		empty($_POST['HASH'])
	) {
		return false;
	}

	return hash(
			'sha256',
			$HASLO_Z_USTAWIEN . ";" .
			$_POST['KWOTA'] . ";" .
			$_POST['ID_PLATNOSCI'] . ";" .
			$_POST['ID_ZAMOWIENIA'] . ";" .
			$_POST['STATUS'] . ";" .
			$_POST['SECURE'] . ";" .
			$_POST['SEKRET']
		) == $_POST['HASH'];
}

$configHotpay = config('hotpay');
if (!hotpayValidateRequest($configHotpay['haslo_z_ustawien'])) {
	exit('invalid hash');
}

$status = $_POST['STATUS'];
$amount = $_POST['KWOTA'];
$transactionId = $_POST['ID_PLATNOSCI'];
$accountId = $_POST['ID_ZAMOWIENIA'];

if ($status !== 'SUCCESS') {
	exit('not successful payment');
}

$paymentAmountPoints = null;
foreach ($configHotpay['options'] as $moneyAmount => $points) {
	if ($amount == $moneyAmount) {
		$paymentAmountPoints = $points;
		$paymentAmountMoney = $moneyAmount;
		break;
	}
}

if (!$paymentAmountPoints) {
	exit('payment config not found');
}

/**
 * @var OTS_DB_MySQL $db
 */
	exit("Duplicated transaction $transactionId");
if($db->select(TABLE_PREFIX . 'hotpay', ['transaction_id' => $transactionId]) !== false) {
}

$account = new OTS_Account();
$account->load($accountId);
if(!$account->isLoaded()) {
	die('account ' . htmlspecialchars($accountId) . ' not found');
}

GesiorShop::changePoints($account, $paymentAmountPoints);

$db->insert(TABLE_PREFIX . 'hotpay',
	[
		'transaction_id' => $transactionId,
		'account_id' => $accountId,
		'price' => $paymentAmountMoney,
		'currency' => 'PLN',
		'points' => $paymentAmountPoints,
		'created' => time(),
	]
);

exit('ok');
