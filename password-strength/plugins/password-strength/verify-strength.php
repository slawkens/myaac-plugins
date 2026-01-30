<?php
defined('MYAAC') or die('Direct access not allowed!');

/**
 * @var OTS_Account $account_logged
 * @var bool $logged
 */

require_once __DIR__ . '/vendor/autoload.php';

if (!setting('password_strength.enabled')) {
	return;
}

use ZxcvbnPhp\Zxcvbn;

$userData = [];

$postPassword = $_POST['new_password'] ?? $_POST['password'] ?? '';
if ($this->_type == HOOK_ACCOUNT_CREATE_POST) {
	if (!setting('core.account_login_by_email')) {
		$userData[] = $_POST['account'];
	}

	if (setting('core.account_create_character_create') && isset($_POST['name'])) {
		$userData[] = $_POST['name'];
	}
}

if (isset($logged) && $logged) {
	if (USE_ACCOUNT_NAME) {
		$userData[] = $account_logged->getName();
	}
	else if (USE_ACCOUNT_NUMBER) {
		$userData[] = $account_logged->getNumber();
	}
	else {
		$userData[] = $account_logged->getId();
	}
}


global $errors;

$zxcvbn = new Zxcvbn();
$strength = $zxcvbn->passwordStrength($postPassword, $userData);

if (($strength['score'] + 1) < setting('password_strength.min_score')) {
	$errors['password'] = 'Password is too weak. ' . $strength['feedback']['warning'];

	global $passwordFailed;
	$passwordFailed = true;
}
