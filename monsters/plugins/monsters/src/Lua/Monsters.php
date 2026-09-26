<?php

namespace MyAAC\Plugins\Monsters\Lua;

use MyAAC\Items;
use MyAAC\Plugins\Monsters\Models\Monster as MonsterModel;
use MyAAC\Plugins\Monsters\Base;

class Monsters extends Base
{
	const FOLDER = 'monster';

	public static function reload($show = false): bool
	{
		self::clearDatabase($show);

		$canaryDataPack = config('dataPackDirectory');
		$canaryDataPack = $canaryDataPack ?? 'data-otservbr-global';

		$monstersFolder = self::getMonstersFolder($canaryDataPack);

		return self::load($monstersFolder, $show);
	}

	public static function load(string $folder, $show = false): bool
	{
		if (!extension_loaded('lua')) {
			self::$error = 'Monsters cannot be loaded: PHP Lua extension is not loaded.';
			return false;
		}

		$rii = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($folder));
		$files = [];

		foreach ($rii as $file) {
			if ($file->isDir()){
				continue;
			}

			$files[] = $file->getPathname();
		}

		$itemsByName = [];
		Items::init();
		foreach((array)Items::$items as $id => $item) {
			$itemsByName[$item['name']] = $id;
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

				foreach($loot as $_id => &$item) {
					if (isset($item['maxCount'])) {
						$item['count'] = $item['maxCount'];
						unset($item['maxCount']);
					}
					elseif (!isset($item['count'])) {
						$item['count'] = 1;
					}

					if (isset($item['name'])) {
						if(isset($itemsByName[$item['name']])) {
							$item['id'] = $itemsByName[$item['name']];
						}
					}

					if(isset($item['id']) && !\Validator::number($item['id'])) {
						if(isset($itemsByName[$item['id']])) {
							$item['id'] = $itemsByName[$item['id']];
						}
					}
				}

				$outfitToSave = [];
				$outfit = $monster['outfit'] ?? [];
				foreach ($outfit as $key => $value) {
					$newKey = str_replace('look', '', strtolower($key));
					$outfitToSave[$newKey] = $value;
				}

				try {
					MonsterModel::create([
						'name' => $name,
						'mana' => $monster['manaCost'] ?? 0,
						'outfit' => json_encode($outfitToSave),
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
						'summons' => json_encode($monster['summon']['summons'] ?? []),
						'loot' => json_encode($loot),
					]);

					self::$totalsAdded++;

					if ($show) {
						success('Added: ' . ($name ?? 'Unknown'));
					}

				} catch (\PDOException $error) {
					if ($show) {
						warning('Error while adding monster - ' . $name . ' - ' . $error->getMessage());
					}
				}
			}
			catch (\Exception $exception) {
				throw $exception;
				//echo '<pre>';
				//error($luaCode);
				//echo '</pre>';
			}
		}

		return true;
	}

	private static function getElementType($type)
	{
		$type = (int) $type;
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

	public static function getMonstersFolder(string $canaryDataPack): string
	{
		$monstersFolder = config('server_path') . $canaryDataPack . '/' . self::FOLDER;
		if (!is_dir($monstersFolder)) {

			$monstersFolder = config('server_path') . 'data/' . self::FOLDER;
		}

		return $monstersFolder;
	}
}
