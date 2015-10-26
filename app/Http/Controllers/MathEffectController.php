<?php

namespace Ilfate\Http\Controllers;

use Ilfate\Helper\Breadcrumbs;
use Ilfate\TdStatistics;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Cache;

class MathEffectController extends BaseController
{
    const PAGE_NAME = 'mathEffect';
    const CACHE_KEY_STATS_TOTAL = 'ME_stats';
    /**
     * @var Breadcrumbs
     */
    protected $breadcrumbs;

    /**
     * @var TdStatistics
     */
    protected $mathEffectModel;

    /**
     * @param Breadcrumbs $breadcrumbs
     */
    public function __construct(Breadcrumbs $breadcrumbs, TdStatistics $mathEffectModel)
    {
        $this->breadcrumbs = $breadcrumbs;
        $this->mathEffectModel = $mathEffectModel;
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
        \MetaTagsHelper::setPageName(self::PAGE_NAME);
        $this->breadcrumbs->addLink(action('GamesController' . '@' . 'index'), 'Games');
        $this->breadcrumbs->addLink(action($this->getCurrentClass() . '@' . __FUNCTION__), 'Math Effect');

        $name = $request->session()->get('userName', null);

        $MEcheckKey = md5(rand(0,99999) . time());
        $request->session()->put('MEcheckKey', $MEcheckKey);

        view()->share('page_title', 'Math Effect - logic game.');
        view()->share('facebookEnabled', true);

        return view('games.mathEffect.index', array('userName' => $name, 'checkKey' => $MEcheckKey));
    }

    /**
     * js game template
     *
     * @param Request $request
     *
     * @return string
     */
    public function save(Request $request)
    {
        if ($request->isMethod('get')) {
            Log::warning('MathEffect save is not Post.');
            abort(404);
        }
        if (!$request->ajax()) {
            Log::warning('MathEffect save is not Ajax.');
            abort(404);
        }
        $name     = $request->session()->get('userName', null);
        $checkKey = $request->session()->get('MEcheckKey', null);
        $request->session()->put('MEcheckKey', null);

        if ($request->get('checkKey') != $checkKey) {
            Log::warning('Some one tryed to hack');
            Log::warning('pointsEarned=' . $request->get('pointsEarned'));
            Log::warning('turnsSurvived=' . $request->get('turnsSurvived'));
            Log::warning('unitsKilled=' . $request->get('unitsKilled'));
            Log::warning('ip=' . $_SERVER['REMOTE_ADDR']);
            Log::warning('name=' . $name);
            return '{}';
        }


        $tdStatistics                  = new TdStatistics();
        $tdStatistics->pointsEarned    = $request->get('pointsEarned');
        $tdStatistics->turnsSurvived   = $request->get('turnsSurvived');
        $tdStatistics->unitsKilled     = $request->get('unitsKilled');
        $tdStatistics->ip              = $_SERVER['REMOTE_ADDR'];
        $tdStatistics->laravel_session = md5($request->cookie('laravel_session'));
        $tdStatistics->name            = $name;

        $tdStatistics->save();
        return '{}';
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


        $stats = TdStatistics::where('laravel_session', '=', $laravel_session)->orderBy('created_at', 'desc')->firstOrFail();
        if (!$stats) {
            Log::warning('No user found to update name. (name=' . $name . ')');
            abort(404);
        } 
        $stats->name = $name;
        $stats->save();
        return '{"actions": ["MathEffectPage.hideMENameForm"]}';
    }

    /**
     * @param Request $request
     *
     * @return mixed
     */
    public function statistic(Request $request)
    {
        \MetaTagsHelper::setPageName(self::PAGE_NAME);
        $this->breadcrumbs->addLink(action('GamesController' . '@' . 'index'), 'Games');
        $this->breadcrumbs->addLink(action($this->getCurrentClass() . '@' . 'index'), 'Math Effect');
        $this->breadcrumbs->addLink(action($this->getCurrentClass() . '@' . __FUNCTION__), 'Statistic');

        $cachedStats = Cache::get(self::CACHE_KEY_STATS_TOTAL, null);

        if (!$cachedStats) {
            $topLogs    = $this->mathEffectModel->getTopLogs();
            $totalGames = $this->mathEffectModel->getTotalGames();
            $avrTurns   = $this->mathEffectModel->getAverageTurns();
            $users      = $this->mathEffectModel->getPlayersNumber();
            $todayLogs  = $this->mathEffectModel->getTodayLogs();
            $expiresAt  = Carbon::now()->addMinutes(8);
            $data = [
                'topLogs'    => $topLogs,
                'totalGames' => $totalGames,
                'avrTurns'   => $avrTurns,
                'users'      => $users,
                'todayLogs'  => $todayLogs
            ];
            Cache::put(self::CACHE_KEY_STATS_TOTAL, $data, $expiresAt);
        } else {
            $topLogs    = $cachedStats['topLogs'];
            $totalGames = $cachedStats['totalGames'];
            $avrTurns   = $cachedStats['avrTurns'];
            $users      = $cachedStats['users'];
            $todayLogs  = $cachedStats['todayLogs'];
        }

        $name     = $request->session()->get('userName', null);
        $userLogs = false;
        if ($name) {
            $userLogs = $this->mathEffectModel->getUserStatsByName($name);
        }
        view()->share('facebookEnabled', true);

        return view('games.mathEffect.stats.index', array(
            'topLogs'    => $topLogs, 
            'todayLogs'  => $todayLogs, 
            'totalGames' => $totalGames,
            'avrTurns'   => $avrTurns,
            'users'      => $users,
            'userLogs'   => $userLogs
            ));
    }
}
