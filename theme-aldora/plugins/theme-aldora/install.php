<?php

use MyAAC\Plugins;

defined('MYAAC') or die('Direct access not allowed!');

$template = 'aldora';

require PLUGINS . "theme-$template/themes/$template/config.php";

Plugins::installMenus($template, require PLUGINS . "theme-$template/menus.php");
