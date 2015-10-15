<?php

namespace Ilfate;

use Illuminate\Database\Eloquent\Model;

class Deck extends Model
{
	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'decks';

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = array();

    protected $appends = array();

    protected $guarded = array();

    public static function getMyDecksCount()
    {
        $player = User::getUser();
        if (!$player->id) {
            return false;
        }
        return self::where('player_id', '=', $player->id)->count();
    }

    public static function getMyDecks()
    {
        $player = User::getUser();
        if (!$player->id) {
            return false;
        }
        return self::where('player_id', '=', $player->id)->get();
    }

}
