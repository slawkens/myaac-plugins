<?php
defined('MYAAC') or die('Direct access not allowed!');

require_once PLUGINS . 'monsters/vendor/autoload.php';

use MyAAC\Plugins\Monsters\Models\Monster;

$args['elements']['monsters'] = [
	'order' => 45,
	'name' => 'Monsters',
	'title' => 'Total number of monsters',
	'class' => 'bg-teal',
	'icon' => 'fas fa-pastafarianism',
	'value' => Monster::count(),
];
