<?php
defined('MYAAC') or die('Direct access not allowed!');

require_once PLUGINS . 'spells/vendor/autoload.php';

use MyAAC\Plugins\Spells\Models\Spell;

$args['elements']['spells'] = [
	'order' => 70,
	'name' => 'Spells',
	'title' => 'Total number of spells',
	'class' => 'bg-danger',
	'icon' => 'fas fa-bolt',
	'value' => Spell::count(),
];
