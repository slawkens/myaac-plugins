<?php

namespace MyAAC\Plugins\Spells\Models;

use Illuminate\Database\Eloquent\Model;

class Spell extends Model
{

	protected $table = 'myaac_spells';

	protected $fillable = [
		'name', 'words',
		'group', 'type',
		'level', 'maglevel', 'mana', 'soul',
		'conjure_id', 'conjure_count', 'reagent', 'rune_id',
		'premium', 'vocations',
		'hide'
	];

}
