<?php

namespace Ilfate\Http\Controllers;

use Ilfate\Mage;
use Ilfate\MageSurvival\AliveCommon;
use Ilfate\MageSurvival\ChanceHelper;
use Ilfate\MageSurvival\Game;
use Ilfate\MageSurvival\GameBuilder;
use Ilfate\MageSurvival\MessageException;
use Ilfate\MageSurvival\Generators\LocationsForest;
use Ilfate\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Cache;
use Illuminate\Support\Facades\DB;

class TdController extends BaseController
{
    const PAGE_NAME = 'td';

    const SOME_STRING = 'dfgdfgwkef';
    const SOME_STRING2 = 'sdfwe23f';

    const CACHE_USER_LOGGING = 'ms-user-is-logged-';
    const CACHE_USER_LOGGING_INDEX = 'ms-user-log-index-';
    const CACHE_USER_LOGGING_DATA = 'ms-user-logged-data-';
    const CACHE_ENABLED_MINUTES = 60;
    const CACHE_SAVED_MINUTES = 540;

    protected $isLogged = false;



    /**
     * @param Mage $mageModel
     */
    public function __construct(Mage $mageModel)
    {
        $this->mageModel = $mageModel;
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
        
        view()->share('page_title', 'TD');
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
        $skipTurns = 12;
        $fast = false;
        $HP = ceil(($number * $number / 5) - 30);
        $HP = max($HP, 6);
        $reward = ceil($number / 2);
        $color = '#FF8360';
        $min = 3;
        $max = 3;
        $turns = 3;
        if (rand(1, 4) == 4) {
            $HP = ceil(1.6 * $HP);
            $color = '#711F1F';
            $reward *= 6;
            $min = 1;
            $max = 1;
            $turns = 1;
            $name = 'Boss ' . $HP . 'HP';
        } else if (rand(1, 3) == 3) {
            $fast = true;
            $name = 'Fast ' . $HP . 'HP';
        } else if (rand(1, 2) == 2) {
            $diagonal = true;
            $name = 'Diagonal ' . $HP . 'HP';
        } else {
            $name = $HP . 'HP';
            $min = 4;
            $max = 5;
            $turns = 5;
            $skipTurns = 25;
            $HP = ceil(1.1 * $HP);
            $reward = ceil($reward / 2);
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
