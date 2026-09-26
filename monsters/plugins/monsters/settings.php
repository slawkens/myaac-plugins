<?php

return [
	'name' => 'Monsters',
	'key' => 'monsters', // will be used with setting() function, must be unique for every setting
	'settings' =>
	[
		[
			'type' => 'section',
			'title' => 'Monsters Page Settings'
		],
		'monsters_images_preview' => [
			'name' => 'Monsters Images Preview',
			'type' => 'boolean',
			'desc' => 'Set to yes to allow picture previews for creatures',
			'default' => false,
		],
		'monsters_items_url' => [
			'name' => 'Monsters Items URL',
			'type' => 'text',
			'desc' => 'Set to website which shows details about items',
			'default' => 'https://tibia.fandom.com/wiki/',
		],
		'monsters_loot_percentage' => [
			'name' => 'Monsters Loot Percentage',
			'type' => 'boolean',
			'desc' => 'Set to yes to show the loot tooltip percent',
			'default' => true,
		],
	]
];
