<?php

namespace Ilfate;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Session;

class DeckCard extends Model
{
	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'deck_cards';

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = array();

    protected $appends = array();

    protected $guarded = array();

    public static function getByDeck($deckId)
    {
        return self::where('deck_id', '=', $deckId)->get();
    }

}
