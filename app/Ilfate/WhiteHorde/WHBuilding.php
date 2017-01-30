<?php

namespace Ilfate\WhiteHorde;

use Ilfate\WhiteHorde;

class WHBuilding extends WhiteHorde
{
	const TYPE_WARHOUSE = 'warhouse';
	const TYPE_SMITHY = 'smithy';
	
	const REQUIREMENT_TYPE_TRAITS = 'traits';
	const REQUIREMENT_TYPE_AGE = 'age';

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'wh_buildings';
	/**
	 * @var WHBuilding
	 */
	protected $originalObject;

	protected $saveable = ['id', 'settlement_id', 'type', 'data'];

	protected $fillable = ['id', 'settlement_id', 'type', 'data'];

	protected static $attributesList = [
		'characterIds',
	];

	protected static $exportableAttributes = [
		'id'
	];

	protected static $slots = [];
	protected static $slotsIncome = [];
	protected static $slotsRequirements = [];


	public function init()
	{
		$this->extractAttributes();
	}

	public function export()
	{
		$data = [
			'type' => $this->type,
		];
		foreach (self::$exportableAttributes as $exportableAttribute) {
			$data[$exportableAttribute] = $this->{$exportableAttribute};
		}
		//$buildingConfig = \Config::get('whiteHorde.buildings.list.' . $this->type);
		$slots = [];
		if (static::$slots) {
			$characters = $this->characterIds;
			foreach (static::$slots as $slot) {
				$slots[] = [
					'name' => $slot,
					'characterId' => empty($characters[$slot]) ? false : $characters[$slot]
				];
			}
		}
		$data['slots'] = $slots ? $slots : false;
		return $data;
	}

	public static function assignCharacter($data)
	{
		if (empty($data['character']) || empty($data['building']) || empty($data['slot'])) {
			throw new WHErrorException('assignCharacter data is missing');
		}
		$character = WH::getCharacter($data['character']);
		if ($character->building_id) {
			$oldBuilding = WH::getBuilding($character->building_id);
			$oldBuilding->removeFromSlot($character->{WHCharacter::ATTRIBUTE_BUILDING_SLOT});
		}
		$building = WH::getBuilding($data['building']);
		$building->addToSlot($character, $data['slot']);
	}

	public static function unassignCharacter($data)
	{
		if (empty($data['character'])) {
			throw new WHErrorException('unassignCharacter data is missing');
		}
		$character = WH::getCharacter($data['character']);
		$building = WH::getBuilding($character->building_id);
		$building->removeFromSlot($character->{WHCharacter::ATTRIBUTE_BUILDING_SLOT});
	}

	public function prepareToSave()
	{
		$this->compressAttributes();
		$this->preparedToSave = true;
	}
	
	public function addToSlot(WHCharacter $character, $slot)
	{
		if (!$this->characterIds) {
			$this->characterIds = [];
		}
		if (!in_array($slot, static::$slots)) {
			throw new WHErrorException('Building ' . $this->type . ' doesn\'t have slot ' . $slot);
		}
		$this->validateAddToSlot($character, $slot);
		if (!empty($this->characterIds[$slot])) {
			$this->removeFromSlot($slot);
		}
		$characterIds = $this->characterIds;
		$characterIds[$slot] = $character->id;
		$this->characterIds = $characterIds;
		$character->assignToBuilding($this, $slot);
		if (static::$slotsIncome && !empty(static::$slotsIncome[$slot])) {
			foreach (static::$slotsIncome[$slot] as $incomeConfig) {
				$settlement = WhiteHorde\WH::getOrCreateSettlement();
				$settlement->income($incomeConfig[0], $incomeConfig[1]);
			}
		}
		$this->wasUpdated();
	}

	public function removeFromSlot($slot)
	{
		if (!empty($this->characterIds[$slot])) {
			$characterIds = $this->characterIds;
			$characterId = $characterIds[$slot];
			$characterIds[$slot] = false;
			$this->characterIds = $characterIds;
			$this->wasUpdated();

			$character = WH::getCharacter($characterId);
			$character->unassignFromBuilding();
			if (static::$slotsIncome && !empty(static::$slotsIncome[$slot])) {
				foreach (static::$slotsIncome[$slot] as $incomeConfig) {
					$settlement = WhiteHorde\WH::getOrCreateSettlement();
					$settlement->income($incomeConfig[0], -$incomeConfig[1]);
				}
			}
		}
	}
	
	protected function validateAddToSlot(WHCharacter $character, $slot)
	{
		if (empty(static::$slotsRequirements[$slot])) return true;
		$requirements = static::$slotsRequirements[$slot];
		foreach ($requirements as $type => $requirement) {
			switch ($type) {
				case self::REQUIREMENT_TYPE_TRAITS:
					if (!$character->hasOneOfTraits($requirement)) {
						WH::addAction('interface.emptyBuildingSlot', ['buildingId' => $this->id, 'slot' => $slot]);
						throw new WHMessageException(
							sprintf('To work here character must have at least one of the following traits: %s.', implode(', ', $requirement))
						);
					}
					break;
				case self::REQUIREMENT_TYPE_AGE:
					if ($character->{WHCharacter::ATTRIBUTE_AGE} < $requirement['min'] 
						|| $character->{WHCharacter::ATTRIBUTE_AGE} > $requirement['max']) {
						WH::addAction('interface.emptyBuildingSlot', ['buildingId' => $this->id, 'slot' => $slot]);
						throw new WHMessageException(
							sprintf('To work here character must be between %s and %s years old.', $requirement['min'], $requirement['max'])
						);
					}
					break;
			}
		}
		
		return true;
	}

	public function save(array $options = [])
	{
		if (static::class !== WHBuilding::class) {
			$this->prepareToSave();
			$this->originalObject->__construct([
				'id' => $this->id,
				'settlement_id' => $this->settlement_id,
				'type' => $this->type,
				'data' => $this->data,
			]);
			$this->originalObject->preparedToSave = true;
			return $this->originalObject->save();
		}
		return parent::save($options); // TODO: Change the autogenerated stub
	}

	/**
	 * @param $type
	 *
	 * @return WHBuilding
	 */
	public function make()
	{
		$class = '\Ilfate\WhiteHorde\Buildings\\' . ucfirst($this->type);
//		dd(class_exists($class));
		$building =  new $class([
			'id' => $this->id,
			'settlement_id' => $this->settlement_id,
			'type' => $this->type,
			'data' => $this->data,
		]);
		$building->init();
		$building->setOriginalObject($this);
		return $building;
	}

	/**
	 * @param WHBuilding $originalObject
	 */
	public function setOriginalObject($originalObject)
	{
		$this->originalObject = $originalObject;
	}

}
