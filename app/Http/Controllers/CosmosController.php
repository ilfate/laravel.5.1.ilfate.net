<?php

namespace Ilfate\Http\Controllers;

use Ilfate\Cosmos\Ship\Ship;
use Log;
use Cache;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CosmosController extends \Ilfate\Http\Controllers\BaseController
{
    const PAGE_NAME = 'cosmos';
    /**
     * Display a listing of the games.
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        \MetaTagsHelper::setPageName(self::PAGE_NAME);
//        $string = 'type1;1;0;0;0:Connection1;2;2;4;3:Connection2;3;0;3;0|type1;0;2;0|1;0;0;0';
        $string = 'type1;1;0;0;0:Connection1;2;0;3;0:Connection2;3;0;5;0|type1;0;2;0|1;0;0;0';
        $ship = Ship::createShipFromSerialiasedString($string);
        $ship->prepareToRender();

        view()->share('bodyClass', 'cosmos');
        view()->share('ship', $ship);

        return view('games.cosmos.index');//, array('game' => $game)
    }


}
