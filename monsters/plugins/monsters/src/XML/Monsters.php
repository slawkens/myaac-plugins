<?php
/**
 * Creatures class
 *
 * @package   MyAAC
 * @author    Gesior <jerzyskalski@wp.pl>
 * @author    Slawkens <slawkens@gmail.com>
 * @copyright 2019 MyAAC
 * @link      https://my-aac.org
 */

namespace MyAAC\Plugins\Monsters\XML;

use MyAAC\Plugins\Monsters\Models\Monster as MonsterModel;
use MyAAC\Plugins\Monsters\Base;
use MyAAC\Plugins\Monsters\XML\MonstersList;
use MyAAC\Server\Items;

class Monsters extends Base {
	private static MonstersList $monstersList;

	const FOLDER = 'monster';
	const FILE = 'monsters.xml';

	public static function reload($show = false): bool
	{
		try {
			MonsterModel::query()->delete();
		} catch(\Exception $error) {}

		if($show) {
			echo '<h2>Reload monsters.</h2>';
			echo "<h2>All records deleted from table '" . TABLE_PREFIX . "monsters' in database.</h2>";
		}

		try {
			self::$monstersList = new MonstersList(config('data_path') . self::FOLDER . '/');
		}
		catch(\Exception $e) {
			self::$error = $e->getMessage();
			return false;
		}

		$itemsByName = [];
		Items::init();
		foreach((array)Items::$items as $id => $item) {
			$itemsByName[$item['name']] = $id;
		}

		//$names_added must be an array
		$names_added[] = '';
		//add monsters
		foreach(self::$monstersList as $lol) {
			$monster = self::$monstersList->current();
			if(!$monster->loaded()) {
				if($show) {
					warning('Error while adding monster: ' . self::$monstersList->currentFile());
				}

				continue;
			}

			//load monster mana needed to summon/convince
			$mana = $monster->getManaCost();

			//load monster name
			$name = $monster->getName();
			//load monster health
			$health = $monster->getHealth();
			//load monster speed and calculate "speed level"
			$speed_ini = $monster->getSpeed();
			if($speed_ini <= 220) {
				$speed_lvl = 1;
			} else {
				$speed_lvl = ($speed_ini - 220) / 2;
			}
			//check "is monster use haste spell"
			$defenses = $monster->getDefenses();
			$use_haste = 0;
			foreach($defenses as $defense) {
				if($defense == 'speed') {
					$use_haste = 1;
				}
			}

			//load race
			$race = $monster->getRace();
			$armor = $monster->getArmor();
			$defensev = $monster->getDefense();

			//load look
			$look = $monster->getLook();

			//load monster flags
			$flags = $monster->getFlags();
			if(!isset($flags['summonable']))
				$flags['summonable'] = '0';
			if(!isset($flags['convinceable']))
				$flags['convinceable'] = '0';

			if(!isset($flags['pushable']))
				$flags['pushable'] = '0';
			if(!isset($flags['canpushitems']))
				$flags['canpushitems'] = '0';
			if(!isset($flags['canpushcreatures']))
				$flags['canpushcreatures'] = '0';
			if(!isset($flags['runonhealth']))
				$flags['runonhealth'] = '0';
			if(!isset($flags['canwalkonenergy']))
				$flags['canwalkonenergy'] = '0';
			if(!isset($flags['canwalkonpoison']))
				$flags['canwalkonpoison'] = '0';
			if(!isset($flags['canwalkonfire']))
				$flags['canwalkonfire'] = '0';
			if(!isset($flags['hostile']))
				$flags['hostile'] = '0';
			if(!isset($flags['attackable']))
				$flags['attackable'] = '0';
			if(!isset($flags['rewardboss']))
				$flags['rewardboss'] = '0';

			$summons = $monster->getSummons();
			$loot = $monster->getLoot();
			foreach($loot as $_id => &$item) {
				if (!isset($item['count'])) {
					$item['count'] = 1;
				}

				if (isset($item['name'])) {
					if(isset($itemsByName[$item['name']])) {
						$item['id'] = $itemsByName[$item['name']];
					}
				}

				if(!\Validator::number($item['id'])) {
					if(isset($itemsByName[$item['id']])) {
						$item['id'] = $itemsByName[$item['id']];
					}
					else {
						unset($item['id']);
					}
				}
			}

			if(!in_array($name, $names_added)) {
				try {
					MonsterModel::create(array(
						'name' => $name,
						'mana' => empty($mana) ? 0 : $mana,
						'exp' => $monster->getExperience(),
						'health' => $health,
						'speed_lvl' => $speed_lvl,
						'use_haste' => $use_haste,
						'voices' => json_encode($monster->getVoices()),
						'immunities' => json_encode($monster->getImmunities()),
						'elements' => json_encode($monster->getElements()),
						'summonable' => $flags['summonable'] > 0 ? 1 : 0,
						'convinceable' => $flags['convinceable'] > 0 ? 1 : 0,
						'rewardboss' => $flags['rewardboss'] > 0 ? 1 : 0,
						'defense' => $defensev,
						'armor' => $armor,
						'race' => $race,
						'loot' => json_encode($loot),
						'outfit' => json_encode($look),
						'summons' => json_encode($summons),
						'flags' => json_encode($flags),
					));

					self::$totalsAdded++;

					if($show) {
						success('Added: ' . $name . '<br/>');
					}
				}
				catch(\Exception $error) {
					if($show) {
						warning('Error while adding monster (' . $name . '): ' . $error->getMessage());
					}
				}

				$names_added[] = $name;
			}
		}

		return true;
	}
}
