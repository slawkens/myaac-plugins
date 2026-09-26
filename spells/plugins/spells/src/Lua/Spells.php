<?php

namespace MyAAC\Plugins\Spells\Lua;

use MyAAC\Plugins\Spells\Models\Spell;
use MyAAC\Plugins\Spells\Base;

class Spells extends Base
{
	const SPELLS_FOLDER = 'scripts/spells';

	private static array $runesDuplicated = [];

	public static function reload($show = false): bool
	{
		self::clearDatabase($show);

		$canaryDataPack = config('dataPackDirectory');
		$canaryDataPack = $canaryDataPack ?? 'data-otservbr-global';

		$spellsFolder = self::getSpellsFolder($canaryDataPack);

		$runesPaths = [
			config('server_path') . $canaryDataPack . '/scripts/spells/runes',
			config('server_path') . $canaryDataPack . '/scripts/runes',
			config('server_path') . 'data/scripts/runes',
			config('server_path') . 'data/scripts/spells/runes',
		];

		foreach ($runesPaths as $path) {
			if (is_dir($path)) {
				$runesFolder = $path;
			}
		}

		if (!isset($runesFolder)) {
			self::$error = 'Runes folder not found.';
			return false;
		}

		return
			self::load('spells', $spellsFolder, $show) &&
			self::load('runes', $runesFolder, $show);
	}

	public static function load(string $type, string $folder, bool $show = false): bool
	{
		$files = glob($folder . '/*/*.lua') + glob($folder . '/*.lua');

		$files = array_filter($files, function($v) {
			return !str_contains($v, 'monster');
		});
/*
		if ($type == 'spells') {
			$files = array_filter($files, function($v) {
				return !str_contains($v, 'runes');
			});
		}
*/

		$configLuaFamiliar = config('familiarTime');
		foreach($files as $file) {
			$spellContent = file_get_contents($file);

			$spellContent = str_replace('configManager.getNumber(configKeys.FAMILIAR_TIME)', $configLuaFamiliar ?? 1, $spellContent);

			$spellType = self::TYPE_INSTANT;
			if (str_contains($spellContent, 'spell:') & str_contains($spellContent, 'rune:')) {
				if ($type == 'spells') {
					$keyword = 'spell:';
					if (str_contains($spellContent, 'creature:conjureItem(')) {
						$spellType = self::TYPE_CONJURE;
					}
				}
				else {
					$keyword = 'rune:';
					$spellType = self::TYPE_RUNE;
				}
			}
			else if (str_contains($spellContent, 'spell:')) {
				$keyword = 'spell:';
				if (str_contains($spellContent, 'creature:conjureItem(')) {
					$spellType = self::TYPE_CONJURE;
				}
			}
			else if (str_contains($spellContent, 'rune:')) {
				$keyword = 'rune:';
				$spellType = self::TYPE_RUNE;
			}
			else {
				error('No spell or rune keyword found in ' . $file);
				continue;
			}

			if ($spellType == 2 && str_contains($spellContent, 'creature:conjureItem')) {
				if (str_contains($spellContent, 'creature:conjureItem(spell, ')) {
					$spellContent = str_replace('creature:conjureItem(spell, ', 'creature:conjureItem(', $spellContent);
				}

				$hasFourParams = false;
				$pattern = '\d+, \d+, \d+/';
				if (preg_match('/creature:conjureItem\(\d+, \d+, \d+, \d+/', $spellContent, $matches)) {
					$pattern = '\d+, \d+, \d+, \d+/';
					$hasFourParams = true;
				}
				if (preg_match('/creature:conjureItem\(' . $pattern, $spellContent, $matches)) {
					if (preg_match('/' . $pattern, $matches[0], $matches2)) {
						$explode = explode(',', $matches2[0]);
						$trimmed_array = array_map('trim', $explode);

						$element = 0;
						if ($hasFourParams) {
							$element = 1;
						}

						$reagent = $trimmed_array[$element + 0];
						$conjureId = $trimmed_array[$element + 1];
						$conjureCount = $trimmed_array[$element + 2];
					}
				}
			}

			$substr = substr($spellContent, strpos($spellContent, $keyword), strlen($spellContent));

			$splitByNewLine = preg_split('/\r\n|\r|\n/', $substr);

			$splitByNewLine = array_filter($splitByNewLine, function ($s) use ($keyword) {
				return str_contains($s, $keyword);
			});

			foreach ($splitByNewLine as &$line) {
				if (str_contains($line, '--')) {
					$line = substr($line, 0, strpos($line, '--'));
				}
			}

			$implode = implode("\n", $splitByNewLine);

			$doPhpDollar = str_replace($keyword, '$spell->', $implode);

			//$removeComments = preg_replace('/--([a-zA-Z0-9\/ .=_]+);/', ';', $doPhpDollar);

			$doSemicolons = str_replace(["\n", "\r"], ';', $doPhpDollar);

			$replacedSoundEffects = preg_replace('/SOUND_EFFECT_TYPE_([a-zA-Z0-9_]+)/', '1', $doSemicolons);

			$toBeParsed = 'use MyAAC\Plugins\Spells\Lua\Spell; $spell = new Spell();' . $replacedSoundEffects . '; return $spell;';

			//die($toBeParsed);

			$toBeParsed = str_replace('spellId', '1', $toBeParsed);
			if (preg_match('/local cooldown = \d+/', $spellContent, $matches)) {
				preg_match('/\d+/', $matches[0], $matches2);

				$toBeParsed = "if (!defined('cooldown')) {define('cooldown', 1);}  " . $toBeParsed;
			}

			global $whoopsHandler;
			if (isset($whoopsHandler)) {
				$whoopsHandler->addDataTable('scriptContent', [$toBeParsed]);
				$whoopsHandler->addDataTable('scriptName', [$file]);
			}

			/**
			 * @var Spell $spell
			 */
			try {
				$spell = eval($toBeParsed);
			} catch (\ParseError $e) {
				if ($show) {
					//echo '<pre>';
					//var_dump($toBeParsed);
					//echo '</pre>';

					warning('Error while parsing spell - ' . $file . ': ' . $e->getMessage());
				}

				continue;
			}

			if (isset($spell->attr['name']) && $spell->attr['name'] == 'Food') {
				if (str_contains($spellContent, '3577')) {
					$conjureId = 3577;
					$conjureCount = 1;
				}
				else if (str_contains($spellContent, '2666')) {
					$conjureId = 2666;
					$conjureCount = 1;
				}
			}

			if (isset($spell->attr['words'])) {
				if (str_contains($spell->attr['words'], '###')) {
					if ($show) {
						warning('Ignoring spell with words containing ### (' . $spell->attr['words'] . ')');
					}

					continue;
				}

				$spell->attr['words'] = str_replace(',', '', $spell->attr['words']);
			}

			if (!isset($spell->attr['name'])) {
				$tmp = basename($file, '.lua');
				$tmp = str_replace('_', ' ', $tmp);
				$tmp = ucwords($tmp);

				$spell->attr['name'] = $tmp;
			}

			if ($spellType == self::TYPE_RUNE) {
				if (isset(self::$runesDuplicated[$spell->attr['name']])) {
					continue;
				}

				self::$runesDuplicated[$spell->attr['name']] = true;
			}

			try {
				Spell::create([
					'name' => $spell->attr['name'] ?? '',
					'words' => $spell->attr['words'] ?? '',
					'group' => static::getGroupByName($spell->attr['group'] ?? ''),
					'type' => $spellType,
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

				self::$totalsAdded[$spellType]++;

				if ($show) {
					success('Added: ' .
						($spell->attr['name'] ?? $spell->attr['words'] ?? basename($file))
						. ' (' . static::getTypeById($spellType) . ') <br/>');
				}
			} catch (\PDOException $error) {
				if ($show) {
					warning('Error while adding spell (' .
						($spell->attr['name'] ?? $spell->attr['words'] ?? basename($file)) . '): ' .
						$error->getMessage());
				}
			}

			// reset variables
			$reagent = null;
			$conjureId = null;
			$conjureCount = null;
		}

		return (self::$totalsAdded[self::TYPE_INSTANT] > 0 || self::$totalsAdded[self::TYPE_CONJURE] > 0 || self::$totalsAdded[self::TYPE_RUNE] > 0);
	}

	public static function getSpellsFolder(string $canaryDataPack): string
	{
		$spellsFolder = config('server_path') . 'data/' . self::SPELLS_FOLDER;
		if (!is_dir($spellsFolder)) {
			$spellsFolder = config('server_path') . $canaryDataPack . '/' . self::SPELLS_FOLDER;
		}

		return $spellsFolder;
	}
}
