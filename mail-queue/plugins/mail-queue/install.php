<?php
defined('MYAAC') or die('Direct access not allowed!');

if(
	!$db->hasTable('myaac_mail_queue')
	|| !$db->hasTable('myaac_mail_queue_history')
) {
	// import schema
	try {
		$db->query(file_get_contents(PLUGINS . 'mail-queue/schema.sql'));
		success('Importing database schema.');
	}
	catch(PDOException $error_) {
		error($error_);
		return;
	}
}
