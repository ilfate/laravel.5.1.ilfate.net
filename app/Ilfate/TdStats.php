<?php

namespace Ilfate;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class TdStats extends Model
{

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'td_stats';

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = array();

	/**
	 * @return mixed
	 */
	public function getTopLogs()
	{
		$topLogs = DB::table('td_stats')
			->select(DB::raw('name, ip, waves'))
			->orderBy('waves', 'desc')
			->limit(10)
			->get();
		return $topLogs;
	}

	/**
	 * @return mixed
	 */
	public function getTotalGames()
	{
		$totalGames = DB::table('td_stats')
			->count();
		return $totalGames;
	}

	/**
	 * @return mixed
	 */
	public function getAverageWaves()
	{
		$avrTurns = DB::table('td_stats')
			->avg('waves');
		return $avrTurns;
	}

	/**
	 * @return mixed
	 */
	public function getPlayersNumber()
	{
		$users = DB::table('td_stats')
			->select(DB::raw('count(DISTINCT CONCAT(COALESCE(name,\'empty\'),ip)) as count'))
			->pluck('count');
		return $users;
	}

	/**
	 * @return mixed
	 */
	public function getTodayLogs()
	{
		$from = Carbon::now()->addHours(-24)->format('Y-m-d H:i:s');
		$to = Carbon::now()->addHours(2)->format('Y-m-d H:i:s');
		$todayLogs = DB::table('td_stats')
			->select(DB::raw('name, ip, waves'))
			->orderBy('waves', 'desc')
			->limit(10)
			->whereBetween('created_at', [$from, $to])
			->get();
		return $todayLogs;
	}

	/**
	 * @param $name
	 *
	 * @return mixed
	 */
	public function getUserStatsByName($name)
	{
		$userLogs = DB::table('td_stats')
			->select(DB::raw('name, ip, waves'))
			->where('name', '=', $name)
			->orderBy('waves', 'desc')
			->limit(10)
			->get();
		return $userLogs;
	}

	public static function getMyStandingForToday($waves)
	{
		$from = Carbon::now()->addHours(-24)->format('Y-m-d H:i:s');
		$number = DB::table('td_stats')
			->select(DB::raw('count(1) as num'))
			->where('created_at', '>', $from)
			->where('waves', '>', $waves)
			->value('num');
		return $number;
	}

}
