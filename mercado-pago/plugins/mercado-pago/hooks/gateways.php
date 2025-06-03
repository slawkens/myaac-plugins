<?php
defined('MYAAC') or die('Direct access not allowed!');

$configMercadoPago = config('mercado-pago');

if (!$configMercadoPago['enabled']) {
	return;
}

array_unshift(
	$args['gateways'],
	$twig->render('mercado-pago/views/mercado-pago.html.twig')
);
