<?php

defined('MYAAC') or die('Direct access not allowed!');

use MyAAC\Plugins;

$template = 'bootstrap-clean';

global $config;
require PLUGINS . "theme-$template/themes/$template/config.php";

Plugins::installMenus($template, config('menus'));
