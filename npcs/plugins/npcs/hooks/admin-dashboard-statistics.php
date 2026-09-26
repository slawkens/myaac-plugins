<?php
defined('MYAAC') or die('Direct access not allowed!');

require_once PLUGINS . 'npcs/vendor/autoload.php';

use MyAAC\Plugins\NPCs\Npcs;

NPCs::load();
$npcs = NPCs::getNpcs();

$args['elements']['npcs'] = [
	'order' => 65,
	'name' => 'NPCs',
	'title' => 'Total number of NPCs',
	'class' => 'bg-teal',
	'icon' => 'fas fa-comment',
	'value' => count($npcs),
];
