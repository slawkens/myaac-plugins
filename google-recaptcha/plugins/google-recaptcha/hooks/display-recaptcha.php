<?php
defined('MYAAC') or die('Direct access not allowed!');

if(GoogleReCAPTCHA::enabled()) {
	$twig->display('google-recaptcha/views/recaptcha-display.html.twig');
}
