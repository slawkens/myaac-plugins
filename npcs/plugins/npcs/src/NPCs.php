<?php

namespace MyAAC\Plugins\NPCs;
use MyAAC\Plugins\NPCs\Lua\NPCs as NPCsLua;
use MyAAC\Plugins\NPCs\XML\NPCs as NPCsXML;
use MyAAC\Cache\PHP as CachePHP;

class NPCs
{
	private static array $npcs = [];
	const MESSAGE_CANNOT_LOAD = 'NPCs cannot be loaded - folder does not exist: %s';

	public static function load(): bool
	{
		$npcsFolder = config('data_path') . 'npc/';
		$npcsLuaFolder = NPCsLua::getNPCsFolder();

		if (!file_exists($npcsFolder)) {
			if (!file_exists($npcsLuaFolder)) {
				error(sprintf(self::MESSAGE_CANNOT_LOAD, $npcsFolder));
				error(sprintf(self::MESSAGE_CANNOT_LOAD, $npcsLuaFolder));
				return false;
			}
		}

		$globXML = glob($npcsFolder . '*.xml');
		$globLua = glob($npcsLuaFolder . '*.lua');
		if ((!$globXML || !count($globXML)) && (!$globLua || !count($globLua))) {
			error('NPCs cannot be loaded: No NPC files found.');
			return false;
		}

		if ($globXML && count($globXML)) {
			self::$npcs = NPCsXML::load($npcsFolder);
		}
		else {
			if (!extension_loaded('lua')) {
				error('NPCs cannot be loaded: PHP Lua extension is not loaded.');
				return false;
			}

			self::$npcs = NPCsLua::load($npcsLuaFolder);
		}

		$cache_php = new CachePHP(config('cache_prefix'), CACHE . 'persistent/');
		$cache_php->set('npcs', self::$npcs, 5 * 365 * 24 * 60 * 60);
		return true;
	}

	public static function init()
	{
		if (self::$npcs) {
			return;
		}

		$cache_php = new CachePHP(config('cache_prefix'), CACHE . 'persistent/');
		self::$npcs = (array)$cache_php->get('npcs');
	}

	public static function getNPCs(): array {
		return self::$npcs;
	}
}
