<?php

class GoogleReCAPTCHA
{
	private static string $errorMessage = '';
	private static int $errorType;

	const ERROR_MISSING_RESPONSE = 1;
	const ERROR_INVALID_ACTION = 2;
	const ERROR_LOW_SCORE = 3;
	const ERROR_NO_SUCCESS = 4;

	public static function verify($action = ''): bool
	{
		if (empty($_POST['g-recaptcha-response'])) {
			self::$errorType = self::ERROR_MISSING_RESPONSE;
			self::$errorMessage = "Please confirm that you're not a robot.";
			return false;
		}

		$recaptchaApiUrl = 'https://www.google.com/recaptcha/api/siteverify';
		$secretKey = setting('google_recaptcha.secret_key');

		$recaptchaResponse = $_POST['g-recaptcha-response'];
		$ip = $_SERVER['REMOTE_ADDR'];
		$params = 'secret='.$secretKey.'&response='.$recaptchaResponse.'&remoteip='.$ip;

		if (function_exists('curl_version')) {
			$curl_connection = curl_init($recaptchaApiUrl);

			curl_setopt($curl_connection, CURLOPT_CONNECTTIMEOUT, 5);
			curl_setopt($curl_connection, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($curl_connection, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($curl_connection, CURLOPT_FOLLOWLOCATION, 0);
			curl_setopt($curl_connection, CURLOPT_POSTFIELDS, $params);

			$response = curl_exec($curl_connection);
			curl_close($curl_connection);
		} else {
			$response = file_get_contents($recaptchaApiUrl . '?' . $params);
		}

		$json = json_decode($response);
		if (setting('google_recaptcha.type') === 'v3') { // score based
			//log_append('recaptcha.log', 'recaptcha_score: ' . $json->score . ', action:' . $json->action);

			if (!isset($json->action) || $json->action !== $action) {
				self::$errorType = self::ERROR_INVALID_ACTION;
				self::$errorMessage = 'Google ReCaptcha returned invalid action.';
				return false;
			}

			if (!isset($json->score) || $json->score < setting('google_recaptcha.v3_min_score')) {
				self::$errorType = self::ERROR_LOW_SCORE;
				self::$errorMessage = 'Your Google ReCaptcha score was too low.';
				return false;
			}
		}

		if (!isset($json->success) || !$json->success) {
			self::$errorType = self::ERROR_NO_SUCCESS;
			self::$errorMessage = "Please confirm that you're not a robot.";
			return false;
		}

		return true;
	}

	/**
	 * @return string
	 */
	public static function getErrorMessage(): string
	{
		return self::$errorMessage;
	}

	/**
	 * @return int
	 */
	public static function getErrorType(): int {
		return self::$errorType;
	}

	public static function enabled(): bool {
		return (setting('google_recaptcha.enabled') && !empty(setting('google_recaptcha.site_key')) && !empty(setting
			('google_recaptcha.secret_key')));
	}

	public static function placeholders(): void
	{
		global $template_place_holders, $twig;
		$recaptchaType = setting('google_recaptcha.type');

		if(!isset($template_place_holders['head_end'])) {
			$template_place_holders['head_end'] = [];
		}

		if(!isset($template_place_holders['body_end'])) {
			$template_place_holders['body_end'] = [];
		}

		// insert into page head
		$template_place_holders['head_end'][] = '<script src="https://www.google.com/recaptcha/api.js' .
			(
			$recaptchaType == 'v3' ? '?render=' . setting('google_recaptcha.site_key') :
				(
				$recaptchaType === 'v2-invisible' ? '?onload=onloadCallback' :
					'')
			) . '" async defer></script>';

		if ($recaptchaType == 'v3') {
			$template_place_holders['body_end'][] = $twig->render('google-recaptcha/views/recaptcha-v3.html.twig', [
					'action' => (PAGE == 'account/create' ? 'register' : 'login')
				]
			);
		}
	}
}
