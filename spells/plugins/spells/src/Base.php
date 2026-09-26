<?php

namespace MyAAC\Plugins\Spells;

use MyAAC\Plugins\Spells\Lua\Spells as SpellsLua;
use MyAAC\Plugins\Spells\Models\Spell as SpellModel;
use MyAAC\Plugins\Spells\XML\Spells as SpellsXML;

class Base
{
	const TYPE_INSTANT = 1;
	const TYPE_CONJURE = 2;
	const TYPE_RUNE = 3;

	const GROUP_NONE = 0;
	const GROUP_ATTACK = 1;
	const GROUP_HEALING = 2;
	const GROUP_SUMMON = 3;
	const GROUP_SUPPLY = 4;
	const GROUP_SUPPORT = 5;

	public static array $totalsAdded = [
		self::TYPE_INSTANT => 0,
		self::TYPE_CONJURE => 0,
		self::TYPE_RUNE => 0,
	];

	protected static string $error = '';
	public static function getError(): string {
		return self::$error;
	}

	public static function reloadSpells(bool $show = false): void
	{
		$startTime = microtime(true);
		$success = true;

		if (file_exists(config('data_path') . SpellsXML::FILE)) {
			SpellsXML::reload($show);
			$totals = SpellsXML::$totalsAdded;
		}
		else {
			$canaryDataPack = config('dataPackDirectory');
			$canaryDataPack = $canaryDataPack ?? 'data-otservbr-global';

			$spellsFolder = SpellsLua::getSpellsFolder($canaryDataPack);

			if (is_dir($spellsFolder)) {
				if (!SpellsLua::reload($show)) {
					error(SpellsLua::getError());
				}
				$totals = SpellsLua::$totalsAdded;
			}
			else {
				$success = false;
			}
		}

		if ($success) {
			$endTime = round(microtime(true) - $startTime, 3);
			success("Spells have been loaded. (Total instant: {$totals[SpellsLua::TYPE_INSTANT]}, conjure: {$totals[SpellsLua::TYPE_CONJURE]}, runes: {$totals[SpellsLua::TYPE_RUNE]}) (In {$endTime} seconds)");
		}
		else {
			error('Any spells folder (both XML and Lua) could not be found.');
		}
	}

	public static function clearDatabase($show = false): void
	{
		try {
			SpellModel::query()->delete();
		} catch(\Exception $error) {
			error("Error clearing spells: " . $error->getMessage());
		}

		if($show) {
			echo '<h2>Reload spells.</h2>';
			echo '<h2>All records deleted from table <b>' . TABLE_PREFIX . 'spells</b> in database.</h2>';
		}
	}

	public static function getGroupByName(string $name)
	{
		switch ($name) {
			case 'attack':
				return self::GROUP_ATTACK;
			case 'healing':
				return self::GROUP_HEALING;
			case 'summon':
				return self::GROUP_SUMMON;
			case 'supply':
				return self::GROUP_SUPPLY;
			case 'support':
				return self::GROUP_SUPPORT;
		}

		return self::GROUP_NONE;
	}

	public static function getTypeById(int $id)
	{
		switch ($id) {
			case self::TYPE_INSTANT:
				return 'Instant';
			case self::TYPE_CONJURE:
				return 'Conjure';
			case self::TYPE_RUNE:
				return 'Rune';
		}

		return 'Unknown';
	}
}
