<?php

use MyAAC\Cache\Cache;

/** @var Cache $cache */
if (!$cache->enabled()) {
	return;
}

$cache->delete('shop_offers_fetch');
$cache->delete('mounts');
