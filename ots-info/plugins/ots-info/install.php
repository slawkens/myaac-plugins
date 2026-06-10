<?php
defined('MYAAC') or die('Direct access not allowed!');

use MyAAC\Models\Pages;

Pages::where('name', 'ots-info')->update([
	'hide' => 1,
]);
