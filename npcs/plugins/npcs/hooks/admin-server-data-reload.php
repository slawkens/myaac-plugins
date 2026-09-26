<?php
defined('MYAAC') or die('Direct access not allowed!');

require_once PLUGINS . 'npcs/vendor/autoload.php';

use MyAAC\Plugins\NPCs\NPCs;

$startTime = microtime(true);

if(NPCs::load()) {
	$endTime = round(microtime(true) - $startTime, 4);
	success('NPCs have been loaded. (Total: ' . count(NPCs::getNPCs()) . ') (In ' . $endTime . ' seconds)');
}
else {
	error('There were some problems loading your NPCs');
}
