<?php

use MyAAC\Plugins;

defined('MYAAC') or die('Direct access not allowed!');

$template = 'aldora';

require PLUGINS . $template . '-theme/themes/' . $template . '/config.php';

Plugins::installMenus($template, require PLUGINS . $template . '-theme/menus.php');
