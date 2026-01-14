<?php

namespace MyAAC\Plugins\LuaSpells;

class LuaSpell
{
	public array $attr = [];

	public function __call($name, $arguments)
	{
		if ($name == 'runeLevel') {
			$this->attr['level'] = $arguments[0];
			return;
		}

		if ($name == 'runeMagicLevel') {
			$this->attr['magicLevel'] = $arguments[0];
			return;
		}

		if ($name == 'vocation') {
			$configVocations = (config('vocations'));

			foreach ($configVocations as $id => &$name) {
				$name = strtolower($name);
			}

			$vocationsFlipped = array_flip($configVocations);

			$vocations = [];
			foreach ($arguments as &$voc) {
				$voc = str_replace(';true', '', $voc);
				$voc = strtolower($voc);
				$vocations[] = $vocationsFlipped[$voc];
			}

			$this->attr['vocations'] = $vocations;
			return;
		}

		$this->attr[$name] = $arguments[0];
	}

	public function register() {}
}
