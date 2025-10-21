<?php
defined('MYAAC') or die('Direct access not allowed!');

if (!setting('password_strength.enabled')) {
	return;
}

$passwordInput = '#password';
if ($this->_type == HOOK_ACCOUNT_CHANGE_PASSWORD_AFTER_NEW_PASSWORD) {
	$passwordInput = '#new_password';
}

$twig->display('password-strength/password-strength.html.twig', [
	'passwordInput' => $passwordInput,
]);
