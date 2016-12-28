<?php

namespace Ilfate\WhiteHorde;

use Ilfate\WhiteHorde;

class Settlement extends WhiteHorde
{
	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'settlements';

	protected $saveable = ['id', 'user_id', 'name', 'data', 'resources', 'inventory', 'events'];

	protected static $attributesList = [
		'age',
		'threatLevel',
	];

	/**
	 * Get user.
	 */
	public function user()
	{
		return $this->hasOne('Ilfate\User', 'id', 'user_id');
	}

	public function export()
	{
		$data = [
			'name' => $this->name,
			'inventory' => $this->exportInventory(),
			'resources' => $this->getResources(),
		];
		return $data;
	}

	public function init()
	{
		$this->extractAttributes();
	}

	public function prepareToSave()
	{
		$this->compressAttributes();
		$this->compressInventory();
		$this->compressResources();
	}

	public function getInventory()
	{
		if (is_array($this->inventory)) {
			return $this->inventory;
		}
		if ($this->inventory) {
			$this->inventory = json_decode($this->inventory, true);
		}
		return $this->inventory;
	}

	protected function compressInventory()
	{
		if (is_array($this->inventory)) {
			$this->inventory = json_encode($this->inventory);
		}
	}

	public function getResources()
	{
		if (is_array($this->resources)) {
			return $this->resources;
		}
		if ($this->resources) {
			$this->resources = json_decode($this->resources, true);
		}
		return $this->resources;
	}

	protected function compressResources()
	{
		if (is_array($this->resources)) {
			$this->resources = json_encode($this->resources);
		}
	}

	protected function exportInventory()
	{
		$inventory = $this->getInventory();
		if (!$inventory) return [];
		$itemsConfig = \Config::get('whiteHorde.items.list');
		$return = [];
		foreach ($inventory as $itemName => $quantity) {
			if (empty($itemsConfig[$itemName])) {
				throw new \Exception('Item ' . $itemName . ' has no definition');
			}
			$item = $itemsConfig[$itemName];
			$item['code'] = $itemName;
			$item['q'] = $quantity;
			$return[] = $item;
		}
		return $return;
	}

	public function addItem($itemName, $quantity = 1)
	{
		$inventory = $this->getInventory();
		if (!empty($this->inventory[$itemName])) {
			$inventory[$itemName] += $quantity;
		} else {
			$inventory[$itemName] = $quantity;
		}
		$this->inventory = $inventory;
		$this->wasUpdated();
	}
}
