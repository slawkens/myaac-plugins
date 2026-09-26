<?php
defined('MYAAC') or die('Direct access not allowed!');

require_once PLUGINS . 'npcs/vendor/autoload.php';

use MyAAC\Plugins\NPCs\Npcs;

$npcCheck = setting('core.create_character_name_npc_check');
if (!$npcCheck) {
	return;
}

/**
 * @var array $args
 */
NPCs::load();
$npcs = NPCs::getNpcs();
if(count($npcs) > 0) {
	foreach ($npcs as $npc) {
		if(str_contains(strtolower($npc), strtolower($args['name']))) {
			$args['error'] = 'Your name cannot contains NPC name.';
			return;
		}
	}
}
