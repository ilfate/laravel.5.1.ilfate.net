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
			'inventory' => $this->getItems(),
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
		$key = $this->getTraits()->search($name);
		$this->getTraits()->pull($key);
		$this->wasUpdated();
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
			$this->traits = json_encode($this->traits->toArray());
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

	public function getItems()
	{
		if (is_array($this->items)) {
			return $this->items;
		}
		if ($this->items) {
			$this->items = json_decode($this->items, true);
		}
		if (!$this->items) {
			return [];
		}
		return $this->items;
	}

	public function addItem()
	{
//		$export = [];
//		$items = $this->getItems();
//		if (!$items) return [];
//		$itemsConfig = \Config::get('whiteHorde.items.list');
//		foreach ($items as $item) {
//			$itemConfig = $itemsConfig[$item];
//			$itemConfig['q'] = 1;
//			$itemConfig['code'] = $item;
//			if (empty($export[$itemConfig['location']])) {
//				$export[$itemConfig['location']] = $itemConfig;
//			} else {
//				if ($itemConfig['location'] == self::ITEM_LOCATION_HAND
//					&& !empty($itemConfig['offLocation']) && $itemConfig['offLocation'] == self::ITEM_LOCATION_OFF_HAND
//					&& empty($export[self::ITEM_LOCATION_OFF_HAND])) {
//					$export[self::ITEM_LOCATION_OFF_HAND] = $itemConfig;
//				}
//			}
//		}
//		return $export;
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

}
