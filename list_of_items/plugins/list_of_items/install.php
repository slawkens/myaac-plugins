<?php
defined('MYAAC_ADMIN') or die('Direct access not allowed!');

/**
 * @var OTS_DB_MySQL $db
 */
if (!$db->hasTable(TABLE_PREFIX . 'list_of_items')) {
	$db->query(file_get_contents(__DIR__ . '/schema.sql'));
	success('Table ' . TABLE_PREFIX . 'list_of_items created!');

	warning(sprintf('The items needs to be reloaded - go to <a href="%s"  target="_blank">items</a> page and click on Reload button.', getLink('items')));
}
