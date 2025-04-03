<?php

namespace MyAAC\Plugin;

// some PCs are really slow...
ini_set('max_execution_time', 300);

class Items
{
	private array $exist = [];

	private $db;

	/**
	 * Constructor
	 * Just ensure that table is loaded
	 *
	 * @param $db
	 */
	public function __construct($db) {
		$this->db = $db;
	}

	/**
	 * Loads items.xml or display error
	 *
	 * @param $itemsXMLPath
	 * @return void
	 */
	public function load($itemsXMLPath)
	{
		// Checks the items.xml file on your server.
		if(file_exists($itemsXMLPath)) {
			$items = new \DOMDocument();
			if(!$items->load($itemsXMLPath)) {
				throw new \RuntimeException('ERROR: Cannot load <i>items.xml</i> - the file is malformed. Check the file with xml syntax validator.');
			}
		}
		else {
			error("Error: cannot load <b>items.xml</b>! File doesn't exist.");
			return;
		}

		// Deletes all rows from the list_of_items table
		$this->db->query("DELETE FROM `" . TABLE_PREFIX . "list_of_items`;");

		$this->loadItemsIntoDatabase($items);
	}

	/**
	 * Load items to database
	 * Works with fromid and toit too!
	 *
	 * @param $items
	 * @return void
	 */
	private function loadItemsIntoDatabase($items)
	{
		// Insert items into the database
		foreach($items->getElementsByTagName('item') as $item)
		{
			if ($item->getAttribute('fromid')) {
				for ($id = $item->getAttribute('fromid'); $id <= $item->getAttribute('toid'); $id++) {
					$this->parseItem($id, $item);
				}
			} else {
				$this->parseItem($item->getAttribute('id'), $item);
			}
		}
	}

	/**
	 * Parse item node
	 *
	 * @param $id
	 * @param $item
	 * @return void
	 */
	function parseItem($id, $item)
	{
		$description = '';
		$type = '';
		$level = 0;

		$attributes = [];
		foreach( $item->getElementsByTagName('attribute') as $attribute)
		{
			$attributes[$attribute->getAttribute('key')] = $attribute->getAttribute('value');

			if ($attribute->getAttribute('key') == 'description'){
				$description = $attribute->getAttribute('value');
				continue;
			}

			if (strtolower($attribute->getAttribute('key')) == 'weapontype') {
				$type = strtolower($attribute->getAttribute('value'));

				if ($type == 'axe' || $type == 'club' || $type == 'sword') {
					foreach( $item->getElementsByTagName('attribute') as $_attribute) {
						if($_attribute->getAttribute('key') == 'attack') {
							$level = $_attribute->getAttribute('value');
							break;
						}
					}
				}
				if ($type == 'shield') {
					foreach( $item->getElementsByTagName('attribute') as $_attribute) {
						if(strtolower($_attribute->getAttribute('key')) == 'defense') {
							$level = $_attribute->getAttribute('value');
							break;
						}
					}
				}

				continue;
			}

			if (strtolower($attribute->getAttribute('key')) == 'slottype' && empty($type)) {
				$type = strtolower($attribute->getAttribute('value'));

				if ($type == 'head' || $type == 'body' || $type == 'legs' || $type == 'feet') {
					foreach( $item->getElementsByTagName('attribute') as $_attribute) {
						if($_attribute->getAttribute('key') == 'armor') {
							$level = $_attribute->getAttribute('value');
							break;
						}
					}
				}
				else if ($type == 'backpack') {
					foreach( $item->getElementsByTagName('attribute') as $_attribute) {
						if(strtolower($_attribute->getAttribute('key')) == 'containersize') {
							$level = $_attribute->getAttribute('value');
							break;
						}
					}
				}
			}

			if (strtolower($attribute->getAttribute('key')) == 'primarytype' && empty($type)) {
				switch(strtolower($attribute->getAttribute('value'))) {
					case 'helmets':
						$type = 'head';
						break;
					case 'armors':
						$type = 'body';
						break;
					case 'legs':
						$type = 'legs';
						break;
					case 'boots':
						$type = 'feet';
						break;
					case 'shields':
						$type = 'shield';
						break;
					case 'rings':
						$type = 'ring';
						break;
					case 'amulets and necklaces':
						$type = 'necklace';
				}

				if ($type == 'head' || $type == 'body' || $type == 'legs' || $type == 'feet') {
					foreach( $item->getElementsByTagName('attribute') as $_attribute) {
						if(strtolower($_attribute->getAttribute('key')) == 'armor') {
							$level = $_attribute->getAttribute('value');
							break;
						}
					}
				}
			}
		}

		if (!isset($this->exist[$id])) {
			$this->db->insert(TABLE_PREFIX . 'list_of_items', [
				'id' => $id,
				'name' => $item->getAttribute('name'),
				'description' => $description,
				'level' => $level,
				'type' => $type,
				'attributes' => json_encode($attributes),
			]);

			$this->exist[$id] = true;
		}
		else {
			warning('Duplicated item id: ' . $id);
		}
	}
}
