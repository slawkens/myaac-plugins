<?php
defined('MYAAC') or die('Direct access not allowed!');

if(
	!$db->hasTable('myaac_monsters')
) {
	// import schema
	try {
		$db->query(file_get_contents(PLUGINS . 'monsters/schema.sql'));
		success('Importing database schema.');
	}
	catch(PDOException $error_) {
		error($error_);
		return;
	}
}
