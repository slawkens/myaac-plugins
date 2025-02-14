<?php

namespace MyAAC\Plugins\LuaMonsters\Models;

use Illuminate\Database\Eloquent\Model;

class LuaMonster extends Model
{
	protected $table = 'myaac_lua_monsters';
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

	public $timestamps = false;
}
