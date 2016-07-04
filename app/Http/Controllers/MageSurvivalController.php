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

class MageSurvivalController extends BaseController
{
    const PAGE_NAME = 'mageSurvival';

    const ACTION_MAGE_CREATE = 'mage-create';
    
    const CACHE_USER_LOGGING = 'ms-user-is-logged-';
    const CACHE_USER_LOGGING_INDEX = 'ms-user-log-index-';
    const CACHE_USER_LOGGING_DATA = 'ms-user-logged-data-';
    const CACHE_ENABLED_MINUTES = 60;
    const CACHE_SAVED_MINUTES = 540;

    protected $isLogged = false;

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
        view()->share('mobileFriendly', true);
        if (env('APP_DEBUG') === true) {
            view()->share('localDevelopment', false);
        }
        $game = $this->getGame($request);
        
        $this->setUpLogging($game);

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
        $mageType = strtolower(strip_tags(htmlentities($request->get('type'))));

        $game = $this->getGame($request);
        $data = [];
        $data['stats'] = $game->getMageUser()->getStats();
        $availableTypes = $this->getAvailableMagesList($data);
        if (empty($availableTypes[$mageType]['available'])) {
            return [
                'action'=> 'reload',
                'status' => 0
            ];
        }
        $addTutorialStatus = false;
        $mages = $user->mages()->get();
        if ($mages->count() < 1) {
            $addTutorialStatus = true;
        }

        $mage->class = $mageType;
        $mage->player_id = $user->getId();
        $mage->status = 1;
        $mage->name = strip_tags(htmlentities($request->get('name')));
        if ($addTutorialStatus) {
            $mage->data = json_encode(['tutorial' => strip_tags(htmlentities($request->get('device')))]);
        }

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
        $turn = (int) $request->get('turn');
        if ($game->getTurn() != $turn) {
            return json_encode(['action' => 'reload']);
        }
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
        if ($this->isLoggingEnabled($game->getUser())) {
            $this->logAction(['result' => $result, 'action' => $action, 'data' => $data], $game->getUser());
        }
        return json_encode($result);
    }

    public function world($name, Request $request)
    {
        $game = $this->getGame($request);
        $game->setWorldType($name);
        return redirect('/Spellcraft');
    }

    public function admin(Request $request)
    {
        $user = User::getUser();

        if ($user->rights != 2) {
            return redirect('/Spellcraft');
        }
        $userLogs = $this->getAllUserLogs();

        view()->share('isAdmin', true);
        view()->share('viewData', ['userLogs' => $userLogs]);
        view()->share('bodyClass', 'mage-survival');
        view()->share('mobileFriendly', true);
        return view('games.mageSurvival.admin');
    }

    public function adminPage($userId, $pageTime, Request $request)
    {
        $user = User::getUser();
        if ($user->rights != 2) {
            return redirect('/Spellcraft');
        }
        $pageLogs = $this->getLoggedPage($userId, $pageTime);
        if (!$pageLogs) {
            return redirect('/Spellcraft/admin');
        }

        view()->share('isAdmin', true);
        view()->share('viewData', $pageLogs);
        view()->share('bodyClass', 'mage-survival');
        view()->share('mobileFriendly', true);
        return view('games.mageSurvival.admin-battle');
    }

    public function adminGetActions($userId, $pageTime, Request $request)
    {
        $actionId = $request->get('action');
        $user = User::getUser();
        if ($user->rights != 2) {
            return '[]';
        }
        $actions = $this->getActions($userId, $pageTime, $actionId);
        if (!$actions) {
            return '[]';
        }
        return json_encode($actions);
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
                $data['stats'] = $game->getMageUser()->getStats();
                $data['mages-types'] = $this->getAvailableMagesList($data);
                $exportMages = [];
                $mages = $game->getInactiveMages();
                foreach ($mages as $mage) {
                    $mageData = json_decode($mage->data, true);
                    $exportMage = [
                        'name' => $mage->name,
                        'stats' => empty($mageData[AliveCommon::DATA_STAT_KEY]) ? [] : $mageData[AliveCommon::DATA_STAT_KEY]
                    ];
                    $exportMages[] = $exportMage;
                }
                $data['mages'] = $exportMages;
                $data['schools'] = \Config::get('mageSpells.schools');
                $data['user'] = $game->getUser();
                break;
            case 'games.mageSurvival.mage-home':
                $data = $game->getHomeData();
                break;
            case 'games.mageSurvival.user-battle':
                $data = $game->getData();
                if ($this->isLogged) {
                    $this->logPageOpen($data, $game);
                }
                break;
        }

        return $data;
    }

    protected function getAvailableMagesList($data)
    {
        $allTypes = \Config::get('mageSurvival.mages-types');
        foreach ($allTypes as &$mageType) {
            if (!empty($mageType['stats'])) {
                $isAvailable = true;
                foreach ($mageType['stats'] as $stat => $value) {
                    if (empty($data['stats'][$stat]) || $value > $data['stats'][$stat]) {
                        $isAvailable = false;
                    }
                }
                if ($isAvailable) {
                    $mageType['available'] = true;
                }
            }
        }
        return $allTypes;
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
    
    protected function setUpLogging(Game $game)
    {
        if ($game->getStatus() !== Game::STATUS_BATTLE) {
            return false;
        }
        $user = User::getUser();
        $isLoggingWasEnabled = $this->isLoggingEnabled($user);
        if ($isLoggingWasEnabled) {
            return true;
        }
        // it was not enabled. on it is expired.
        $chance = \Config::get('mageSurvival.game.user-logging-chance');
        if(ChanceHelper::chance($chance)) {
            $this->enableLogging($user);
        }
        return false;
    }

    protected function isLoggingEnabled(User $user)
    {
        if ($this->isLogged === true) return true;
        $isEnabled = Cache::get(self::CACHE_USER_LOGGING . $user->id);
        if ($isEnabled) {
            $this->enableLogging($user);
            return true;
        }
        return false;
    }

    protected function enableLogging($user)
    {
        $this->isLogged = true;
        Cache::put(self::CACHE_USER_LOGGING . $user->id, true, self::CACHE_ENABLED_MINUTES);
    }

    protected function logPageOpen($data, Game $game)
    {
        $user = $game->getUser();
        $index = Cache::get(self::CACHE_USER_LOGGING_INDEX . $user->id);
        $pageTime = time();
        $info = [
            'map' => $game->getWorld()->getType(),
            'turn' => $game->getWorld()->getTurn(),
        ];
        if ($index) {
            $index['pages'][] = $pageTime;
            $index['info'][$pageTime] = $info;
            $index['currentPage'] = $pageTime;
        } else {
             $index = [
                 'pages' => [
                     $pageTime
                 ],
                 'actions' => [],
                 'info' => [$pageTime => $info],
                 'currentPage' => $pageTime
             ];
        }
        Cache::put(self::CACHE_USER_LOGGING_INDEX . $user->id, $index, self::CACHE_SAVED_MINUTES);
        Cache::put($this->getKeyForPageLog($user->id, $pageTime), json_encode($data), self::CACHE_SAVED_MINUTES);
        $user->last_visit = new \DateTime();
        $user->save();
    }

    protected function logAction($data, User $user)
    {
        $index = Cache::get(self::CACHE_USER_LOGGING_INDEX . $user->id);

        if ($index && !empty($index['currentPage'])) {
            $pageTime = $index['currentPage'];
            if (empty($index['actions'][$pageTime])) {
                $index['actions'][$pageTime] = 0;
                $actionId = 1;
            } else {
                $actionId = $index['actions'][$pageTime] + 1;
            }
            $index['actions'][$pageTime]++;
        } else {
             return;
        }
        Cache::put(self::CACHE_USER_LOGGING_INDEX . $user->id, $index, self::CACHE_SAVED_MINUTES);
        Cache::put($this->getKeyForActionLog($user->id, $pageTime, $actionId), json_encode($data), self::CACHE_SAVED_MINUTES);
    }

    protected function loadAllPages($user)
    {
        $result = false;
        $index = Cache::get(self::CACHE_USER_LOGGING_INDEX . $user->id);
        if (!$index || empty($index['pages'])) return $result;
        $pages = [];
        foreach ($index['pages'] as $pageTime) {
            $pages[] = [
                'pageTime' => $pageTime,
                'info' => $index['info'][$pageTime],
                'time' => Carbon::createFromTimestamp($pageTime)->toDateTimeString(),
                'actions' => empty($index['actions'][$pageTime]) ? 0 : $index['actions'][$pageTime]
            ];
        }
        return $pages;
    }

    protected function getAllUserLogs()
    {
        $users = [];
        $activeUsers = DB::table('users')
            ->select(DB::raw('id,name,email'))
            ->where('last_visit', '>', Carbon::now()->subHours(6))
            ->orderBy('last_visit')
            ->get();
        foreach ($activeUsers as $activeUser) {
            $users[] = [
                'id' => $activeUser->id,
                'name' => $activeUser->name,
                'email' => $activeUser->email,
                'pages' => $this->loadAllPages($activeUser)
            ];
        }
        return $users;
    }

    protected function getLoggedPage($userId, $pageTime)
    {
        $index = Cache::get(self::CACHE_USER_LOGGING_INDEX . $userId);
        if (!$index) {
            return null;
        }
        $page = Cache::get($this->getKeyForPageLog($userId, $pageTime));
        if (!$page) return null;
        $viewData = json_decode($page, true);
        $actions = [];
        if (!empty($index['actions'][$pageTime])) {
            for ($i = 1; $i <= $index['actions'][$pageTime]; $i++) {
                $actions[] = json_decode(Cache::get($this->getKeyForActionLog($userId, $pageTime, $i)), true);
            }
        }
        $viewData['game']['loggedActions'] = $actions;
        $viewData['game']['userId'] = $userId;
        $viewData['game']['pageTime'] = $pageTime;
        return $viewData;
    }

    protected function getActions($userId, $pageTime, $actionId)
    {
        $index = Cache::get(self::CACHE_USER_LOGGING_INDEX . $userId);
        $return = [];
        if ($index && $index['currentPage'] != $pageTime) {
            $return['thisWasLast'] = true;
        }
        if (!$index || $index['actions'][$pageTime] == $actionId) {
            return $return;
        }
        $actions = [];
        if (!empty($index['actions'][$pageTime])) {
            for ($i = $actionId + 1; $i <= $index['actions'][$pageTime]; $i++) {
                $actions[] = json_decode(Cache::get($this->getKeyForActionLog($userId, $pageTime, $i)), true);
            }
        }
        $return['actions'] = $actions;

        return $return;
    }

    protected function getKeyForPageLog($userId, $time)
    {
        return self::CACHE_USER_LOGGING_DATA . $userId . '-page-' . $time;
    }

    protected function getKeyForActionLog($userId, $pageTime, $actionId)
    {
        return self::CACHE_USER_LOGGING_DATA . $userId . '-page-' . $pageTime . '-action-' . $actionId;
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

    public function addAllItems(Request $request)
    {
        if (env('APP_DEBUG') !== true) {
//            return redirect('/Spellcraft');
        }
        $game = $this->getGame($request);

        $game->addAllItems();
        return redirect('/Spellcraft');
    }

}
