<?php
defined('MYAAC') or die('Direct access not allowed!');

require_once __DIR__ . '/../init.php';

if(GoogleReCAPTCHA::enabled()) {
	global $logged;
	if ($logged) {
		return; // do not display when logged in
	}

	$recaptchaType = setting('google_recaptcha.type');

	if ($this->_type == HOOK_ADMIN_HEAD_END) {
		echo '<script src="https://www.google.com/recaptcha/api.js' .
			(
			$recaptchaType == 'v3' ? '?render=' . setting('google_recaptcha.site_key') :
				(
				$recaptchaType === 'v2-invisible' ? '?onload=onloadCallback' :
					'')
			) . '" async defer></script>';
	}
	else if($this->_type == HOOK_ADMIN_BODY_END && $recaptchaType == 'v3') {
		$twig->display('google-recaptcha/views/recaptcha-v3.html.twig', [
				'action' => (PAGE == 'account/create' ? 'register' : 'login')
			]
		);
	}
}
