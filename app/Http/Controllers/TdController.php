<?php

namespace Ilfate\Http\Controllers;

use Ilfate\Mage;
use Ilfate\MageSurvival\AliveCommon;
use Ilfate\MageSurvival\ChanceHelper;
use Ilfate\MageSurvival\Game;
use Ilfate\MageSurvival\GameBuilder;
use Ilfate\MageSurvival\MessageException;
use Ilfate\MageSurvival\Generators\LocationsForest;
use Ilfate\TdStats;
use Ilfate\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Cache;
use Illuminate\Support\Facades\DB;

class TdController extends BaseController
{
    const PAGE_NAME = 'td';

    const CACHE_KEY_STATS_TOTAL = 'TD_stats';

    const SOME_STRING = 'dfgdfgwkef';
    const SOME_STRING2 = 'sdfwe23f';

    const CACHE_USER_LOGGING = 'ms-user-is-logged-';
    const CACHE_USER_LOGGING_INDEX = 'ms-user-log-index-';
    const CACHE_USER_LOGGING_DATA = 'ms-user-logged-data-';
    const CACHE_ENABLED_MINUTES = 60;
    const CACHE_SAVED_MINUTES = 540;

    protected $isLogged = false;
    /**
     * @var TdStats
     */
    protected $tdStats;

    /**
     * @param TdStats $tdStats
     */
    public function __construct(TdStats $tdStats)
    {
        $this->tdStats = $tdStats;
    }

    /**
     * Display a listing of the games.
     *
     * @param Request $request
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        //echo 'awd'; die;
        \MetaTagsHelper::setPageName(self::PAGE_NAME);

        $name = $request->session()->get('userName', null);
        
        view()->share('userName', $name);
        view()->share('page_title', 'TD');
        view()->share('restart', $request->get('restart', false));
        //view()->share('facebookEnabled', true);
        view()->share('bodyClass', 'td');
        view()->share('mobileFriendly', false);
        if (env('APP_DEBUG') === true) {
            view()->share('localDevelopment', true);
        }
        
//        view()->share('viewData', $data);

        return view('games.td.index');
    }
    
    /**
     * js game template
     *
     * @param Request $request
     *
     * @return string
     */
    public function saveStats(Request $request)
    {
        if (!$request->ajax()) {
            Log::warning('TD save is not Ajax.');
            abort(404);
        }
        $name     = $request->session()->get('userName', null);

        $check = $request->get('check');
        $waves = $request->get('wave');
        if (((($waves + 77) * 3) - 22) != $check) {
            Log::warning('TD save Check not passed.');
            return '{}';
        }

        $tdStatistics                  = new TdStats();
        $tdStatistics->waves           = $waves;
        $tdStatistics->ip              = $_SERVER['REMOTE_ADDR'];
        $tdStatistics->laravel_session = md5($request->cookie('laravel_session'));
        $tdStatistics->name            = $name;

        $tdStatistics->save();

        $stats = TdStats::getMyStandingForToday($waves);
        
        return json_encode(['stats' => $stats + 1]);
    }

    /**
     * @param Request $request
     *
     * @return string
     */
    public function saveName(Request $request)
    {
        $name            = $request->get('name');
        $laravel_session = md5($request->cookie('laravel_session'));

        $request->session()->put('userName', $name);


        $stats = TdStats::where('laravel_session', '=', $laravel_session)->orderBy('created_at', 'desc')->firstOrFail();
        if (!$stats) {
            Log::warning('No user found to update name. (name=' . $name . ')');
            abort(404);
        }
        $stats->name = $name;
        $stats->save();
        return '{"actions": ["$(\'#TDNameForm\').html(\'Your name is saved\')"]}';
    }

    /**
     * @param Request $request
     *
     * @return string
     */
    public function getStats(Request $request)
    {
        $name            = $request->get('name');
        $laravel_session = md5($request->cookie('laravel_session'));

        $cachedStats = Cache::get(self::CACHE_KEY_STATS_TOTAL, null);

        if (!$cachedStats) {
            $topLogs    = $this->tdStats->getTopLogs();
            $totalGames = $this->tdStats->getTotalGames();
            $avrTurns   = $this->tdStats->getAverageWaves();
            $users      = $this->tdStats->getPlayersNumber();
            $todayLogs  = $this->tdStats->getTodayLogs();
            $expiresAt  = Carbon::now()->addMinutes(4);
            $cachedStats = [
                'topLogs'    => $topLogs,
                'totalGames' => $totalGames,
                'avrTurns'   => $avrTurns,
                'users'      => $users,
                'todayLogs'  => $todayLogs
            ];
            Cache::put(self::CACHE_KEY_STATS_TOTAL, $cachedStats, $expiresAt);
        }

        return json_encode([
            'stats' => $cachedStats
        ]);
    }
    
    /**
     * loadWave
     *
     * @param Request $request
     *
     * @return \Illuminate\View\View
     */
    public function loadWave(Request $request)
    {
        $number           = $request->get('number');
        $additionalToLoad = 1;//rand(0, 3);
        $waves = [];
        $monsters = [];
        $towers = [];
        for ($i = $number; $i <= $number + $additionalToLoad; $i++) {
            $wave = \Config::get('td.waves.' . $i);
            if (!$wave) {
                $this->generateWave($i, $waves, $monsters, $towers);
                continue;
            }
            foreach ($wave['types'] as $type) {
                $monsters[$type] = \Config::get('td.monsters.' . $type);
            }
            if (!empty($wave['newTower'])) {
                if (is_array($wave['newTower'])) {
                    $towerName = $wave['newTower'][array_rand($wave['newTower'])];
                    $wave['newTower'] = $towerName;
                } else {
                    $towerName = $wave['newTower'];
                }
                $towers[$towerName] = \Config::get('td.towers.' . $towerName);
            }
            $waves[$i] = $wave;

        }
        if (!$waves) {
            $result = ['error' => 'no wave',
            ];
        } else {
            $result = ['waves' => $waves, 'monsters' => $monsters];
            if ($towers) {
                $result['towers'] = $towers;
            }
        }


        return json_encode($result);
    }

    protected function generateWave($number, &$waves, &$monsters, &$towers)
    {
        $newTowerArray = \Config::get('td.towersAccess' . $number);
        if ($newTowerArray) {
            $newTowerName = $newTowerArray[array_rand($newTowerArray)];
            $towers[$newTowerName] = \Config::get('td.towers.' . $newTowerName);
        }
        $skipTurns = 12;
        $fast = false;
        $HP = ceil(($number * $number / 10) - $number);
        $HP = max($HP, 6);
        $reward = ceil($number / 2);
        $color = '#FF8360';
        $min = 3;
        $max = 3;
        $turns = 3;
        switch($number % 4) {
            case 0:
                $name = $HP . 'HP';
                $min = 4;
                $max = 5;
                $turns = 5;
                $skipTurns = 25;
                $HP = ceil(1.1 * $HP);
                $reward = ceil($reward / 2);
                break;
            case 1:
                $diagonal = true;
                $name = 'Diagonal ' . $HP . 'HP';
                break;
            case 2:
                $fast = true;
                $name = 'Fast ' . $HP . 'HP';
                break;
            case 3:
                $HP = ceil(1.6 * $HP);
                $color = '#711F1F';
                $reward *= 6;
                $min = 1;
                $max = 1;
                $turns = 1;
                $name = 'Boss ' . $HP . 'HP';
                break;
        }

        $wave = [
            'name' => $name,
            'min' => $min,
            'max' => $max,
            'types' => ['g' . $number],
            'turns' => $turns,
            'skipTurns' => $skipTurns
        ];
        $monster = [
            'health'=> $HP,
            'moneyAward'=> $reward,
            'color'=> $color,
        ];
        if (!empty($newTower)) {
            $wave['newTower'] = $newTower;
        }
        if (!empty($fast)) {
            $monster['fast'] = true;
        }
        if (!empty($diagonal)) {
            $monster['diagonal'] = true;
        }
        $waves[$number] = $wave;
        $monsters['g' . $number] = $monster;
    }

    

}
