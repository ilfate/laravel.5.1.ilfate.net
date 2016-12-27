<?php

namespace Ilfate\WhiteHorde;

use Ilfate\WhiteHorde;

class WHBuilding extends WhiteHorde
{
	const TYPE_WARHOUSE = 'warhouse';

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'wh_buildings';

	protected $saveable = ['id', 'settlement_id', 'type', 'data'];

	protected static $attributesList = [
		'characterIds',
	];

	protected static $exportableAttributes = [
		'characterIds',
	];


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
		return $data;
	}

	public function prepareToSave()
	{
		$this->compressAttributes();
	}

	/**
	 * @param $type
	 *
	 * @return WHBuilding
	 */
	public static function make($type)
	{
		$class = '\Ilfate\WhiteHorde\Buildings\\' . ucfirst($type);
		$building =  new $class();
		$building->type = $type;
		return $building;
	}
	
}
