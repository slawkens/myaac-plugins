<?php
/**
 * Spells class
 *
 * @package   MyAAC
 * @author    Gesior <jerzyskalski@wp.pl>
 * @author    Slawkens <slawkens@gmail.com>
 * @copyright 2019 MyAAC
 * @link      https://my-aac.org
 */

namespace MyAAC\Plugins\Spells\XML;

use MyAAC\Plugins\Spells\Models\Spell;
use MyAAC\Plugins\Spells\Base;

class Spells extends Base
{
	const FILE = 'spells/spells.xml';

	public static function reload($show = false): bool
	{
		self::clearDatabase($show);

		try {
			$spellsList = new SpellsList(config('data_path') . self::FILE);
		}
		catch(\Exception $e) {
			self::$error = $e->getMessage();
			return false;
		}

		//add conjure spells
		$conjurelist = $spellsList->getConjuresList();
		if($show) {
			echo "<h3>Conjure:</h3>";
		}

		foreach($conjurelist as $spellname) {
			$spell = $spellsList->getConjure($spellname);
			$name = $spell->getName();

			$words = $spell->getWords();
			if(strpos($words, '#') !== false) {
				continue;
			}

			try {
				Spell::create(array(
					'name' => $name,
					'words' => $words,
					'type' => self::TYPE_CONJURE,
					'mana' => $spell->getMana(),
					'level' => $spell->getLevel(),
					'maglevel' => $spell->getMagicLevel(),
					'soul' => $spell->getSoul(),
					'premium' => $spell->isPremium() ? 1 : 0,
					'vocations' => json_encode($spell->getVocations()),
					'conjure_count' => $spell->getConjureCount(),
					'conjure_id' => $spell->getConjureId(),
					'reagent' => $spell->getReagentId(),
					'hide' => $spell->isEnabled() ? 0 : 1
				));

				self::$totalsAdded[self::TYPE_CONJURE]++;

				if($show) {
					success('Added: ' . $name . '<br/>');
				}
			}
			catch(\PDOException $error) {
				if($show) {
					warning('Error while adding spell (' . $name . '): ' . $error->getMessage());
				}
			}
		}

		// add instant spells
		$instantlist = $spellsList->getInstantsList();
		if($show) {
			echo "<h3>Instant:</h3>";
		}

		foreach($instantlist as $spellname) {
			$spell = $spellsList->getInstant($spellname);
			$name = $spell->getName();

			$words = $spell->getWords();
			if(strpos($words, '#') !== false)
				continue;

			try {
				Spell::create(array(
					'name' => $name,
					'words' => $words,
					'type' => self::TYPE_INSTANT,
					'mana' => $spell->getMana(),
					'level' => $spell->getLevel(),
					'maglevel' => $spell->getMagicLevel(),
					'soul' => $spell->getSoul(),
					'premium' => $spell->isPremium() ? 1 : 0,
					'vocations' => json_encode($spell->getVocations()),
					'conjure_count' => 0,
					'hide' => $spell->isEnabled() ? 0 : 1
				));

				self::$totalsAdded[self::TYPE_INSTANT]++;

				if($show) {
					success('Added: ' . $name . '<br/>');
				}
			}
			catch(\PDOException $error) {
				if($show) {
					warning('Error while adding spell (' . $name . '): ' . $error->getMessage());
				}
			}
		}

		// add runes
		$runeslist = $spellsList->getRunesList();
		if($show) {
			echo "<h3>Runes:</h3>";
		}

		foreach($runeslist as $spellname) {
			$spell = $spellsList->getRune($spellname);

			$name = $spell->getName() . (str_contains($spell->getName(), 'Rune') ? '' : ' Rune');

			try {
				Spell::create(array(
					'name' => $name,
					'words' => $spell->getWords(),
					'type' => self::TYPE_RUNE,
					'mana' => $spell->getMana(),
					'level' => $spell->getLevel(),
					'maglevel' => $spell->getMagicLevel(),
					'soul' => $spell->getSoul(),
					'premium' => $spell->isPremium() ? 1 : 0,
					'vocations' => json_encode($spell->getVocations()),
					'conjure_count' => 0,
					'rune_id' => $spell->getID(),
					'hide' => $spell->isEnabled() ? 0 : 1
				));

				self::$totalsAdded[self::TYPE_RUNE]++;

				if($show) {
					success('Added: ' . $name . '<br/>');
				}
			}
			catch(\PDOException $error) {
				if($show) {
					warning('Error while adding spell (' . $name . '): ' . $error->getMessage());
				}
			}
		}

		return true;
	}
}
