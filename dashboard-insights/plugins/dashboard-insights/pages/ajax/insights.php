<?php
defined('MYAAC') or die('Direct access not allowed!');

require __DIR__ . '/../../src/Insights.php';

use MyAAC\Plugins\DashboardInsights\Admin\Insights;

if (!admin()) {
	die('Access denied!');
}

$getYear = (int)($_POST['year'] ?? date('Y'));
$getMonth = $_POST['month'] ?? (int)date('M') + 1;

$insights = new Insights($db);

echo json_encode(
	[
		'lastLoginPlayers' => $insights->getLastLoggedPlayers($getYear, $getMonth),
		'lastCreatedAccounts' => $insights->getLastCreatedAccounts($getYear, $getMonth),
	]
);

die();
