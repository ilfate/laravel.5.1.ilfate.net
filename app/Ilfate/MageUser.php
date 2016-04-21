<?php

namespace Ilfate;

use Illuminate\Database\Eloquent\Model;

class MageUser extends Model
{

	const MAGE_STATUS_ACTIVE = 0;


	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'mage_users';

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = array();

    protected $appends = array();

    protected $guarded = array();

	/**
	 * Get user.
	 */
	public function user()
	{
		return $this->hasOne('Ilfate\User', 'id', 'user_id');
	}
}
