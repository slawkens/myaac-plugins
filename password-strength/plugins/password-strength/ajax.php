<?php
/**
 * @package   myaac-password-strength
 * @author    Slawkens <slawkens@gmail.com>
 * @copyright 2023 MyAAC
 * @link      https://my-aac.org
 */

// we need some functions
require __DIR__ . '/../../common.php';
require SYSTEM . 'functions.php';
require SYSTEM . 'init.php';

require_once __DIR__ . '/vendor/autoload.php';

if (empty($_POST['password'])) {
	error_('Please enter password.');
}
/*
if (empty($_POST['userInputs'])) {
	error_('Please enter user input.');
}*/

use ZxcvbnPhp\Zxcvbn;

$zxcvbn = new Zxcvbn();
$strength = $zxcvbn->passwordStrength($_POST['password'], $_POST['userInputs'] ?? []);

success_($strength['score'], $strength['feedback']['warning'] ?? '', $strength['feedback']['suggestions'] ?? '');

function success_($score, $warning, $suggestions)
{
	echo json_encode([
		'success' => true,
		'score' => $score,
		'warning' => setting('password_strength.display_warning') ? $warning : '',
		'suggestions' => setting('password_strength.display_suggestions') ? $suggestions : [],
	]);

	exit();
}

function error_($desc = '')
{
	echo json_encode([
		'success' => false,
		'message' => $desc,
	]);

	exit();
}
