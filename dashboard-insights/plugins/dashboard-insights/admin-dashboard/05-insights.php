<?php
defined('MYAAC') or die('Direct access not allowed!');

require __DIR__ . '/../src/Insights.php';

use MyAAC\Plugins\DashboardInsights\Admin\Insights;

$insights = new Insights($db);

$twig->display('dashboard-insights/views/insights.html.twig', [
	'firstYear' => $insights->getFirstYear(),

	'currentYear' => date('Y'),
	'currentMonth' => (int)date('m'),

	'months' => $insights->getMonths(),
]);
