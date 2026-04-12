<?php
defined('MYAAC') or die('Direct access not allowed!');

// import schema
try {
	$db->query(file_get_contents(PLUGINS . 'bug-tracker/schema.sql'));
	success('Importing database schema.');
}
catch(PDOException $error_) {
	error($error_);
	return;
}
