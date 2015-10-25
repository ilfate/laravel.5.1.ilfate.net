<?php

namespace Ilfate\Http\Controllers;

use Ilfate\Helper\Breadcrumbs;
use Illuminate\Support\Facades\Session;

class GamesController extends \Ilfate\Http\Controllers\BaseController
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
     * @return \Illuminate\View\View
     */
    public function index()
    {
        view()->share('page_title', 'Games made by Ilya Rubinchik.');

        $this->breadcrumbs->addLink(action($this->getCurrentClass() . '@' . __FUNCTION__), 'Games');
        return view('games.index');
    }

}
