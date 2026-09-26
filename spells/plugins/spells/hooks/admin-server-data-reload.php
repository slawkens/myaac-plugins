<?php
defined('MYAAC') or die('Direct access not allowed!');

require_once PLUGINS . 'spells/vendor/autoload.php';

use \MyAAC\Plugins\Spells\Base as BaseSpells;

BaseSpells::reloadSpells(false);
