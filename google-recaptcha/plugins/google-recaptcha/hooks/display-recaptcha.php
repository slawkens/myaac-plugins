<?php
defined('MYAAC') or die('Direct access not allowed!');

require_once __DIR__ . '/../init.php';

if(GoogleReCAPTCHA::enabled()) {
	$twig->display('google-recaptcha/views/recaptcha-display.html.twig');
}
