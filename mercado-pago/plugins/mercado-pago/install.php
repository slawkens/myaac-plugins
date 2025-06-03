<?php
/**
 * This is shop system taken from Gesior, modified for MyAAC.
 *
 * @name      myaac-gesior-shop-system
 * @author    Gesior <jerzyskalski@wp.pl>
 * @author    Slawkens <slawkens@gmail.com>
 * @website   github.com/slawkens/myaac-gesior-shop-system
 */
defined('MYAAC') or die('Direct access not allowed!');

if(!$db->hasTable('myaac_mercadopago')
) {
	// import schema
	try {
		$db->query(file_get_contents(PLUGINS . 'mercado-pago/schema.sql'));
		success('Some tables were missing. Importing database schema.');
	}
	catch(PDOException $error_) {
		error($error_);
		return;
	}
}

if(!is_file(PLUGINS . 'mercado-pago/config.php')) {
	copy(
		PLUGINS . 'mercado-pago/config.php.dist',
		PLUGINS . 'mercado-pago/config.php'
	);
	success("Copied config.php.dist to config.php");
}

success("You can configure the script in following file: plugins/mercado-pago/config.php");
