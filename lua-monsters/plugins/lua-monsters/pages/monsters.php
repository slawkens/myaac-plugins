<?php
defined('MYAAC') or die('Direct access not allowed!');
$title = 'Monsters';

require_once PLUGINS . 'lua-monsters/vendor/autoload.php';

use MyAAC\Plugins\LuaMonsters\Monsters;
use MyAAC\Plugins\LuaMonsters\Models\LuaMonster as LuaMonsterModel;
use MyAAC\Timer;

$reload = isset($_REQUEST['reload']) && (int)$_REQUEST['reload'] == 1;

if($reload && admin()) {
	$timer = new Timer();

	Monsters::reload(true);

	$timeTotal = round($timer->elapsed(), 2);

	success("Monsters reloaded in $timeTotal seconds.");
}

if(admin()) {
	echo $twig->render('lua-monsters/views/reload.html.twig');
}

if (empty($_REQUEST['name'])) {
	// display list of monsters
	$preview = setting('core.monsters_images_preview');
	$monsters = LuaMonsterModel::where('hide', '!=', 1)->when(!empty($_REQUEST['boss']), function ($query) {
		$query->where('rewardboss', 1);
	})->get()->toArray();

	if ($preview) {
		foreach($monsters as $key => &$monster)
		{
			$monster['img_link'] = getMonsterImgPath($monster['name']);
		}
	}

	$twig->display('lua-monsters/views/monsters.html.twig', array(
		'monsters' => $monsters,
		'preview' => $preview
	));

	return;
}

// display monster
$monster_name = urldecode(stripslashes(ucwords(strtolower($_REQUEST['name']))));
$monsterModel = LuaMonsterModel::where('hide', '!=', 1)->where('name', $monster_name)->first();

if ($monsterModel && isset($monsterModel->name)) {
	/** @var array $monster */
	$monster = $monsterModel->toArray();

	function sort_by_chance($a, $b)
	{
		if ($a['chance'] == $b['chance']) {
			return 0;
		}
		return ($a['chance'] > $b['chance']) ? -1 : 1;
	}

	$title = $monster['name'] . " - Monsters";

	$outfit = json_decode($monster['outfit'], true);

	if (isset($outfit['lookTypeEx'])) {
		$monster['img_link'] = setting('core.item_images_url') . $outfit['lookTypeEx'] . setting('core.item_images_extension');
	}
	else {
		$monster['img_link'] = setting('core.outfit_images_url') . '?id=' . $outfit['lookType'] . (!empty($outfit['lookAddons'])
				? '&addons=' . $outfit['lookAddons'] : '') . '&head=' . $outfit['lookHead'] . '&body=' . $outfit['lookBody']
			. '&legs=' . $outfit['lookLegs'] . '&feet=' . $outfit['lookFeet'];
	}

	$voices = json_decode($monster['voices'], true);
	$summons = json_decode($monster['summons'], true);
	$elements = json_decode($monster['elements'], true);
	$immunities = json_decode($monster['immunities'], true);
	$loot = json_decode($monster['loot'], true);
	if (!empty($loot)) {
		usort($loot, 'sort_by_chance');
	}

	foreach ($loot as &$item) {
		if (isset($item['id'])) {
			$item['name'] = getItemNameById($item['id']);
		}
		else {
			$item['id'] = 0;
		}

		$item['rarity_chance'] = round($item['chance'] / 1000, 2);
		$item['rarity'] = getItemRarity($item['chance']);
		$item['tooltip'] = ucfirst($item['name']) . '<br/>Chance: ' . $item['rarity'] . (setting('core.monsters_loot_percentage') ? ' ('. $item['rarity_chance'] .'%)' : '') . '<br/>Max count: ' . ($item['maxCount'] ?? 1);
	}

	$monster['loot'] = $loot ?? null;
	$monster['voices'] = $voices ?? null;
	$monster['summons'] = $summons ?? null;
	$monster['elements'] = $elements ?? null;
	$monster['immunities'] = $immunities ?? null;

	$twig->display('lua-monsters/views/monster.html.twig', array(
		'monster' => $monster,
	));

} else {
	echo "Monster with name <b>" . htmlspecialchars($monster_name) . "</b> doesn't exist.";
}

// back button
$twig->display('lua-monsters/views/monsters.back_button.html.twig');
