<?php

/**
 * @package POT
 * @version 0.1.3
 * @author Wrzasq <wrzasq@gmail.com>
 * @copyright 2007 - 2008 (C) by Wrzasq
 * @license http://www.gnu.org/licenses/lgpl-3.0.txt GNU Lesser General Public License, Version 3
 */

namespace MyAAC\Plugins\Spells\XML;

class Spell
{
	private int $type;

	private \DOMElement $element;

	public function __construct(int $type, \DOMElement $spell)
	{
		$this->type = $type;
		$this->element = $spell;
	}

	public function getType(): int {
		return $this->type;
	}

	public function getName(): string {
		return $this->element->getAttribute('name');
	}

	public function getID(): int {
		return (int) $this->element->getAttribute('id');
	}

	public function getWords(): string {
		return $this->element->getAttribute('words');
	}

	public function isAggressive(): bool {
		return $this->element->getAttribute('aggressive') != '0';
	}

	public function isAggresive(): bool {
		return $this->isAggressive();
	}

	public function getCharges(): int {
		return (int) $this->element->getAttribute('charges');
	}

	public function getLevel(): int {
		return (int) $this->element->getAttribute('lvl');
	}

	public function getMagicLevel(): int {
		return (int) $this->element->getAttribute('maglv');
	}

	public function getMana(): int {
		return (int) $this->element->getAttribute('mana');
	}

	public function getSoul(): int {
		return (int) $this->element->getAttribute('soul');
	}

	public function hasParams(): bool {
		return $this->element->getAttribute('params') == '1';
	}

	public function isEnabled(): bool {
		return $this->element->getAttribute('enabled') != '0';
	}

	public function isFarUseAllowed(): bool {
		return $this->element->getAttribute('allowfaruse') == '1';
	}

	public function isPremium(): bool {
		return $this->element->getAttribute('prem') == '1';
	}

	public function isLearnNeeded(): bool {
		return $this->element->getAttribute('needlearn') == '1';
	}

	public function getConjureId(): int {
		return (int) $this->element->getAttribute('conjureId');
	}

	public function getReagentId(): int {
		return (int) $this->element->getAttribute('reagentId');
	}

	public function getConjureCount(): int {
		return (int) $this->element->getAttribute('conjureCount');
	}

	public function getVocations(): array
	{
		global $config;
		if(!isset($config['vocation_ids']))
			$config['vocations_ids'] = array_flip($config['vocations']);

		$vocations = array();

		foreach( $this->element->getElementsByTagName('vocation') as $vocation)
		{
			if($vocation->getAttribute('id') != NULL) {
				$voc_id = explode(';', $vocation->getAttribute('id'));
			}
			else {
				$voc_id = $config['vocations_ids'][$vocation->getAttribute('name')];
			}

			if(is_array($voc_id)) {
				$vocations = array_merge($vocations, $voc_id);
			} else {
				$vocations[] = $voc_id;
			}
		}

		return $vocations;
	}

	public function getVocationsFull(): array
	{
		global $config;
		if(!isset($config['vocations_ids']))
			$config['vocations_ids'] = array_flip($config['vocations']);

		$vocations = [];

		foreach( $this->element->getElementsByTagName('vocation') as $vocation)
		{
			$show = $vocation->getAttribute('showInDescription');
			if($vocation->getAttribute('id') != NULL) {
				$voc_id = $vocation->getAttribute('id');
			}
			else {
				$voc_id = $config['vocations_ids'][$vocation->getAttribute('name')];
			}

			$vocations[$voc_id] = strlen($show) == 0 || $show != '0';
		}

		return $vocations;
	}
}
