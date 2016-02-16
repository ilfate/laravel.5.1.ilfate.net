<?php

namespace Ilfate;

use Illuminate\Database\Eloquent\Model;

class Mage extends Model
{

	const MAGE_STATUS_ACTIVE = 0;


	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'mages';

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = array();

    protected $appends = array();

    protected $guarded = array();

	public function isValidMageType($type)
	{
		$types = \Config::get('mageSurvival.mages-types');
		if (isset($types[$type])) {
			return true;
		}
		return false;
	}

	/**
	 * Get user.
	 */
	public function user()
	{
		return $this->hasOne('Ilfate\User', 'id', 'player_id');
	}

	/**
	 * Get user.
	 */
	public function world()
	{
		return $this->hasOne('Ilfate\MageWorld', 'id', 'world_id');
	}

//    public static function getMyDecksCount()
//    {
//        $player = User::getUser();
//        if (!$player->id) {
//            return false;
//        }
//        return self::where('player_id', '=', $player->id)->count();
//    }
//
//    public static function getMyDecks()
//    {
//        $player = User::getUser();
//        if (!$player->id) {
//            return false;
//        }
//        return self::where('player_id', '=', $player->id)->get();
//    }

}
