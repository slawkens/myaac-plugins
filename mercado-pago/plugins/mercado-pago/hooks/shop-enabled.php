<?php
defined('MYAAC') or die('Direct access not allowed!');

$configMercadoPago = config('mercado-pago');

if (!$configMercadoPago['enabled']) {
	return;
}


if ($args['system'] != 'mercado-pago') {
	return;
}

$args['enabled']['mercado-pago'] = true;
$args['file'] = PLUGINS . 'mercado-pago/payments/mercado-pago.php';
