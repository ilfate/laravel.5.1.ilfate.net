<?php

namespace Ilfate\Http\Controllers;

use Ilfate\Mage;
use Ilfate\MageSurvival\AliveCommon;
use Ilfate\MageSurvival\ChanceHelper;
use Ilfate\MageSurvival\Game;
use Ilfate\MageSurvival\GameBuilder;
use Ilfate\MageSurvival\MessageException;
use Ilfate\MageSurvival\Generators\LocationsForest;
use Ilfate\Settlement;
use Ilfate\TdStats;
use Ilfate\User;
use Ilfate\WhiteHorde\WH;
use Ilfate\WhiteHorde\WHBuilding;
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
    public function index(Request $request)
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
        $game = new \Ilfate\WhiteHorde\Game();
        $data = $game->loadMainScreen();

        view()->share('viewData', $data);

        return view('games.whiteHorde.index');
    }

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
        view()->share('bodyClass', 'WhiteHorde demo');
        view()->share('mobileFriendly', true);
        if (env('APP_DEBUG') === true) {
            view()->share('localDevelopment', true);
        }

        return view('games.whiteHorde.demo');
    }
    

    public function test()
    {
//        $settlement = new Settlement();
//        $settlement->age = 3;
//        $settlement->compressAttributes();
//        $settlement->save();
//        $settlement = Settlement::findOrFail(1);
//        $settlement->extractAttributes();
        $settlement = WH::getOrCreateSettlement();
//        dd($settlement);

        echo "\n END";
    }

    public function fake()
    {



//        WH::addBuilding(WHBuilding::TYPE_WARHOUSE);
        
//        WH::addCharacter()->initRandomAdult();
//        WH::endExecution();
        
        
        return redirect('/WhiteHorde');
    }
}
