<?php

/**
 * Automatic Mercadopago payment system gateway.
 *
 * @name      pix-myaac-mercadopago
 * @author    Rafhael Oliveira <rafhaelxd@gmail.com>
 * @author    Laércio Leal <laercioleal97@gmail.com> - Correções para Gesior 7.0 Alpha
 * @website   github.com/thetibiaking/ttk-myaac-plugins
 * @website   github.com/underewarrr/
 * @version   1.0.0
 */

defined('MYAAC') or die('Direct access not allowed!');

require_once(PLUGINS . 'gesior-shop-system/libs/shop-system.php');
require_once(PLUGINS . 'gesior-shop-system/config.php');

$configMercadoPago = config('mercado-pago');

$x_signature = $_SERVER['HTTP_X_SIGNATURE'] ?? null;
$x_request_id = $_SERVER['HTTP_X_REQUEST_ID'] ?? null;

$webhook_body = file_get_contents('php://input');
$webhook_data = json_decode($webhook_body, true);

// Função para enviar resposta padronizada
function sendResponse($success, $message, $status_code = 200, $data = null)
{
	http_response_code($status_code);
	header('Content-Type: application/json');

	$response = [
		'success' => $success,
		'message' => $message
	];

	if ($data !== null) {
		$response['data'] = $data;
	}

	echo json_encode($response);
	exit; // Importante: encerra a execução do script
}

if (!$x_signature || !$configMercadoPago['webhook_x_signature']) {
	error_log('Invalid request. Missing X-Signature.');
	sendResponse(true, 'Received. Missing X-Signature.', 200);
	return;
}

if (!$x_request_id) {
	error_log('Invalid request. Missing X-Request-ID.');
	sendResponse(true, 'Received. Missing X-Request-ID.', 200);
	return;
}

// Verify if the collector_id is present in the request
if (isset($webhook_data['data']['id'])) {
	$collector_id = $webhook_data['data']['id'] ?? null;

	$parts = explode(',', $x_signature);

	if (count($parts) < 2) {
		error_log('Invalid request. Invalid X-Signature format. For Collector ID: ' . $collector_id);
		sendResponse(true, 'Received. Invalid X-Signature format.', 200);
		return;
	}

	// Initializing variables to store ts and hash
	$ts = null;
	$hash = null;

	// Iterate over the values to obtain ts and v1
	foreach ($parts as $part) {
		// Split each part into key and value
		$keyValue = explode('=', $part, 2);
		if (count($keyValue) == 2) {
			$key = trim($keyValue[0]);
			$value = trim($keyValue[1]);
			if ($key === "ts") {
				$ts = $value;
			} elseif ($key === "v1") {
				$hash = $value;
			}
		}
	}

	// Generate the manifest string
	$manifest = "id:$collector_id;request-id:$x_request_id;ts:$ts;";

	// Create an HMAC signature defining the hash type and the key as a byte array
	$sha = hash_hmac('sha256', $manifest, $configMercadoPago['webhook_x_signature']);
	if ($sha !== $hash) {
		// HMAC verification passed
		error_log("Invalid HMAC. For Collector ID: $collector_id");
		sendResponse(true, 'Received. Invalid HMAC', 200);
		return;
	}

	if (!isset($webhook_data['action'])) {
		error_log('Invalid request. Missing action. For Collector ID: ' . $collector_id);
		sendResponse(true, 'Received. Missing action.', 200);
		return;
	}

	$webhook_type = $webhook_data['type'] ?? null;
	$webhook_action = $webhook_data['action'] ?? null;

	if ($webhook_type === 'payment' && $webhook_action === 'payment.updated') {

		$payment_details = $db->select(TABLE_PREFIX . 'mercadopago', ['collector_id' => $collector_id], 1);

		if ($payment_details === false) {
			error_log('Payment not found. For Collector ID: ' . $collector_id);
			sendResponse(true, 'Received. Payment not found.', 200);
			return;
		}

		if ($payment_details['payment_status'] === 'Completed' && $payment_details['payer_status'] === 'Completed' || $payment_details['payer_status'] === 'Completed') {
			error_log('Payment already completed. For Collector ID: ' . $collector_id);
			sendResponse(true, 'Received. Payment already completed.', 200);
			return;
		}

		$curl = curl_init();

		curl_setopt_array($curl, array(
			CURLOPT_URL => 'https://api.mercadopago.com/v1/payments/' . $collector_id,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_CUSTOMREQUEST => 'GET',
			CURLOPT_HTTPHEADER => array(
				'accept: application/json',
				'content-type: application/json',
				'Authorization: Bearer ' . $configMercadoPago['accessToken'],
			),
			CURLOPT_SSL_VERIFYPEER => $configMercadoPago['enable_ssl_verify'],
			CURLOPT_TIMEOUT => 30,
		));

		$response = curl_exec($curl);

		$payment_MP_details = json_decode($response); // Coleta as informações do pagamento
		$payment_MP_status = isset($payment_MP_details->status) ? $payment_MP_details->status : null; // Coleta o status do pagamento

		if ($payment_details['payer_status'] === 'Pending') {
			$account_id = $payment_details['account_id'];
			$account = new OTS_Account();
			$account->load($account_id);

			if ($account->isLoaded()) {
				$time = date('Y-m-d H:i:s');
				
				if(!$payment_MP_status || $payment_MP_status !== 'approved'){
					$db->update(TABLE_PREFIX . 'mercadopago', ['payer_status' => 'Completed', 'payment_status' => $payment_MP_status, 'updated' => $time], ['collector_id' => $collector_id, 'account_id' => $account_id]);
					error_log('Payment not approved. For Collector ID: ' . $collector_id . ' Payment status: ' . $payment_MP_status);
					sendResponse(true, 'Received. Payment not approved.', 200);
					return;
				}

				if ($payment_MP_status === 'approved' && GesiorShop::changePoints($account, $payment_details['points'])) {

					$db->update(TABLE_PREFIX . 'mercadopago', ['payer_status' => 'Completed', 'payment_status' => 'Completed', 'updated' => $time], ['collector_id' => $collector_id, 'account_id' => $account_id]);

					$ip = $_SERVER['REMOTE_ADDR'];
					log_append('mercadoPago.log', "Time: $time;Player Account: $account_id;Payer Email: $payment_details[email]:Payer Currency: $payment_details[currency];Payer Valuer: $payment_details[price];Premium Points: $payment_details[points];Payment Status: Completed;Payer Ip: $ip;Payer Status: Completed");
				} else {
					error_log('Something went wrong. Could not deliver points for Collector ID: ' . $collector_id);
				}
			} else {
				error_log('Account not found. For Collector ID: ' . $collector_id . ' and Account ID: ' . $account_id);
			}
		}
	}

	sendResponse(true, 'Received.', 200);
	return;
} else {
	error_log('Invalid or missing collector_id in the request.');
	sendResponse(true, 'Received. Invalid or missing collector_id in the request.', 200);
}
