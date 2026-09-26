<?php

namespace MyAAC\Plugins\Monsters\Models;

use Illuminate\Database\Eloquent\Model;

class Monster extends Model
{
	protected $table = 'myaac_monsters';

	protected $fillable = [
		'name',
		'mana', 'exp', 'health',
		'outfit',
		'speed_lvl', 'use_haste',
		'immunities', 'elements', 'flags',
		'race', 'vocations',
		'voices', 'loot', 'summons',
		'defense', 'armor',
		'summonable', 'convinceable', 'rewardboss',
		'hide'
	];
}
