<?php

use MyAAC\Plugin\Census;

defined('MYAAC') or die('Direct access not allowed!');
$title = 'Census';

if(!isset($config['census_countries']))
	$config['census_countries'] = 5;

require PLUGINS . 'census/Census.php';
require SYSTEM . 'countries.conf.php';

$twig->addGlobal('config', $config);

$census = new Census($db);

$twig->display('census/views/census.html.twig', [
	'vocations' => $census->getVocationStats(),
	'genders' => $census->getGenderStats(),
	'countries' => $census->getCountriesStats(),
	'countriesOther' => $census->getCountriesOther(),
]);
