<?php
/**
 * Welcome Box for MyAAC
 *
 * @name      welcome-box
 * @author    slawkens <slawkens@gmail.com>
 */

use MyAAC\Cache\Cache;
use MyAAC\Plugin\WelcomeBox;

/**
 * @var $config array
 */
if('news' !== PAGE) {
	return;
}

$values = Cache::remember('welcome-box-values', 10 * 60, function() use ($db) {
	require PLUGINS . 'welcome-box/WelcomeBox.php';

	$welcomeBox = new WelcomeBox($db);
	return [
		'lastJoinedPlayer' => $welcomeBox->getLastJoinedPlayer(),
		'bestPlayer' => $welcomeBox->getBestPlayer(),
		'total' => $welcomeBox->getTotal(),
	];
});

$twig->display('welcome-box/welcome-box.html.twig', [
	'values' => $values
]);
