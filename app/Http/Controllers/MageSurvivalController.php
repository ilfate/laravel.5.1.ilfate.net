<?php

namespace Ilfate\Http\Controllers;

use Ilfate\Mage;
use Ilfate\MageSurvival\Game;
use Ilfate\MageSurvival\GameBuilder;
use Ilfate\MageSurvival\MessageException;
use Ilfate\MageSurvival\Generators\LocationsForest;
use Ilfate\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Cache;

class MageSurvivalController extends BaseController
{
    const PAGE_NAME = 'mageSurvival';

    const ACTION_MAGE_CREATE = 'mage-create';

    /**
     * @var Mage
     */
    protected $mageModel;

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

        view()->share('page_title', 'Spellcraft - turn based magic crafting Rogue like RPG game!');
        //view()->share('facebookEnabled', true);
        view()->share('bodyClass', 'mage-survival');

        $game = $this->getGame($request);

        $viewFileName = $game->getViewName();
        $data = $this->getDataForView($viewFileName, $game);

        view()->share('viewData', $data);

        //return view('games.mageSurvival.index');
        return view($viewFileName);
    }

    /**
     * @param Request $request
     */
    public function createMage(Request $request)
    {
        $user = User::getUser();
        $mage = new Mage();
        $mageType = strip_tags(htmlentities($request->get('type')));
        $mage->class = $mageType;
        $mage->player_id = $user->getId();
        $mage->name = strip_tags(htmlentities($request->get('name')));

        $result = $mage->save();

        return [
            'action'=> self::ACTION_MAGE_CREATE,
            'status' => $result
        ];
    }

    public function action(Request $request)
    {
        $game = $this->getGame($request);

        $action = $request->get('action');
        $data = $request->get('data');
        try {
            $result = $game->action($action, json_decode($data, true));
        } catch (MessageException $e) {
            $result = ['action' => 'error-message',
                       'game' => [
                           'messages' => [
                                ['message' => $e->getMessage()]
                            ]
                       ]
            ];
        }
        return json_encode($result);
    }

    public function addAllSpells(Request $request)
    {
        if (env('APP_DEBUG') !== true) {
//            return redirect('/Spellcraft');
        }
        $game = $this->getGame($request);

        $game->addAllSpells();
        return redirect('/Spellcraft');
    }

    public function world($name, Request $request)
    {
        $game = $this->getGame($request);
        $game->setWorldType($name);
        return redirect('/Spellcraft');
    }

    public function redirect()
    {
        return redirect('/Spellcraft');
    }

    public function mapBuilder($name,Request $request)
    {
        $x = $request->get('x', 0);
        $y = $request->get('y', 0);
        $value = $request->session()->get('mapBuilder.' . $name);
        if ($value) {
            view()->share('mapValue', json_encode($value));
        }
        view()->share('bodyClass', 'mage-map-builder');
        view()->share('mapName', $name);
        view()->share('mapName2', 'WitchForest');
        view()->share('offsetX', $x);
        view()->share('offsetY', $y);
        return view('games.mageSurvival.map-builder');
    }
    public function saveMapName(Request $request)
    {
        $name = $request->get('name');
        $map = $request->get('map');
        $map = str_replace(' ', '+', $map);
        $newMapPart = json_decode($map, true);
        $oldFullMap = $request->session()->get('mapBuilder.' . $name);
        foreach ($newMapPart as $y => $row) {
            foreach ($row as $x => $cell) {
                $oldFullMap[$y][$x] = $cell;
            }
        }
        $request->session()->set('mapBuilder.' . $name, $oldFullMap);
//        return redirect('/Spellcraft/mapBuilder/show/' . $name);
        return '{}';
    }
    public function showMap($name, Request $request)
    {
        $value = $request->session()->get('mapBuilder.'. $name);
        return var_export($value);
    }

    public function editMap($name, Request $request)
    {
        $worlds = \Config::get('mageSurvival.worlds');
        $type = 0;
        foreach ($worlds as $typeId => $world) {
            if ($world['map-type'] == $name) {
                $type = $typeId;
            }
        }
        if (!$type) {
            if (!empty(LocationsForest::${$name})) {
                $map = LocationsForest::${$name};
                $request->session()->set('mapBuilder.'. $name, $map);
                return redirect('/Spellcraft/mapBuilder/' . $name);
            }
            return redirect('/Spellcraft/mapBuilder/empty');
        }
        $className = '\Ilfate\MageSurvival\Generators\WorldGenerator' . $worlds[$type]['map-type'];
        $mapConfig = $className::getGeneratorConfig();
        if (empty($mapConfig['full-world'])) {
            return redirect('/Spellcraft/mapBuilder/empty');
        }
        $request->session()->set('mapBuilder.'. $name, $mapConfig['full-world']);
        return redirect('/Spellcraft/mapBuilder/' . $name);
    }

    protected function getDataForView($view, Game $game)
    {
        $data = [];
        switch($view) {
            case 'games.mageSurvival.mage-list':
                $data['mages-types'] = \Config::get('mageSurvival.mages-types');
                $data['mages'] = $game->getInactiveMages();
                break;
            case 'games.mageSurvival.mage-home':
                $data = $game->getHomeData();
                break;
            case 'games.mageSurvival.battle':
                $data = $game->getData();
                break;
        }
        return $data;
    }

    protected function getPlayer(Request $request)
    {
        $user = User::getUser();

        $guest = $user->getGuest();
        return $user;
    }

    protected function getGame(Request $request)
    {
        $game = GameBuilder::getGame($request);
        return $game;
    }



}
