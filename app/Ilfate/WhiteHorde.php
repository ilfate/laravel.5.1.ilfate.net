<?php

namespace Ilfate;

use Illuminate\Database\Eloquent\Model;

abstract class WhiteHorde extends Model
{

	protected static $attributesList = [];
	protected $saveable = [];
	protected $wasChanged = false;
	protected $preparedToSave = false;

	public function extractAttributes()
	{
		if ($this->data) {
			$decoded = json_decode($this->data, true);
			foreach ($decoded as $key => $value) {
				$this->{$key} = $value;
			}
		}
	}

	public function compressAttributes()
	{
		if (!static::$attributesList) return;
		$data = [];
		foreach (static::$attributesList as $attribute) {
			if ($this->{$attribute}) {
				$data[$attribute] = $this->{$attribute};
			}
		}
		$this->data = json_encode($data);
	}

	protected static function boot() {
		parent::boot();

		static::saving(function($model) {
			if (count($model->saveable) > 0) {
				$model->attributes = array_intersect_key($model->attributes, array_flip($model->saveable));
			}
    	});
	}

	abstract public function prepareToSave();
	abstract public function init();

	public function save(array $options = [])
	{
		if (!$this->preparedToSave) {
			$this->prepareToSave();
		}
		return parent::save($options); // TODO: Change the autogenerated stub
	}

	public function wasUpdated() {
		$this->wasChanged = true;
	}

	public function isUpdated()
	{
		return $this->wasChanged;
	}
}
