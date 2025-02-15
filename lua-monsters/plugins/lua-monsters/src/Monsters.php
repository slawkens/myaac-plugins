<?php

namespace MyAAC\Plugins\LuaMonsters;

use MyAAC\Items;
use MyAAC\Plugins\LuaMonsters\Models\LuaMonster as LuaMonsterModel;

class Monsters
{
	public static function reload($show = false): bool
	{
		self::clearDatabase($show);
		self::loadFromLua(config('server_path') . 'data-otservbr-global/monster', $show);

		return true;
	}

	public static function clearDatabase($show = false)
	{
		try {
			LuaMonsterModel::query()->delete();
		} catch(\Exception $error) {}

		if($show) {
			echo '<h2>Reload monsters.</h2>';
			echo '<h2>All records deleted from table <b>' . TABLE_PREFIX . 'lua_monsters</b> in database.</h2>';
		}
	}

	public static function loadFromLua($folder, $show = false): void
	{
		set_time_limit(60);

		$rii = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($folder));
		$files = [];

		foreach ($rii as $file) {
			if ($file->isDir()){
				continue;
			}

			$files[] = $file->getPathname();
		}

		$itemsReversed = [];
		Items::load();
		foreach((array)Items::$items as $id => $item) {
			$itemsReversed[$item['name']] = $id;
		}

		$luaMonstersLoader = file_get_contents(__DIR__ . '/MonsterLoader.lua');

		foreach ($files as $file) {
			if (str_contains($file, '_functions')) {
				continue;
			}

			$monsterFileContent = file_get_contents($file);
			//$monsterFileContent = str_replace('dofile("data-otservbr-global/monster/', 'dofile("' . config
			//('data_path') . 'monster/', $monsterFileContent);

			$explodeMonster = preg_split('/\r\n|\r|\n/', $monsterFileContent);
			$newStr = '';
			foreach( $explodeMonster as $line ) {
				if (!str_contains($line, 'Zone.getByName(') && !str_contains($line, 'zone:getPositions()')) {
					$newStr .= $line . PHP_EOL;
				}
			}

			$replaceSoulWarQuest = str_replace('SoulWarQuest.goshnarsCrueltyWaveInterval', 1, $newStr);

			$removeStorage = preg_replace('/	storage([a-zA-Z0-9\/ =_.]+)/', '	storage = 1', $replaceSoulWarQuest);

			// determine name, weird hack
			preg_match('/Game\.createMonsterType\("([a-zA-Z0-9\/ \'=\-_.()]+)"\)/', $removeStorage, $matches);
			$name = $matches[1] ?? 'unknown-name';

			//if ($name == 'unknown-name') {
			//	var_dump($file);
			//}

			$lua = new \Lua();
			try {
				$luaCode = $luaMonstersLoader . $removeStorage . ' return getMonster()';
				$monster = $lua->eval($luaCode);

				$speed_ini = $monster['speed'];
				if($speed_ini <= 220) {
					$speed_lvl = 1;
				} else {
					$speed_lvl = ($speed_ini - 220) / 2;
				}

				// check "if monster use haste spell"
				$use_haste = 0;
				foreach($monster['defenses'] as $defense) {
					if($defense == 'speed') {
						$use_haste = 1;
					}
				}

				// convert voices
				$voices = [];
				foreach($monster['voices'] ?? [] as $voice) {
					if (is_array($voice) && isset($voice['text'])) {
						$voices[] = $voice['text'];
					}
				}

				$immunities = [];
				foreach($monster['immunities'] ?? [] as $immunity) {
					$immunities[] = $immunity['type'];
				}

				//global $whoopsHandler;
				//$whoopsHandler->addDataTable('test', [$name]);

				$elements = [];
				foreach($monster['elements'] ?? [] as $element) {
					$elements[] = ['name' => ucfirst(self::getElementType($element['type'])), 'percent' => $element['percent']];
				}

				$loot = $monster['loot'] ?? [];
				foreach($loot as &$item) {
					if(isset($item['name']) && isset($itemsReversed[$item['name']])) {
						$item['id'] = $itemsReversed[$item['name']];
					}
				}

				LuaMonsterModel::create([
					'name' => $name,
					'mana' => $monster['manaCost'] ?? 0,
					'outfit' => json_encode($monster['outfit'] ?? []),
					'exp' => $monster['experience'],
					'health' => $monster['health'],
					'speed_lvl' => $speed_lvl,
					'use_haste' => $use_haste,
					'summonable' => ($monster['flags']['summonable'] ?? false) ? 1 : 0,
					'convinceable' => ($monster['flags']['convinceable'] ?? false) ? 1 : 0,
					'rewardboss' => ($monster['flags']['rewardBoss'] ?? false) ? 1 : 0,
					'voices' => json_encode($voices),
					'immunities' => json_encode($immunities),
					'elements' => json_encode($elements),
					'flags' => json_encode($monster['flags'] ?? []),
					'defense' => $monster['defenses']['defense'],
					'armor' => $monster['defenses']['armor'],
					'race' => $monster['race'] ?? '',
					'summons' => json_encode($monster['summons'] ?? []),
					'loot' => json_encode($loot),
				]);
			}
			catch (\Exception $exception) {
				error('Error in ' . $file . ' :: ' . $exception->getMessage());
				//echo '<pre>';
				//error($luaCode);
				//echo '</pre>';
			}
		}
	}

	private static function getElementType($type)
	{
		return match ($type) {
			0 => 'physical',
			1 => 'energy',
			2 => 'earth',
			3 => 'fire',
			4 => 'undefined',
			5 => 'lifedrain',
			6 => 'manadrain',
			7 => 'healing',
			8 => 'drown',
			9 => 'ice',
			10 => 'holy',
			11 => 'death',
			12 => 'agony',
			13 => 'neutral',
			default => 'unknown',
		};
	}
}
