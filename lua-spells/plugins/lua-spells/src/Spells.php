<?php

namespace MyAAC\Plugins\LuaSpells;

use MyAAC\Plugins\LuaSpells\Models\LuaSpell as LuaSpellModel;

class Spells
{
	public static function reload($show = false): bool
	{
		self::clearDatabase($show);

		$spellsFolder = config('server_path') . 'data-otservbr-global/scripts/spells';
		if (!is_dir($spellsFolder)) {
			$spellsFolder = config('server_path') . 'data/scripts/spells';
		}

		$runesFolder = config('server_path') . 'data-otservbr-global/scripts/spells/runes';
		if (!is_dir($runesFolder)) {
			$runesFolder = config('server_path') . 'data/scripts/runes';
		}

		self::loadFromLua('spells', $spellsFolder, $show);
		self::loadFromLua('runes', $runesFolder, $show);
		return true;
	}

	public static function clearDatabase($show = false)
	{
		try {
			LuaSpellModel::query()->delete();
		} catch(\Exception $error) {}

		if($show) {
			echo '<h2>Reload spells.</h2>';
			echo '<h2>All records deleted from table <b>' . TABLE_PREFIX . 'lua_spells</b> in database.</h2>';
		}
	}

	public static function loadFromLua($type, $folder, $show = false)
	{
		$files = glob($folder . '/*/*.lua') + glob($folder . '/*.lua');

		$files = array_filter($files, function($v) {
			return !str_contains($v, 'monster');
		});

		if ($type == 'spells') {
			$files = array_filter($files, function($v) {
				return !str_contains($v, 'runes');
			});
		}

		$configLuaFamiliar = config('familiarTime');
		foreach($files as $file) {
			$spellContent = file_get_contents($file);

			$spellContent = str_replace('configManager.getNumber(configKeys.FAMILIAR_TIME)', $configLuaFamiliar ?? 1, $spellContent);

			$keyword = 'spell:';
			if (str_contains($spellContent, 'rune:')) {
				$keyword = 'rune:';
			}

			$substr = substr($spellContent, strpos($spellContent, $keyword), strlen($spellContent));

			$doPhpDollar = str_replace($keyword, '$spell->', $substr);

			$doSemicolons = str_replace(array("\n", "\r"), ';', $doPhpDollar);

			$removeComments = preg_replace('/--([a-zA-Z0-9\/ =_]+);/', ';', $doSemicolons);

			$replacedSoundEffects = preg_replace('/SOUND_EFFECT_TYPE_([a-zA-Z0-9_]+)/', '1', $removeComments);

			$toBeParsed = 'use MyAAC\Plugins\LuaSpells\LuaSpell; $spell = new LuaSpell();' . $replacedSoundEffects . '; return $spell;';

			$toBeParsed = str_replace('spellId', '1', $toBeParsed);
			if (preg_match('/local cooldown = \d+/', $spellContent, $matches)) {
				preg_match('/\d+/', $matches[0], $matches2);

				$toBeParsed = "if (!defined('cooldown')) {define('cooldown', 1);}  " . $toBeParsed;
			}

			//global $whoopsHandler;
			//$whoopsHandler->addDataTable('scriptContent', [$toBeParsed]);
			//$whoopsHandler->addDataTable('scriptName', [$file]);

			/**
			 * @var LuaSpell $spell
			 */
			try {
				$spell = eval($toBeParsed);
			} catch (\ParseError $e) {
				if ($show) {
					warning('Error while parsing spell - ' . $file . ': ' . $e->getMessage());
				}

				continue;
			}

			//echo '<pre>';
			//var_dump($spell);
			//echo '</pre>';

			if (str_contains($spellContent, 'creature:conjureItem')) {
				if (preg_match('/creature:conjureItem\(\d+, \d+, \d+/', $spellContent, $matches)) {
					if (preg_match('/\d+, \d+, \d+/', $matches[0], $matches2)) {
						$explode = explode(',', $matches2[0]);
						$trimmed_array = array_map('trim', $explode);

						$reagent = $trimmed_array[0];
						$conjureId = $trimmed_array[1];
						$conjureCount = $trimmed_array[2];
					}
				}
			}

			$type = 1;
			if (str_contains($spellContent, 'creature:conjureItem(')) {
				$type = 2;
			}
			else if (isset($spell->attr['runeId'])) {
				$type = 3;
			}

			try {
				LuaSpellModel::create([
					'name' => $spell->attr['name'],
					'words' => $spell->attr['words'] ?? '',
					'group' => $spell->attr['group'] ?? '',
					'type' => $type,
					'mana' => $spell->attr['mana'] ?? 0,
					'level' => $spell->attr['level'] ?? 0,
					'maglevel' => $spell->attr['magicLevel'] ?? 0,
					'soul' => $spell->attr['soul'] ?? 0,
					'premium' => ($spell->attr['isPremium'] ?? false) ? 1 : 0,
					'vocations' => json_encode($spell->attr['vocations'] ?? []),
					'conjure_count' => $conjureCount ?? 0,
					'conjure_id' => $conjureId ?? 0,
					'reagent' => $reagent ?? 0,
					'rune_id' => $spell->attr['runeId'] ?? 0,
					'hide' => 0,
				]);

				if ($show) {
					success('Added: ' . $spell->attr['name'] . '<br/>');
				}
			} catch (\PDOException $error) {
				if ($show) {
					warning('Error while adding spell (' . $spell->attr['name'] . '): ' . $error->getMessage());
				}
			}
		}
	}
}
