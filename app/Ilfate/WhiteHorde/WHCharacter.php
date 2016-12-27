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
	const ATTRIBUTE_FATHER_ID = 'fatherId';
	const ATTRIBUTE_MOTHER_ID = 'motherId';
	const ATTRIBUTE_SPOUSE_ID = 'spouseId';
	
	const LOCATION_SETTLEMENT = 0;
	const LOCATION_RAID = 1;
	const LOCATION_MISSING = 2;

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
		self::ATTRIBUTE_FATHER_ID,
		self::ATTRIBUTE_MOTHER_ID,
		self::ATTRIBUTE_SPOUSE_ID,
	];

	protected static $exportableAttributes = [
		self::ATTRIBUTE_AGE,
		self::ATTRIBUTE_GENDER,
		self::ATTRIBUTE_NAME,
		'id',
		'location',
	];

	public function init()
	{
		$this->extractAttributes();
	}

	public function export()
	{
		$data = [
			'inventory' => $this->getItems(),
			'traits' => $this->exportTraits(),
		];

		foreach (self::$exportableAttributes as $exportableAttribute) {
			$data[$exportableAttribute] = $this->{$exportableAttribute};
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
		if ($this->traits instanceof TraitCollection) {
			$this->traits = json_encode($this->traits->toArray());
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
		return $this->items;
	}

}
