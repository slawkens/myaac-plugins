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

if(!tableExist('z_ots_comunication')
	|| !tableExist('z_shop_categories')
	|| !tableExist('z_shop_history')
	|| !tableExist('z_shop_offer')
	|| !tableExist('crypto_payments')
	|| !tableExist('myaac_paypal')
	|| !tableExist('stripe')
) {
	// import schema
	try {
		$db->query(file_get_contents(PLUGINS . 'gesior-shop-system/schema.sql'));
		success('Some tables were missing. Importing database schema.');
	}
	catch(PDOException $error_) {
		error($error_);
		return;
	}
}

$query = $db->query("SELECT `id` FROM `z_shop_categories` LIMIT 1;");
if($query->rowCount() === 0) {
	$defaultCategories = [
		['id' => 1, 'name' => 'Items', 'hidden' => 0],
		['id' => 2, 'name' => 'Addons', 'hidden' => 0],
		['id' => 3, 'name' => 'Mounts', 'hidden' => 0],
		['id' => 4, 'name' => 'Premium Account', 'hidden' => 0],
		['id' => 5, 'name' => 'Containers', 'hidden' => 0],
		['id' => 6, 'name' => 'Other', 'hidden' => 0],
	];

	foreach($defaultCategories as $category) {
		$db->insert('z_shop_categories', $category);
	}

	success('Imported sample categories to database.');
}

if(!$db->hasColumn('z_shop_offer', 'hidden')) {
	$db->query("ALTER TABLE `z_shop_offer` ADD `hidden` TINYINT(1) NOT NULL DEFAULT 0;");
	success('Added hidden field to z_shop_offer table to database.');
}

if (!$db->hasColumn('z_shop_offer', 'category_id')) {
	$db->exec("ALTER TABLE z_shop_offer ADD `category_id` TINYINT(1) NOT NULL DEFAULT 0 AFTER `count2`;");

	$query = $db->query("SELECT id, name FROM z_shop_categories;");
	foreach ($query as $category) {
		$db->update('z_shop_offer', ['category_id' => $category['id']], ['offer_type' => $category['name']]);
	}

	if ($db->hasColumn('z_shop_categories', 'description')) {
		$db->exec("UPDATE z_shop_categories SET `name` = `description`;");
		$db->exec("ALTER TABLE z_shop_categories DROP `description`;");
	}

	$db->exec("ALTER TABLE z_shop_categories DROP PRIMARY KEY, CHANGE id id INT(11) NOT NULL;");
	$db->exec("ALTER TABLE z_shop_categories ADD PRIMARY KEY(`id`);");

	success('Updated tables to latest version (category_id) - v4.0.');
}

if (!$db->hasColumn('z_shop_offer', 'ordering')) {
	$db->exec("ALTER TABLE z_shop_offer ADD `ordering` INT(11) NOT NULL DEFAULT 0;");
	$db->exec("UPDATE z_shop_offer SET `ordering` = `id`;");
}

// insert some samples
// avoid duplicates
$query = $db->query("SELECT `id` FROM `z_shop_offer` LIMIT 1;");
if($query->rowCount() === 0) {

	$defaultOffers = [
		[
			'points' => 10, 'itemid1' => 2160, 'count1' => 50, 'itemid2' => 0, 'count2' => 0, 'category_id' => 1, 'offer_type' => 'item', 'offer_description' => '50 crystal coins. They weigh 5.00 oz.', 'offer_name' => '50 Crystal Coins', 'ordering' => 1,
		],
		[
			'points' => 10, 'itemid1' => 139, 'count1' => 3, 'itemid2' => 131, 'count2' => 3, 'category_id' => 2, 'offer_type' => 'addon', 'offer_description' => 'This purchase will give you the full knight outfit.', 'offer_name' => 'Knight Outfit', 'ordering' => 2,
		],
		[
			'points' => 10, 'itemid1' => 22, 'count1' => 0, 'itemid2' => 0, 'count2' => 0, 'category_id' => 3, 'offer_type' => 'mount', 'offer_description' => 'This purchase will give you the Rented Horse mount.', 'offer_name' => 'Rented Horse', 'ordering' => 3
		],
		[
			'points' => 10, 'itemid1' => 0, 'count1' => 30, 'itemid2' => 0, 'count2' => 0, 'category_id' => 4, 'offer_type' => 'pacc',
			'offer_description' => '30 Days of Premium Account', 'offer_name' => 'PACC 30', 'ordering' => 4,
		],
	];

	foreach($defaultOffers as $offer) {
		$db->insert('z_shop_offer', $offer);
	}

	success('Imported sample offers to database.');
}

if($db->select(TABLE_PREFIX . 'admin_menu', ['name' => 'Gifts']) !== false) {
	$db->delete(TABLE_PREFIX . 'admin_menu', ['name' => 'Gifts']);
}

if(!@copy('https://curl.se/ca/cacert.pem', PLUGINS . 'gesior-shop-system/libs/' . 'cert/cacert.pem')) {
	$errors = error_get_last();
	error($errors['type'] . "<br />\n" . $errors['message']);
} else {
	success('Updated cacert.pem from remote host.');
}

if(!function_exists('curl_init')) {
	error(sprintf("Error. Please enable <a target='_blank' href='%s'>CURL extension</a> in PHP. <a target='_blank' href='%s'>Read here &#187;</a> Paypal and Cryptobox will not work correctly without it.", "http://php.net/manual/en/book.curl.php", "http://stackoverflow.com/questions/1347146/how-to-enable-curl-in-php-xampp"));
	return;
}

if(!is_file(PLUGINS . 'gesior-shop-system/config.php')) {
	copy(
		PLUGINS . 'gesior-shop-system/config.php.dist',
		PLUGINS . 'gesior-shop-system/config.php'
	);
	success("Copied config.php.dist to config.php");
}

success("You can configure the script in following file: plugins/gesior-shop-system/config.php");
