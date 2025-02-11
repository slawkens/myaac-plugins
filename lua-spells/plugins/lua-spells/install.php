<?php
defined('MYAAC') or die('Direct access not allowed!');

if(
	!$db->hasTable('myaac_lua_spells')
) {
	// import schema
	try {
		$db->query(file_get_contents(PLUGINS . 'lua-spells/schema.sql'));
		success('Importing database schema.');
	}
	catch(PDOException $error_) {
		error($error_);
		return;
	}
}
