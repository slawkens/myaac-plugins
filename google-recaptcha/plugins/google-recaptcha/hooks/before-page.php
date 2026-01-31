<?php
defined('MYAAC') or die('Direct access not allowed!');

if(GoogleReCAPTCHA::enabled()) {
	if (PAGE != 'account/create' && PAGE != 'account/manage') {
		return; // do not display on other pages
	}

	GoogleReCAPTCHA::placeholders();
}


