<?php
defined('MYAAC') or die('Direct access not allowed!');
$title = 'Spells';

require_once PLUGINS . 'lua-spells/vendor/autoload.php';

use MyAAC\Plugins\LuaSpells\Spells;
use MyAAC\Plugins\LuaSpells\Models\LuaSpell as LuaSpellModel;

$reload = isset($_REQUEST['reload']) && (int)$_REQUEST['reload'] == 1;

if($reload && admin()) {
	Spells::reload(true);

	$totals = Spells::$totalsAdded;

	success("Spells reloaded. (Total instant: {$totals[Spells::TYPE_INSTANT]}, conjure: {$totals[Spells::TYPE_CONJURE]}, runes: {$totals[Spells::TYPE_RUNE]})");
}

if(admin()) {
	echo $twig->render('lua-spells/views/reload.html.twig');
}

if(isset($_REQUEST['vocation_id'])) {
	$vocation_id = $_REQUEST['vocation_id'];
	if($vocation_id == 'all') {
		$vocation = 'all';
	}
	else {
		$vocation = config('vocations')[$vocation_id];
	}
}
else {
	$vocation = (isset($_REQUEST['vocation']) ? urldecode($_REQUEST['vocation']) : 'all');

	if($vocation == 'all') {
		$vocation_id = 'all';
	}
	else {
		$vocation_ids = array_flip(config('vocations'));
		$vocation_id = $vocation_ids[$vocation];
	}
}

$order = 'name';
$spells = [];
$spells_db = LuaSpellModel::where('hide', '!=', 1)->where('type', '<', 4)->orderBy($order)->get();

if((string)$vocation_id != 'all') {
	foreach($spells_db as $spell) {
		$spell_vocations = json_decode($spell['vocations'], true);
		if(in_array($vocation_id, $spell_vocations) || count($spell_vocations) == 0) {
			$spell['vocations'] = null;
			$spells[] = $spell;
		}
	}
}
else {
	foreach($spells_db as $spell) {
		$vocations = json_decode($spell['vocations'], true);

		foreach($vocations as &$tmp_vocation) {
			if(isset($config['vocations'][$tmp_vocation]))
				$tmp_vocation = $config['vocations'][$tmp_vocation];
			else
				$tmp_vocation = 'Unknown';
		}

		$spell['vocations'] = implode('<br/>', $vocations);
		$spells[] = $spell;
	}
}

$showColumns = [
	'group' => LuaSpellModel::where('group', '!=', '')->count() > 0,
	'level' => LuaSpellModel::where('level', '>', 0)->count() > 0,
];

$twig->display('lua-spells/views/spells.html.twig', array(
	'post_vocation_id' => $vocation_id,
	'post_vocation' => $vocation,
	'spells' => $spells,
	'showColumns' => $showColumns,
));
