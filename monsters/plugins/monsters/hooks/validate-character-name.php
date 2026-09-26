<?php
defined('MYAAC') or die('Direct access not allowed!');

require_once PLUGINS . 'monsters/vendor/autoload.php';

use MyAAC\Plugins\Monsters\Models\Monster;

$monstersCheck = setting('core.create_character_name_monsters_check');
if (!$monstersCheck) {
	return;
}

/**
 * @var array $args
 */

if (Monster::where('name', 'like', '%' . $args['name'] . '%')->exists()) {
	$args['error'] =  'Your name cannot contains monster name.';
}

