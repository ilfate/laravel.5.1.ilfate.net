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
            view()->share('localDevelopment', false);
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
            $wave = \Config::get('td.waves.' . $number);
            if (!$wave) { break; }
            foreach ($wave['types'] as $type) {
                $monsters[$type] = \Config::get('td.monsters.' . $type);
            }
            if (!empty($wave['newTower'])) {
                $towers[$wave['newTower']] =  \Config::get('td.towers.' . $wave['newTower']);
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



    

}
