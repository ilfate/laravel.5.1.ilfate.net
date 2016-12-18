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

class WhiteHordeController extends BaseController
{
    const PAGE_NAME = 'WhiteHorde';


    /**
     * Display a listing of the games.
     *
     * @param Request $request
     *
     * @return \Illuminate\View\View
     */
    public function demo(Request $request)
    {
        //echo 'awd'; die;
        \MetaTagsHelper::setPageName(self::PAGE_NAME);

        $name = $request->session()->get('userName', null);
        
        view()->share('userName', $name);
        view()->share('page_title', 'White Horde');
        view()->share('bodyClass', 'WhiteHorde');
        view()->share('mobileFriendly', true);
        if (env('APP_DEBUG') === true) {
            view()->share('localDevelopment', true);
        }

        return view('games.whiteHorde.demo');
    }
    

}
