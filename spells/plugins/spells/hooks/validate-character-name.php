<?php
defined('MYAAC') or die('Direct access not allowed!');

require_once PLUGINS . 'spells/vendor/autoload.php';

use MyAAC\Plugins\Spells\Models\Spell;

$spellsCheck = setting('core.create_character_name_spells_check');
if (!$spellsCheck) {
	return;
}

/**
 * @var array $args
 */

if (Spell::where('name', 'like', '%' . $args['name'] . '%')->exists()) {
	$args['error'] = 'Your name cannot contain a spell name.';
}

if (Spell::where('words', 'like', '%' . $args['name'] . '%')->exists()) {
	$args['error'] = 'Your name cannot contain a spell name.';
}
