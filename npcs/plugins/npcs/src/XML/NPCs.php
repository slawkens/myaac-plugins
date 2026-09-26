<?php
/**
 * NPC class
 *
 * @package   MyAAC
 * @author    Gesior <jerzyskalski@wp.pl>
 * @author    Slawkens <slawkens@gmail.com>
 * @author    Lee
 * @copyright 2026 MyAAC
 * @link      https://my-aac.org
 */
namespace MyAAC\Plugins\NPCs\XML;

class NPCs
{
	public static array $npcs = [];

	public static function load(string $folder): array
	{
		$npcs = [];
		$xml = new \DOMDocument();
		foreach (preg_grep('~\.(xml)$~i', scandir($folder)) as $npc) {
			$xml->load($folder . $npc);
			if ($xml) {
				$element = $xml->getElementsByTagName('npc')->item(0);
				if (isset($element)) {
					$name = $element->getAttribute('name');
					if (!empty($name) && !in_array($name, $npcs)) {
						$npcs[] = strtolower($name);
					}
				}
			}
		}

		return $npcs;
	}

}
