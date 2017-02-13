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
use Ilfate\ShipAi\Generator;
use Ilfate\ShipAi\Hex;
use Ilfate\ShipAi\Star;
use Ilfate\TdStats;
use Ilfate\User;
use Ilfate\WhiteHorde\WH;
use Ilfate\WhiteHorde\WHBuilding;
use Ilfate\WhiteHorde\WHErrorException;
use Ilfate\WhiteHorde\WHMessageException;
use Ilfate\WhiteHorde\WHTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Cache;
use Illuminate\Support\Facades\DB;

class ShipAiController extends BaseController
{
    const PAGE_NAME = 'ShipAi';


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
        view()->share('page_title', 'ShipAi');
        view()->share('bodyClass', 'shipAi');
        view()->share('mobileFriendly', true);
        if (env('APP_DEBUG') === true) {
            view()->share('localDevelopment', true);
        }
//        $game = new \Ilfate\WhiteHorde\Game();
//        $data = $game->loadMainScreen();
//
        view()->share('viewData', '');



        $hexMap = Hex::getHexMap(1);
        view()->share('hexMap', $hexMap);

        

        return view('games.shipAi.index');
    }

    public function galaxy()
    {
        \MetaTagsHelper::setPageName(self::PAGE_NAME);

        view()->share('page_title', 'Galaxy view');
        view()->share('bodyClass', 'shipAi galaxy');
        view()->share('mobileFriendly', true);
        if (env('APP_DEBUG') === true) {
            view()->share('localDevelopment', true);
        }
        $hexMap = Hex::getHexMap(1);
        view()->share('hexMap', $hexMap);
        return view('games.shipAi.galaxy');
    }

    public function hex($id)
    {
        \MetaTagsHelper::setPageName(self::PAGE_NAME);

        view()->share('page_title', 'Hex view');
        view()->share('bodyClass', 'shipAi hex');
        view()->share('mobileFriendly', true);
        if (env('APP_DEBUG') === true) {
            view()->share('localDevelopment', true);
        }
        $hex = Hex::loadFull($id);
        view()->share('hex', $hex);
        view()->share('width', $hex->getHexWidth(Hex::SIDE_SIZE_LIGHT_YEARS));
        return view('games.shipAi.hex');
    }

    public function star($id)
    {
        \MetaTagsHelper::setPageName(self::PAGE_NAME);

        view()->share('page_title', 'Star view');
        view()->share('bodyClass', 'shipAi star');
        view()->share('mobileFriendly', true);
        if (env('APP_DEBUG') === true) {
            view()->share('localDevelopment', true);
        }
        $star = Star::findOrFail($id);
//        $hex = Hex::loadFull($id);
        view()->share('star', $star);
//        view()->share('width', $hex->getHexWidth(Hex::SIDE_SIZE_LIGHT_YEARS));
        return view('games.shipAi.star');
    }

    
    public function action(Request $request)
    {
        

        $action = $request->get('action');
        $data = $request->get('data');
        
        try {
            $game = new \Ilfate\WhiteHorde\Game();
            $result = $game->action($action, json_decode($data, true));
//            $result = WH::action($action, json_decode($data, true));
            WH::endExecution();
        } catch (WHErrorException $e) {
            $result = ['action' => 'error-message',
                       'game' => [
                           'error' => $e->getMessage()
                       ]
            ];
        } catch (WHMessageException $e) {
            $result = ['action' => 'error-message',
                       'game' => [
                           'message' => $e->getMessage()
                       ]
            ];
        } catch (\Exception $e) {
            \Log::critical('Error in White Horde. message:' . $e->getMessage());
            $result = ['action' => 'error-message',
                       'game' => [
                           'error' => 'internal error'
                       ]
            ];
        } catch (\Throwable $e) {
            \Log::critical('Error in White Horde. message:' . $e->getMessage());
            $result = ['action' => 'error-message',
                       'game' => [
                           'error' => 'internal error'
                       ]
            ];
        }
        if($actions = WH::getActions()) {
            $result['game']['actions'] = $actions;
        }
//        if ($this->isLoggingEnabled($game->getUser())) {
//            $this->logAction(['result' => $result, 'action' => $action, 'data' => $data], $game->getUser());
//        }
        return json_encode($result);
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



        WH::addBuilding(WHBuilding::TYPE_SMITHY);
        
        //WH::addCharacter()->initRandomAdult();
//        $characters = WH::getAllCharacters();
//        $character = WH::getCharacter(10);
//        $character->addTrait(WHTrait::getRandomFromType(WHTrait::TYPE_NEGATIVE));

//        foreach ($characters as $character) {
//            $character->addTrait(WHTrait::getRandomFromType(WHTrait::TYPE_NEGATIVE));
//        }

//        $s = WH::getOrCreateSettlement();
//        $items = \Config::get('whiteHorde.items.list');
//        foreach ($items as $name => $item) {
//
//            $s->addItem($name, 5);
//        }
        WH::endExecution();

        return redirect('/WhiteHorde');
    }
}
