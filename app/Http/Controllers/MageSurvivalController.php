<?php

namespace Ilfate\Http\Controllers;

use Ilfate\Mage;
use Ilfate\MageSurvival\Game;
use Ilfate\MageSurvival\GameBuilder;
use Ilfate\MageSurvival\MessageException;
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

        view()->share('page_title', 'Mage Survival - battle with your magic.');
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

    protected function getDataForView($view, Game $game)
    {
        $data = [];
        switch($view) {
            case 'games.mageSurvival.mage-list':
                $data['mages-types'] = \Config::get('mageSurvival.mages-types');
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
