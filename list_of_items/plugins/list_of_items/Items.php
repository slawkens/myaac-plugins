<?php

namespace MyAAC\Plugin;

// some PCs are really slow...
ini_set('max_execution_time', 300);

class Items
{
	private array $exist = [];

	private $db;

	public function __construct($db) {
		$this->db = $db;
	}

	public function load($file): void
	{
		// Checks the items.xml file on your server.
		if(!file_exists($file)) {
			error("Error: cannot load <b>items.xml</b>! File doesn't exist.");
			return;
		}

		// Deletes all rows from the list_of_items table
		$this->db->query("DELETE FROM `" . TABLE_PREFIX . "list_of_items`;");

		$this->loadItemsIntoDatabase($file);
	}

	private function loadItemsIntoDatabase(string $file): void
	{
		try {
			$xml = new \SimpleXMLElement(file_get_contents($file));
		} catch (\Exception $e) {
			error('Error: Cannot load items.xml. More info in system/logs/error.log file.');
			log_append('error.log', "[" . __CLASS__ . "] Fatal error: Cannot load items.xml - $file. Error: " . $e->getMessage());
			return;
		}

		// Insert items into the database
		foreach($xml->xpath('item') as $item) {
			if ($item->attributes()->fromid) {
				for ($id = (int)$item->attributes()->fromid; $id <= (int)$item->attributes()->toid; $id++) {
					$this->parseItem($id, $item);
				}
			} else {
				$this->parseItem((int)$item->attributes()->id, $item);
			}
		}
	}

	function parseItem(int $id, $item): void
	{
		$description = '';
		$type = '';
		$level = 0;

		$attributes = [];
		foreach($item->xpath('attribute') as $attribute) {
			$key = strtolower((string)$attribute->attributes()->key);
			$value = strtolower((string)$attribute->attributes()->value);

			$attributes[$key] = $value;

			if ($key == 'description'){
				$description = $value;
				continue;
			}

			if ($key == 'weapontype') {
				$type = $value;

				if ($type == 'axe' || $type == 'club' || $type == 'sword') {
					foreach($item->xpath('attribute') as $_attribute) {
						if (strtolower((string)$_attribute->attributes()->key) == 'attack') {
							$level = strtolower((string)$_attribute->attributes()->value);
							break;
						}
					}
				}
				if ($type == 'shield') {
					foreach($item->xpath('attribute') as $_attribute) {
						if (strtolower((string)$_attribute->attributes()->key) == 'defense') {
							$level = strtolower((string)$_attribute->attributes()->value);
							break;
						}
					}
				}

				continue;
			}

			if ($key == 'slottype' && empty($type)) {
				$type = $value;

				if ($type == 'head' || $type == 'body' || $type == 'legs' || $type == 'feet') {
					foreach ($item->xpath('attribute') as $_attribute) {
						if (strtolower((string)$_attribute->attributes()->key) == 'armor') {
							$level = strtolower((string)$_attribute->attributes()->value);
							break;
						}
					}
				}
				else if ($type == 'backpack') {
					foreach ($item->xpath('attribute') as $_attribute) {
						if (strtolower((string)$_attribute->attributes()->key) == 'containersize') {
							$level = strtolower((string)$_attribute->attributes()->value);
							break;
						}
					}
				}
			}

			if ($key == 'primarytype' && empty($type)) {
				switch($value) {
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
					foreach ($item->xpath('attribute') as $_attribute) {
						if (strtolower((string)$_attribute->attributes()->key) == 'armor') {
							$level = strtolower((string)$_attribute->attributes()->value);
							break;
						}
					}
				}
			}
		}

		if (!isset($this->exist[$id])) {
			$this->db->insert(TABLE_PREFIX . 'list_of_items', [
				'id' => $id,
				'name' => (string)$item->attributes()->name,
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
