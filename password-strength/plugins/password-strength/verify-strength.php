<?php

require_once __DIR__ . '/vendor/autoload.php';

if (!setting('password_strength.enabled')) {
	return;
}

use ZxcvbnPhp\Zxcvbn;

$userData = [];

$postPassword = $_POST['new_password'] ?? '';
if ($this->_type == HOOK_ACCOUNT_CREATE_POST) {
	if (!setting('core.account_login_by_email')) {
		$userData[] = $_POST['account'];
	}

	if (setting('core.account_create_character_create') && isset($_POST['name'])) {
		$userData[] = $_POST['name'];
	}

	$postPassword = $_POST['password'] ?? '';
}

global $errors;

$zxcvbn = new Zxcvbn();
$strength = $zxcvbn->passwordStrength($postPassword, $userData);

if (($strength['score'] + 1) < setting('password_strength.min_score')) {
	$errors['password'] = 'Password is too weak. ' . $strength['feedback']['warning'];
}


