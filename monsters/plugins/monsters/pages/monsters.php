<?php
/**
 * Monsters
 *
 * @package   myaac-monsters
 * @author    Gesior <jerzyskalski@wp.pl>
 * @author    Slawkens <slawkens@gmail.com>
 * @author    Lee
 * @copyright 2020 MyAAC
 * @link      https://my-aac.org
 */
defined('MYAAC') or die('Direct access not allowed!');
$title = 'Monsters';

require_once PLUGINS . 'monsters/vendor/autoload.php';

use MyAAC\Plugins\Monsters\Models\Monster as MonsterModel;
use \MyAAC\Plugins\Monsters\Base as BaseMonsters;

$reload = isset($_REQUEST['reload']) && (int)$_REQUEST['reload'] == 1;

if($reload && admin()) {
	BaseMonsters::reloadMonsters(true);
}

if(admin()) {
	echo $twig->render('monsters/views/reload.html.twig');
}

if (empty($_REQUEST['name'])) {
	$preview = setting('monsters.monsters_images_preview');

	// display list of monsters
	$monsters = MyAAC\Cache::remember('monsters', 30 * 60, function () use ($preview) {
		$monsters = MonsterModel::where('hide', '!=', 1)->when(!empty($_REQUEST['boss']), function ($query) {
			$query->where('rewardboss', 1);
		})->get()->toArray();

		foreach($monsters as &$monster) {
			$monster['img_link'] = _getMonsterImage($monster);
			$monster['link_full'] = getMonsterLink($monster['name'], true);
			$monster['link'] = getMonsterLink($monster['name'], false);
		}

		return $monsters;
	});

	$twig->display('monsters/views/monsters.html.twig', array(
		'monsters' => $monsters,
		'preview' => $preview
	));

	return;
}

// display monster
$monster_name = urldecode(stripslashes(ucwords(strtolower($_REQUEST['name']))));
$monsterModel = MonsterModel::where('hide', '!=', 1)->where('name', $monster_name)->first();

if ($monsterModel && isset($monsterModel->name)) {
	/** @var array $monster */
	$monster = $monsterModel->toArray();

	function sort_by_chance($a, $b): int
	{
		if ($a['chance'] == $b['chance']) {
			return 0;
		}
		return ($a['chance'] > $b['chance']) ? -1 : 1;
	}

	$title = $monster['name'] . " - Monsters";

	$monster['img_link']= _getMonsterImage($monster);

	$voices = json_decode($monster['voices'] ?? '', true);
	$summons = json_decode($monster['summons'] ?? '', true);
	$elements = json_decode($monster['elements'] ?? '', true);
	$immunities = json_decode($monster['immunities'] ?? '', true);
	$loot = json_decode($monster['loot'] ?? '', true);
	if (!empty($loot)) {
		usort($loot, 'sort_by_chance');
	}

	foreach ($loot as &$item) {
		if (!isset($item['name'])) {
			$item['name'] = getItemNameById($item['id']);
		}

		$item['rarity_chance'] = round($item['chance'] / 1000, 2);
		$item['rarity'] = getItemRarity($item['chance']);
		$item['tooltip'] = ucfirst($item['name']) . '<br/>Chance: ' . $item['rarity'] . (setting('monsters.monsters_loot_percentage') ? ' ('. $item['rarity_chance'] .'%)' : '') . '<br/>Max count: ' . $item['count'];
	}

	foreach ($summons as &$summon) {
		$summon['link'] = getMonsterLink($summon['name'], true);
	}

	$monster['loot'] = $loot ?? null;
	$monster['voices'] = $voices ?? null;
	$monster['summons'] = $summons ?? null;
	$monster['elements'] = $elements ?? null;
	$monster['immunities'] = $immunities ?? null;

	$twig->display('monsters/views/monster.html.twig', array(
		'monster' => $monster,
	));

} else {
	echo "Monster with name <b>" . htmlspecialchars($monster_name) . "</b> doesn't exist.";
}

// back button
$twig->display('monsters/views/monsters.back_button.html.twig');

function _getMonsterImage(array $monster): string
{
	$outfit = json_decode($monster['outfit'] ?? '', true);

	if (!empty($outfit['typeex'])) {
		return setting('core.item_images_url') . $outfit['typeex'] . setting('core.item_images_extension');
	}

	if (isset($outfit['type'])) {
		$getValue = function ($val) use ($outfit) {
			return (!empty($outfit[$val])
				? '&' . $val . '=' . $outfit[$val] : '');
		};

		return setting('core.outfit_images_url') . '?id=' . $outfit['type'] . $getValue('addons') . $getValue('head') . $getValue('body') . $getValue('legs') . $getValue('feet');
	}

	return 'plugins/monsters/assets/images/nophoto.png';
}
