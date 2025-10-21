<?php

return [
	'name' => 'Password Strength',
	'key' => 'password_strength', // will be used with setting() function, must be unique for every setting
	'settings' =>
		[
			[
				'type' => 'section',
				'title' => 'Password Strength'
			],
			'enabled' => [
				'name' => 'Enable Password Strength',
				'type' => 'boolean',
				'desc' => 'Enable Password Strength plugin.',
				'default' => true,
			],
			'min_score' => [
				'name' => 'Minimum Score',
				'type' => 'number',
				'default' => 1,
				'min' => 1,
				'max' => 5,
				'desc' => 'Minimum allowed score of password. Value of 1-5. Lower value = lower protection.',
				'show_if' => [
					'enabled', '=', 'true'
				]
			],
			'display_warning' => [
				'name' => 'Display Warning',
				'type' => 'boolean',
				'desc' => 'Show a warning when score is 3 or lower.',
				'default' => true,
				'show_if' => [
					'enabled', '=', 'true'
				],
			],
			'display_suggestions' => [
				'name' => 'Display Suggestions',
				'type' => 'boolean',
				'desc' => 'Show a suggestions about better password.',
				'default' => true,
				'show_if' => [
					'enabled', '=', 'true'
				],
			],
		],
];
