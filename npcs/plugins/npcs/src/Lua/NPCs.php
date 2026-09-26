<?php
namespace MyAAC\Plugins\NPCs\Lua;

class NPCs
{
	const NPCS_FOLDER = 'npc';

	public static function load(string $folder): array
	{
		$npcs = [];
		$glob = glob($folder . '*.lua');
		foreach ($glob as $npc) {
			if (str_contains($npc, '_functions') || str_contains($npc, 'hireling.lua')) {
				continue;
			}

			$file = fopen($npc, 'r');

			$luaScript = '';

			$index = 0;
			while ((( $line = fgets( $file )) !== false) && ( $index++ < 5 )) {
				if (str_contains($line, 'dofile')) {
					continue;
				}

				$luaScript .= $line;
				if (str_contains($line, 'Game.createNpcType')) {
					break;
				}
			}

			$baseLua = file_get_contents(__DIR__ . '/npc.lua');

			$lua = new \Lua();
			try {
				//global $whoopsHandler;
				//$whoopsHandler->addDataTable('test', [$baseLua . $luaScript . ' return _npcName']);

				$name = $lua->eval($baseLua . $luaScript . ' return _npcName');
				$npcs[] = $name;
			}
			catch (\Exception $exception) {
				throw $exception;
			}

			fclose($file);
		}

		return $npcs;
	}

	public static function getNPCsFolder(): string
	{
		$canaryDataPack = config('dataPackDirectory');
		$canaryDataPack = $canaryDataPack ?? 'data-otservbr-global';

		$npcFolder = config('server_path') . 'data/' . self::NPCS_FOLDER;
		if (!is_dir($npcFolder)) {
			$npcFolder = config('server_path') . $canaryDataPack . '/' . self::NPCS_FOLDER;
		}

		return $npcFolder . '/';
	}
}
