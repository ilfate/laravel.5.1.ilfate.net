<?php

namespace Ilfate\Http\Controllers;

use Ilfate\Helper\Breadcrumbs;

class TestController extends \Ilfate\Http\Controllers\BaseController
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
     * @return Response
     */
    public function index()
    {
        $this->breadcrumbs->addLink(action($this->getCurrentClass() . '@' . __FUNCTION__), 'Code');
        return View::make('games.vortex.index');
    }

    /**
     * Main page
     *
     * @return Response
     */
    public function engine()
    {
        $this->breadcrumbs->addLink(action($this->getCurrentClass() . '@' . 'index'), 'Code');
        $this->breadcrumbs->addLink(action($this->getCurrentClass() . '@' . __FUNCTION__), 'Engine');
        return View::make('code.engine');
    }

    /**
     * Main page
     *
     * @return Response
     */
    public function stars()
    {
        $this->breadcrumbs->addLink(action($this->getCurrentClass() . '@' . 'index'), 'Code');
        $this->breadcrumbs->addLink(action($this->getCurrentClass() . '@' . __FUNCTION__), 'Stars');
        return View::make('code.stars');
    }
}
