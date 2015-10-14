<?php

namespace Ilfate;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Session;

class Series extends Model
{
	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'series';

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = array();

    protected $appends = array();

    protected $guarded = array();

    public static function getRandomSeries($difficulty = false, $exclude = array())
    {
        $query = self::where('active', '=', 1);
        if ($difficulty) {
        	$query = $query->where('difficulty', '=', $difficulty);
        }
        if ($exclude) {
            $query = $query->whereNotIn('id', $exclude);
        }
        $series = $query->orderByRaw("RAND()")->first();
        return $series;
    }

}
