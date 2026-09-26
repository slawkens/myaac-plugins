<?php
/**
 * @package POT
 * @version 0.1.5
 * @author Wrzasq <wrzasq@gmail.com>
 * @author slawkens <slawkens@gmail.com>
 * @copyright 2007 - 2008 (C) by Wrzasq
 * @license http://www.gnu.org/licenses/lgpl-3.0.txt GNU Lesser General Public License, Version 3
 */

namespace MyAAC\Plugins\Spells\XML;

use MyAAC\Plugins\Spells\Base;

class SpellsList implements \IteratorAggregate, \Countable
{
	private array $runes = [];
	private array $instants = [];
	private array $conjures = [];

	public function __construct(string $file)
	{
		// check if spells.xml exist
		if(!@file_exists($file)) {
			log_append('error.log', '[OTS_SpellsList.php] Fatal error: Cannot load spells.xml. File does not exist. (' . $file . ').');
			throw new \Exception('Error: Cannot load spells.xml. File not found.');
		}

		// loads monsters mapping file
		$spells = new \DOMDocument();
		if(!@$spells->load($file)) {
			log_append('error.log', '[OTS_SpellsList.php] Fatal error: Cannot load spells.xml (' . $file . '). Error: ' . print_r(error_get_last(), true));
			throw new \Exception('Error: Cannot load spells.xml. File is invalid. More info in system/logs/error.log file.');
		}

		// loads runes
		foreach( $spells->getElementsByTagName('rune') as $rune) {
			$this->runes[ $rune->getAttribute('name') ] = new Spell(Base::TYPE_RUNE, $rune);
		}

		// loads instants
		foreach( $spells->getElementsByTagName('instant') as $instant) {
			$this->instants[ $instant->getAttribute('name') ] = new Spell(Base::TYPE_INSTANT, $instant);
		}

		// loads conjures
		foreach( $spells->getElementsByTagName('conjure') as $conjure) {
			$this->conjures[ $conjure->getAttribute('name') ] = new Spell(Base::TYPE_CONJURE, $conjure);
		}
	}

	public function getRunesList(): array {
		return array_keys($this->runes);
	}

	public function hasRune(string $name): bool {
		return isset($this->runes[$name]);
	}

	/**
	 * Returns given rune spell.
	 *
	 * @version 0.1.3
	 * @param string $name Rune name.
	 * @return Spell Rune spell wrapper.
	 * @throws \OutOfBoundsException If rune does not exist.
	 */
	public function getRune(string $name): Spell
	{
		if (isset($this->runes[$name])) {
			return $this->runes[$name];
		}

		throw new \OutOfBoundsException();
	}

	public function getInstantsList(): array {
		return array_keys($this->instants);
	}

	/**
	 * Checks if instant exists.
	 *
	 * @version 0.1.3
	 * @since 0.1.3
	 * @param string $name Instant name.
	 * @return bool If instant is set then true.
	 */
	public function hasInstant(string $name): bool {
		return isset($this->instants[$name]);
	}

	/**
	 * Returns given instant spell.
	 *
	 * @version 0.1.3
	 * @param string $name Spell name.
	 * @return Spell Instant spell wrapper.
	 * @throws \OutOfBoundsException If instant does not exist.
	 */
	public function getInstant(string $name): Spell
	{
		if (isset($this->instants[$name])) {
			return $this->instants[$name];
		}

		throw new \OutOfBoundsException();
	}

	public function getConjuresList(): array {
		return array_keys($this->conjures);
	}

	public function hasConjure(string $name): bool {
		return isset($this->conjures[$name]);
	}

	public function getConjure(string $name): Spell
	{
		if( isset($this->conjures[$name]) )
		{
			return $this->conjures[$name];
		}

		throw new \OutOfBoundsException();
	}

	/**
	 * Iterator for all spells.
	 *
	 * <p>
	 * Returned object will continousely iterate through all kind of spells.
	 * </p>
	 *
	 * @version 0.1.5
	 * @since 0.1.5
	 * @return \AppendIterator Iterator for all spells.
	 */
	public function getIterator(): \Traversable
	{
		$iterator = new \AppendIterator();
		$iterator->append( new \ArrayIterator($this->runes) );
		$iterator->append( new \ArrayIterator($this->instants) );
		$iterator->append( new \ArrayIterator($this->conjures) );
		return $iterator;
	}

	/**
	 * Number of all loaded spells.
	 *
	 * @version 0.1.5
	 * @since 0.1.5
	 * @return int Amount of all spells.
	 */
	public function count(): int {
		return count($this->runes) + count($this->instants) + count($this->conjures);
	}
}
