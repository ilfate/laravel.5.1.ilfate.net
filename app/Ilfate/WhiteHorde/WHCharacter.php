<?php

namespace Ilfate\WhiteHorde;

use Ilfate\MageSurvival\ChanceHelper;
use Ilfate\WhiteHorde;

class WHCharacter extends WhiteHorde
{
	const GENDER_MALE = 'm';
	const GENDER_FEMALE = 'f';

	const ATTRIBUTE_AGE = 'age';
	const ATTRIBUTE_GENDER = 'gender';
	const ATTRIBUTE_NAME = 'name';
	const ATTRIBUTE_BUILDING_SLOT = 'buildingSlot';
	const ATTRIBUTE_TRAIT_EXPIRE = 'traitsExpire';
	const ATTRIBUTE_FATHER_ID = 'fatherId';
	const ATTRIBUTE_MOTHER_ID = 'motherId';
	const ATTRIBUTE_SPOUSE_ID = 'spouseId';
	
	const LOCATION_SETTLEMENT = 0;
	const LOCATION_RAID = 1;
	const LOCATION_MISSING = 2;

	const ITEM_LOCATION_HEAD = 'head';
	const ITEM_LOCATION_BODY = 'body';
	const ITEM_LOCATION_FEET = 'feet';
	const ITEM_LOCATION_HAND = 'hand';
	const ITEM_LOCATION_OFF_HAND = 'offHand';

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'wh_characters';

	protected $saveable = ['id', 'settlement_id', 'raid_id', 'building_id', 'location', 'data', 'traits', 'items'];

	protected static $attributesList = [
		self::ATTRIBUTE_AGE,
		self::ATTRIBUTE_GENDER,
		self::ATTRIBUTE_NAME,
		self::ATTRIBUTE_BUILDING_SLOT,
		self::ATTRIBUTE_TRAIT_EXPIRE,
		self::ATTRIBUTE_FATHER_ID,
		self::ATTRIBUTE_MOTHER_ID,
		self::ATTRIBUTE_SPOUSE_ID,
	];

	protected static $exportableAttributes = [
		self::ATTRIBUTE_AGE => 'get',
		self::ATTRIBUTE_GENDER => 'getTranslatedGender',
		self::ATTRIBUTE_NAME => 'get',
		'id' => 'get',
		'location' => 'get',
		'building_id' => 'getBuildingId',
		self::ATTRIBUTE_BUILDING_SLOT => 'get'
	];

	protected static $itemLocationsList = [
		self::ITEM_LOCATION_HEAD,
		self::ITEM_LOCATION_BODY,
		self::ITEM_LOCATION_FEET,
		self::ITEM_LOCATION_HAND,
		self::ITEM_LOCATION_OFF_HAND,
	];

	public function init()
	{
		$this->extractAttributes();
	}

	public function birth()
	{
		$items = [];
		foreach (self::$itemLocationsList as $location) {
			$items[$location] = false;
		}
		$this->items = $items;
	}

	public function export()
	{
		$data = [
			'inventory' => $this->exportItems(),
			'traits' => $this->exportTraits(),
		];

		foreach (self::$exportableAttributes as $exportableAttribute => $method) {
			if ($method === 'get') {
				$data[$exportableAttribute] = $this->{$exportableAttribute};
			} else {
				$data[$exportableAttribute] = $this->$method($this->{$exportableAttribute});
			}
		}
		return $data;
	}

	/**
	 * @return TraitCollection
	 */
	public function getTraits()
	{
		if (!($this->traits instanceof TraitCollection)) {
			$this->initTraits();
		}
		return $this->traits;
	}

	protected function initTraits()
	{
		$this->traits = new TraitCollection(json_decode($this->traits, true));
	}

	protected function exportTraits()
	{
		return $this->getTraits()->toArray();
	}
	
	public function addTrait($name)
	{
		if ($opposite = WHTrait::getOpposite($name)) {
			if ($this->getTraits()->contains($opposite)) {
				$this->removeTrait($opposite);
				return;
			}
		}
		if ($this->getTraits()->contains($name)) {
			return;
		}
		if ($this->gender == self::GENDER_MALE && WHTrait::isType($name, WHTrait::TYPE_ONLY_FEMALE)) {
			return;
		} else if ($this->gender == self::GENDER_FEMALE && WHTrait::isType($name, WHTrait::TYPE_ONLY_MALE)) {
			return;
		}
		$this->getTraits()->push($name);
		$this->wasUpdated();
	}

	public function removeTrait($name)
	{
		$this->getTraits()->remove($name);
		$this->wasUpdated();
	}

	public function hasTrait($trait)
	{
		return $this->getTraits()->contains($trait);
	}

	public function hasOneOfTraits($traits)
	{
		$characterTraits = $this->getTraits();
		foreach ($traits as $trait) {
			if ($characterTraits->contains($trait)) return true;
		}
		return false;
	}

	public function set($attribute, $value)
	{
		if (!in_array($attribute, self::$attributesList)) return $this;
		if ($this->$attribute != $value) {
			$this->wasUpdated();
		}
		$this->$attribute = $value;
		return $this;
	}

	public function prepareToSave()
	{
		$this->compressAttributes();
		$this->compressItems();
		if ($this->traits instanceof TraitCollection) {
			$this->traits = json_encode($this->getTraits()->export());
		}
	}

	protected function compressItems()
	{
		if (is_array($this->items)) {
			$this->items = json_encode($this->items);
		}
	}

	public function initRandomAdult()
	{
		$this->age = rand(16, 30);
		$this->gender = ChanceHelper::oneFromArray([self::GENDER_MALE, self::GENDER_FEMALE]);
		$this->name = ucfirst(strtolower(str_random(6))); // TODO
		$this->wasUpdated();
	}

	public static function equipItemAction($data)
	{
		if (empty($data['character']) || empty($data['item']) || empty($data['location'])) {
			throw new WHErrorException('equipItem data is missing');
		}
		$character = WH::getCharacter($data['character']);
		$itemName = $data['item'];
		$character->addItemFromCurrentLocation($itemName, $data['location']);
	}

	public static function unequipItemAction($data)
	{
		if (empty($data['character']) || empty($data['item']) || empty($data['location'])) {
			throw new WHErrorException('unequipItem data is missing');
		}
		$character = WH::getCharacter($data['character']);
		$itemName = $data['item'];
		$character->giveItemToCurrentLocation($itemName, $data['location']);
	}

	public function unassignFromBuilding()
	{
		$this->building_id = null;	
		$this->{self::ATTRIBUTE_BUILDING_SLOT} = false;
		$this->wasUpdated();
	}

	public function assignToBuilding(WHBuilding $building, $slotName)
	{
		$this->building_id = $building->id;
		$this->{self::ATTRIBUTE_BUILDING_SLOT} = $slotName;
		$this->wasUpdated();
	}

	public function getItems()
	{
		if (is_array($this->items)) {
			return $this->items;
		}
		if ($this->items) {
			$this->items = json_decode($this->items, true);
		}
		if (!$this->items) {
			$this->items = [];
		}
		return $this->items;
	}

	protected function exportItems()
	{
		$items = $this->getItems();
		if (!$items) return [];
		$itemsConfig = \Config::get('whiteHorde.items.list');
		foreach ($items as $location => $itemCode) {
			if (!$itemCode) continue;
			if (empty($itemsConfig[$itemCode])) {
				throw new WHErrorException('Item ' . $itemCode . ' has no definition');
			}
			$item = $itemsConfig[$itemCode];
			$item['code'] = $itemCode;
			$item['q'] = 1;
			$items[$location] = $item;
		}
		return $items;
	}

	public function addItemFromCurrentLocation($itemName, $slotLocation)
	{
		$storage = '';
		switch ($this->location) {
			case self::LOCATION_SETTLEMENT:
				$settlement = WH::getOrCreateSettlement();
				if (!$settlement->hasItem($itemName)) {
					throw new WHErrorException('There is no item ' . $itemName . ' in settlement inventory');
				}
				$storage = $settlement;
				break;
		}
		$itemConfig = \Config::get('whiteHorde.items.list.' . $itemName);
		if (!$itemConfig) {
			throw new WHErrorException('There is no item ' . $itemName . ' found');
		}
		$items = $this->getItems();
		if (!empty($items[$slotLocation])) {
			throw new WHErrorException('Character id=' . $this->id . ' don\'t have a free slot for Item ' . $itemName);
		} else {
			$items[$slotLocation] = $itemName;
		}
		 $this->items = $items;
		
		$storage->removeItem($itemName);
		$this->wasUpdated();
	}

	public function giveItemToCurrentLocation($itemName, $slotLocation)
	{
		$storage = '';
		switch ($this->location) {
			case self::LOCATION_SETTLEMENT:
				$settlement = WH::getOrCreateSettlement();
				$storage = $settlement;
				break;
		}
		$itemConfig = \Config::get('whiteHorde.items.list.' . $itemName);
		if (!$itemConfig) {
			throw new WHErrorException('There is no item ' . $itemName . ' found');
		}
		$items = $this->getItems();
		if (empty($items[$slotLocation])) {
			throw new WHErrorException('Character id=' . $this->id . ' don\'t have this item in slot ' . $itemName);
		} else {
			$items[$slotLocation] = false;
		}
		 $this->items = $items;

		$storage->addItem($itemName);
		$this->wasUpdated();
	}

	protected function getTranslatedGender($gender) {
		switch ($gender) {
			case self::GENDER_MALE:
				return 'Male';
			case self::GENDER_FEMALE:
				return 'Female';
			default: return '';
		}
	}

	public function getBuildingId()
	{
		if ($this->building_id) return $this->building_id;
		return false;
	}

}
