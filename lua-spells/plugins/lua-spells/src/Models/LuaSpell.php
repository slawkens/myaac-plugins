<?php

namespace MyAAC\Plugins\LuaSpells\Models;

use Illuminate\Database\Eloquent\Model;

class LuaSpell extends Model
{
	protected $table = 'myaac_lua_spells';
	protected $fillable = [
		'name', 'words',
		'group', 'type',
		'level', 'maglevel', 'mana', 'soul',
		'conjure_id', 'conjure_count', 'reagent', 'rune_id',
		'premium', 'vocations',
		'hide'
	];

	public $timestamps = false;
}
