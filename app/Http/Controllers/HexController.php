<?php

namespace Ilfate\Http\Controllers;

use Ilfate\Cosmos\Ship\Ship;
use Ilfate\Hex\Cell;
use Ilfate\Hex\HexagonalField;
use Log;
use Cache;
use Carbon\Carbon;
use Illuminate\Http\Request;

class HexController extends \Ilfate\Http\Controllers\BaseController
{
    const PAGE_NAME = 'hex';

    /**
     * Display game
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        \MetaTagsHelper::setPageName(self::PAGE_NAME);

        $field = new HexagonalField();
        $field->load($request);
        $field->build();

        view()->share('bodyClass', self::PAGE_NAME);
        view()->share('field', $field);

        return view('games.hex.index');//, array('game' => $game)
    }

    /**
     * resets game
     *
     * @return \Illuminate\View\View
     */
    public function reset(Request $request)
    {
        $request->session()->forget(HexagonalField::SESSION_NAME);
        return redirect('/hex');
    }


    public function action(Request $request)
    {
        $field = new HexagonalField();
        $field->load($request);
        $field->build();

        $type = $request->get('type');
        $x = $request->get('x');
        $y = $request->get('y');

        $field->action($type, ['x' => $x, 'y' => $y]);
        $field->save($request);
        $updates = $field->getUpdates();
        if ($updates) {
            return json_encode(['action' => $type, 'updates' => $updates]);
        }
        return '[]';
    }


}
