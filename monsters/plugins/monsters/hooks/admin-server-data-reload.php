<?php
defined('MYAAC') or die('Direct access not allowed!');

require_once PLUGINS . 'monsters/vendor/autoload.php';

use \MyAAC\Plugins\Monsters\Base as BaseMonsters;

BaseMonsters::reloadMonsters(false);
