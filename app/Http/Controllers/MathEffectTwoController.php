<?php

namespace Ilfate\Http\Controllers;

use Ilfate\Helper\Breadcrumbs;
use Illuminate\Support\Facades\Session;

class MathEffectTwoController extends BaseController
{
    /**
     * @var Breadcrumbs
     */
    protected $breadcrumbs;

    /**
     * @param Breadcrumbs $breadcrumbs
     */
    public function __construct(Breadcrumbs $breadcrumbs)
    {
        $this->breadcrumbs = $breadcrumbs;
    }

    /**
     * Display a listing of the games.
     *
     * @return Response
     */
    public function index()
    {
        $this->breadcrumbs->addLink(action('GamesController' . '@' . 'index'), 'Games');
        $this->breadcrumbs->addLink(action($this->getCurrentClass() . '@' . __FUNCTION__), 'Math Effect');

        $name = Session::get('userName', null);

        View::share('page_title', 'Math Effect - logic game.');

        return View::make('games.mathEffectTwo.index');
    }

    /**
     * js game template
     *
     * @return Response
     */
    public function save()
    {
        if (Request::isMethod('get')) {
            Log::warning('MathEffect save is not Post.');
            App::abort(404);
        }
        if (!Request::ajax()) {
            Log::warning('MathEffect save is not Ajax.');
            App::abort(404);
        }
        $name     = Session::get('userName', null);
        $checkKey = Session::get('MEcheckKey', null);
        Session::put('MEcheckKey', null);

        if (Input::get('checkKey') != $checkKey) {
            Log::warning('Some one tryed to hack');
            Log::warning('pointsEarned=' . Input::get('pointsEarned'));
            Log::warning('turnsSurvived=' . Input::get('turnsSurvived'));
            Log::warning('unitsKilled=' . Input::get('unitsKilled'));
            Log::warning('ip=' . $_SERVER['REMOTE_ADDR']);
            Log::warning('name=' . $name);
            return '{}';
        }


        $tdStatistics                  = new TdStatistics();
        $tdStatistics->pointsEarned    = Input::get('pointsEarned');
        $tdStatistics->turnsSurvived   = Input::get('turnsSurvived');
        $tdStatistics->unitsKilled     = Input::get('unitsKilled');
        $tdStatistics->ip              = $_SERVER['REMOTE_ADDR'];
        $tdStatistics->laravel_session = md5(Cookie::get('laravel_session'));
        $tdStatistics->name            = $name;

        $tdStatistics->save();
        //return 'turns = ' . $turnsSurvived;
        return '{}';
    }

    public function saveName()
    {
        $name            = Input::get('name');
        $laravel_session = md5(Cookie::get('laravel_session'));

        Session::put('userName', $name);


        $stats = TdStatistics::where('laravel_session', '=', $laravel_session)->orderBy('created_at', 'desc')->firstOrFail();
        if (!$stats) {
            Log::warning('No user found to update name. (name=' . $name . ')');
            App::abort(404);
        } 
        $stats->name = $name;
        $stats->save();
        return '{"actions": ["Page.hideMENameForm"]}';
    }
}
