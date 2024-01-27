<?php

defined('MYAAC') or die('Direct access not allowed!');

use MyAAC\Plugins;

$template = 'atomio';

require TEMPLATES . $template . '/config.php';

Plugins::installMenus($template, require PLUGINS . $template . '-theme/menus.php');
