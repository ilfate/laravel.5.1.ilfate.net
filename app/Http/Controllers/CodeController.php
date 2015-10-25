<?php

namespace Ilfate\Http\Controllers;

use Ilfate\Helper\Breadcrumbs;

class CodeController extends \Ilfate\Http\Controllers\BaseController
{
    /**
     * @var Breadcrumbs
     */
    protected $breadcrumbs;

    /**
     * @param Breadcrumbs $breadcrumbs
     */
    public function __construct(Breadcrumbs $breadcrumbs) {
        $this->breadcrumbs = $breadcrumbs;
    }

    /**
     * Main page
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $this->breadcrumbs->addLink(action($this->getCurrentClass() . '@' . __FUNCTION__), 'Code');
        view()->share('bodyClass', 'code');
        return view('code.index');
    }

    /**
     * js game template
     *
     * @return \Illuminate\View\View
     */
    public function gameTemplate()
    {
        $this->breadcrumbs->addLink(action($this->getCurrentClass() . '@' . 'index'), 'Code');
        $this->breadcrumbs->addLink(action($this->getCurrentClass() . '@' . __FUNCTION__), 'Game Template');
        view()->share('bodyClass', 'game-template');
        return view('games.gameTemplate');
    }


    /**
     * RobotRock game
     *
     * @return \Illuminate\View\View
     */
    public function robotRock()
    {
        $this->breadcrumbs->addLink(action($this->getCurrentClass() . '@' . 'index'), 'Code');
        $this->breadcrumbs->addLink(action($this->getCurrentClass() . '@' . __FUNCTION__), 'RobotRock');
        view()->share('bodyClass', 'robot-rock');
        return view('games.robotRock.index');
    }
}
