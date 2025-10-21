<?php
defined('MYAAC') or die('Direct access not allowed!');

global $action, $twig, $template_name, $logged;
if($logged && $template_name == 'emma' && str_contains(PAGE, 'account/')
	&& !str_contains(PAGE, 'account/manage') && !str_contains(PAGE, 'account/create')) {
	echo $twig->render('theme-emma/themes/emma/emma-account-menu.html.twig');
}
