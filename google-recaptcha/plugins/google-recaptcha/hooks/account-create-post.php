<?php
defined('MYAAC') or die('Direct access not allowed!');

if(GoogleReCAPTCHA::enabled()) {
	if (!GoogleReCAPTCHA::verify('register')) {
		global $logged, $errors;
		unsetSession('account');
		unsetSession('password');
		unsetSession('remember_me');
		$logged = false;

		$errors['verification'] = GoogleReCAPTCHA::getErrorMessage();
	}
}
