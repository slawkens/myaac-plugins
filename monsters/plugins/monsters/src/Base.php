<?php

namespace MyAAC\Plugins\Monsters;

use MyAAC\Plugins\Monsters\Lua\Monsters as MonstersLua;
use MyAAC\Plugins\Monsters\Models\Monster as MonsterModel;
use MyAAC\Plugins\Monsters\XML\Monsters as MonstersXML;

class Base
{
	protected static string $error = '';
	public static function getError(): string {
		return self::$error;
	}

	public static int $totalsAdded = 0;

	public static function reloadMonsters(bool $show = false): void
	{
		$startTime = microtime(true);
		$success = true;

		if (file_exists(config('data_path') . MonstersXML::FOLDER . '/' . MonstersXML::FILE)) {
			if (!MonstersXML::reload($show)) {
				error(MonstersXML::getError());
				$success = false;
			}
			$totals = MonstersXML::$totalsAdded;
		}
		else {
			$canaryDataPack = config('dataPackDirectory');
			$canaryDataPack = $canaryDataPack ?? 'data-otservbr-global';

			$monstersFolder = MonstersLua::getMonstersFolder($canaryDataPack);

			if (is_dir($monstersFolder)) {
				if (!MonstersLua::reload($show)) {
					error(MonstersLua::getError());
				}
				$totals = MonstersLua::$totalsAdded;
			}
			else {
				$success = false;
			}
		}

		if ($success) {
			$endTime = round(microtime(true) - $startTime, 3);
			success("Monsters have been loaded. (Total added: {$totals}) (In {$endTime} seconds)");
		}
		else {
			error('Any monsters folder (both XML and Lua) could not be found.');
		}
	}

	public static function clearDatabase($show = false): void
	{
		try {
			MonsterModel::query()->delete();
		} catch(\Exception $error) {
			error('Error clearing monsters: ' . $error->getMessage());
		}

		if($show) {
			echo '<h2>Reload monsters.</h2>';
			echo '<h2>All records deleted from table <b>' . TABLE_PREFIX . 'monsters</b> in database.</h2>';
		}
	}
}
