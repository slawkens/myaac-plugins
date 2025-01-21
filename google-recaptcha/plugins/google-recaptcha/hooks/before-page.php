<?php
defined('MYAAC') or die('Direct access not allowed!');

require_once __DIR__ . '/../init.php';

global $template_place_holders;
if(!isset($template_place_holders['head_end'])) {
	$template_place_holders['head_end'] = array();
}

if(GoogleReCAPTCHA::enabled()) {
	if (PAGE != 'account/create' && PAGE != 'account/manage') {
		return; // do not display on other pages
	}

	$recaptchaType = setting('google_recaptcha.type');

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


