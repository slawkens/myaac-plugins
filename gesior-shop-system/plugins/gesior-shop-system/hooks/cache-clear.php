<?php

use MyAAC\Cache\Cache;

$cache = Cache::getInstance();
if (!$cache->enabled()) {
	return;
}

$cache->delete('shop_offers_fetch');
$cache->delete('mounts');
