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

require_once PLUGINS . 'gesior-shop-system/config.php';

$configMercadoPago = config('mercado-pago');

csrfProtect();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	// Verifica se os dados foram enviados via POST
	// Caminho relativo para o arquivo config.php

	$quantidadeSelecionada = (int)$_POST['quantidade'];
	$cpf = $_POST['cpf'];

	// Validate CPF
	if (!validateCPF($cpf)) {
		echo '<p>CPF inválido. Por favor, verifique e tente novamente.</p>';
		exit;
	}

	$totalValue = $quantidadeSelecionada * $configMercadoPago['priceByPoints'];
	$pontosGanhos = $quantidadeSelecionada;
	$payer_email = $_POST['email_cob'];

	$curl = curl_init();
	$dados["transaction_amount"] = $totalValue;
	$dados["description"] = 'premium_points';
	$dados["external_reference"] = "2";
	$dados["payment_method_id"] = "pix";
	$dados["payer"]["email"] = $payer_email;
	$dados["payer"]["first_name"] = $_POST['name'];
	$dados["payer"]["last_name"] = $_POST['surname'];
	$dados["payer"]["identification"]["type"] = "CPF";
	$dados["payer"]["identification"]["number"] = $cpf;
	$dados["additional_info"]["items"] = [
		[
			"id" => uniqid('dbmasterpoints_', true),
			"title" => "Premium Points for Dragon Ball Masters",
			"description" => $pontosGanhos . " Premium Points para serem utilizados no servidor Dragon Ball Masters.",
			"category_id" => "entretenimento",
			"quantity" => $pontosGanhos,
			"type" => "virtual_product",
		]
	];

	$idempotencyKey = uniqid('idempotency_', true);

	curl_setopt_array($curl, array(
		CURLOPT_URL => 'https://api.mercadopago.com/v1/payments',
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_CUSTOMREQUEST => 'POST',
		CURLOPT_POSTFIELDS => json_encode($dados),
		CURLOPT_HTTPHEADER => array(
			'accept: application/json',
			'content-type: application/json',
			'Authorization: Bearer ' . $configMercadoPago['accessToken'],
			'X-Idempotency-Key: ' . $idempotencyKey,
		),
		CURLOPT_SSL_VERIFYPEER => $configMercadoPago['enable_ssl_verify'],
		CURLOPT_TIMEOUT => 30,
	));

	$response = curl_exec($curl);

	// Adicione tratamento de erros do cURL
	if (curl_errno($curl)) {
		echo '<p>Erro de conexão: ' . curl_error($curl) . '</p>';
		return;
	}

	$resultado = json_decode($response);

	if (json_last_error() !== JSON_ERROR_NONE) {
		echo '<p>Erro ao decodificar resposta JSON: ' . json_last_error_msg() . '</p>';
		echo '<p>Resposta bruta: ' . htmlspecialchars($response) . '</p>';
		return;
	}

	$collector_id = isset($resultado->id) ? $resultado->id : null;
	curl_close($curl);

	// Display full response for debugging if debug is enabled
	if ($configMercadoPago['debug']) {
		echo '<pre>';
		var_dump($resultado);
		echo '</pre>';
	}

	// Check if collector_id is present
	if (!$collector_id) {
		echo '<h3>Não foi possível gerar o QR Code. Por favor, tente novamente ou contate o suporte.</h3>
		<br/><p>Error: Collector ID not found in the response.</p>';
		return;
	}

	echo '<div style="display: grid; gap: 10px; margin: auto; padding: 5px; place-content: center; text-align: center;">';
	echo '<div style=""><h3>Escaneie o QR Code Abaixo</h3></div>';

	// Verifica se a resposta foi bem-sucedida antes de exibir o QR Code
	if ($resultado->status === 'pending') {

		echo '<div style="display: grid; gap: 5px; margin: auto; padding: 5px; place-content: center;">';

		echo '<img style="display:block; width:300px;height:300px; margin:auto;" id="base64image" src="data:image/jpeg;base64, ' . $resultado->point_of_interaction->transaction_data->qr_code_base64 . '" />';
		echo '<div style="display: flex; gap: 5px; margin: auto; padding: 5px; place-content: center; width: 100%; place-items: center;">';
		echo '	<b>Copie:</b>
			  	<input id="qrCodeVal" type="text" value="' . $resultado->point_of_interaction->transaction_data->qr_code . '" disabled/>
			  </div>
			  <button class="btn" style="width: 100%;" onclick="copyFunction()">Copiar Código</button>
			  <p id="copyMessage" style="margin-top: 10px;"></p>
		</div>'; // Div do QR Code

		// Exibe os pontos ganhos
		echo '<div style="display: grid; gap: 5px; margin: auto; padding: 5px; place-content: center;">
                <p style="color: red;">Não saia dessa pagina até efetuar o pagamento, caso você saia, será necessário gerar outro QrCode.</p>
                <p style="color: green;">Você gerou um QrCode no valor de R$ ' . number_format($totalValue, 2, '.', '') . ' para receber ' . $pontosGanhos . ' premium points!</p>
                <h4>Finalizou o pagamento? Voce já pode fechar esta guia!</h4>
              </div>
            </div>'; // A div se inicia em outro echo, por isso o fechamento da div está aqui

		echo '<script>
		let copied = false;
		const copyFunction = async () => {
			const messageElement = document.getElementById("copyMessage");
			const valueToCopy = document.getElementById("qrCodeVal").value;
			try {
				await navigator.clipboard.writeText(valueToCopy);
				messageElement.innerText = "Código Copiado com sucesso";
				messageElement.style.color = "green";
				// Limpa o status após 2 segundos
			} catch (error) {
				messageElement.innerText = "Erro ao copiar o código.";
				messageElement.style.color = "red";
			}
				
			setTimeout(() => {
				messageElement.innerText = "";
			}, 2000);
		};
		</script>'; // Script para botão de copiar código

		// Ação no banco de dados (se necessário)
		$current_session = getSession('account');

		if ($db->select(TABLE_PREFIX . 'mercadopago', ['collector_id' => $collector_id, 'payment_status' => 'Completed']) !== false) {
			echo "<h3>Transação duplicada para Collector_id: $collector_id </h3>";
			return;
		}

		if ($db->select(TABLE_PREFIX . 'mercadopago', ['collector_id' => $collector_id, 'payer_status' => 'Completed']) !== false) {
			echo "<h3>Seus premium points já foram entregues. <br/>Collector_id: $collector_id </h3>";
			return;
		}
		$time = date('Y-m-d H:i:s');

		$db->insert(
			TABLE_PREFIX . 'mercadopago',
			[
				'idempotency_key' => $idempotencyKey,
				'collector_id' => $collector_id,
				'email' => $payer_email,
				'account_id' => $current_session,
				'price' => $totalValue,
				'currency' => 'BRL',
				'points' => $pontosGanhos,
				'payer_status' => 'Pending',
				'payment_status' => $resultado->status,
				'created' => $time,
			]
		);
	} else {
		echo '<p>Erro ao processar o pagamento. Tente novamente.</p>';

		// Display error message, if available
		if (isset($resultado->message)) {
			echo '<p>Error Message: ' . $resultado->message . '</p>';
		}

		// Display status for further analysis
		echo '<p>Status: ' . $resultado->status . '</p>';
	}
} else {
	echo '
       <form method="POST">
            <div style="display: grid; gap: 5px; margin: auto; padding: 5px;">
	   			' . csrf(true) . '
                <div style="display: grid; gap: 5px;">
                    <label for="quantidade">Quantidade de Premium Points:</label>
                    <input type="number" name="quantidade" id="quantidade" class="form-control" min="100" step="5" value="100" required>
                </div>
                <div style="display: grid; gap: 5px;">
                    <label for="description">Nome:</label>
                    <input type="text" name="name" id="name" placeholder="Nome" required>
                </div>
                <div style="display: grid; gap: 5px;">
                    <label for="description">Sobrenome:</label>
                    <input type="text" name="surname" id="surname" placeholder="Sobrenome" required>
                </div>
                <div style="display: grid; gap: 5px;">
                    <label for="email_cob">Email do Pagador:</label>
                    <input type="email" name="email_cob" id="email_cob" placeholder="meu@email.com" class="form-control" required>
                </div>
                <div style="display: grid; gap: 5px;">
                    <label for="cpf">CPF do Pagador:</label>
                    <input type="text" name="cpf" id="cpf" placeholder="000.000.000-00" required>
                </div>
                <div style="display: grid; gap: 5px;">
                    <label for="valor">Previsão de Valor:</label>
                    <input type="text" name="previsaoVal" id="previsaoVal" disabled>
                </div>
                <div style="display: grid; gap: 5px; padding-top: 5px;">
                    <button type="submit" class="btn btn-success">Gerar Pix</button>
                </div>
            </div>
        </form>
        
        <script>
			document.getElementById("previsaoVal").value = (document.getElementById("quantidade").value *' . $configMercadoPago['priceByPoints'] . ').toFixed(2);
			document.getElementById("quantidade").addEventListener("input", function() {
				var quantidade = this.value;
				document.getElementById("previsaoVal").value = (quantidade *' . $configMercadoPago['priceByPoints'] . ').toFixed(2);
			});
        </script>
		<script src="https://sdk.mercadopago.com/js/v2"></script>
		<script>
			const mp = new MercadoPago("' . $configMercadoPago['publicKey'] . '");
		</script>
    ';
}

// Simple CPF validation function
function validateCPF($cpf)
{
	// Remove any non-numeric characters
	$cpf = preg_replace('/[^0-9]/', '', $cpf);

	// Check if CPF has 11 digits
	if (strlen($cpf) != 11) {
		return false;
	}

	// Validate CPF using basic algorithm
	$sum = 0;
	for ($i = 0; $i < 9; $i++) {
		$sum += (int)$cpf[$i] * (10 - $i);
	}

	$remainder = $sum % 11;
	$digit = ($remainder < 2) ? 0 : 11 - $remainder;

	if ((int)$cpf[9] != $digit) {
		return false;
	}

	$sum = 0;
	for ($i = 0; $i < 10; $i++) {
		$sum += (int)$cpf[$i] * (11 - $i);
	}

	$remainder = $sum % 11;
	$digit = ($remainder < 2) ? 0 : 11 - $remainder;

	if ((int)$cpf[10] != $digit) {
		return false;
	}

	return true;
}
