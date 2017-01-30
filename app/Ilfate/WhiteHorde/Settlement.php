<?php

namespace Ilfate\WhiteHorde;

use Ilfate\WhiteHorde;

class Settlement extends WhiteHorde
{
	const RESOURCE_STEEL   = 'steel';
	const RESOURCE_WOOD    = 'wood';
	const RESOURCE_GOLD    = 'gold';
	const RESOURCE_STONE   = 'stone';
	const RESOURCE_FOOD    = 'food';
	const RESOURCE_BONES   = 'bones';
	const RESOURCE_TUSKS   = 'tusks';
	const RESOURCE_LEATHER = 'leather';

	use ItemsStorageTrait;
	
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

	protected $resourcesChanged = [];

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
			'resources' => $this->exportResources(),
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
		if (!$this->resources) return [];

		return $this->resources;
	}

	protected function compressResources()
	{
		if (is_array($this->resources)) {
			$this->resources = json_encode($this->resources);
		}
	}

	protected function exportResources($onlyChanged = false)
	{
		$return = [];
		$resources = $this->getResources();
		foreach ($resources as $name => $resource) {
			if ($onlyChanged && !in_array($name, $this->resourcesChanged)) continue;
			$return[] = ['name' => $name, 'value' => $resource[0], 'income' => $resource[1]];
		}
		return $return;
	}

	public function resource($name, $value)
	{
		$resources = $this->getResources();
		if (!empty($resources[$name])) {
			$resources[$name][0] += $value;
		} else {
			$resources[$name] = [$value, 0];
		}
		$this->resources = $resources;
		if (!in_array($name, $this->resourcesChanged)) $this->resourcesChanged[] = $name;
		$this->wasUpdated();
	}

	public function income($name, $value)
	{
		$resources = $this->getResources();
		if (!empty($resources[$name])) {
			$resources[$name][1] += $value;
		} else {
			$resources[$name] = [0, $value];
		}
		$this->resources = $resources;
		if (!in_array($name, $this->resourcesChanged)) $this->resourcesChanged[] = $name;
		$this->wasUpdated();
	}

	public function exportChangedResources()
	{
		if (!$this->resourcesChanged) return false;
		return $this->exportResources(true);
	}

	protected function exportInventory()
	{
		$inventory = $this->getInventory();
		if (!$inventory) return [];
		$itemsConfig = \Config::get('whiteHorde.items.list');
		$return = [];
		foreach ($inventory as $itemName => $quantity) {
			if (empty($itemsConfig[$itemName])) {
				throw new WHErrorException('Item ' . $itemName . ' has no definition');
			}
			$item = $itemsConfig[$itemName];
			$item['code'] = $itemName;
			$item['q'] = $quantity;
			$return[] = $item;
		}
		return $return;
	}

	
}
