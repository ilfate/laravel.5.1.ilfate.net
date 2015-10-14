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
        return view('code.index');
    }

    /**
     * Main page
     *
     * @return \Illuminate\View\View
     */
    public function engine()
    {
        $this->breadcrumbs->addLink(action($this->getCurrentClass() . '@' . 'index'), 'Code');
        $this->breadcrumbs->addLink(action($this->getCurrentClass() . '@' . __FUNCTION__), 'Engine');
        return view('code.engine');
    }

    /**
     * Main page
     *
     * @return \Illuminate\View\View
     */
    public function stars()
    {
        $this->breadcrumbs->addLink(action($this->getCurrentClass() . '@' . 'index'), 'Code');
        $this->breadcrumbs->addLink(action($this->getCurrentClass() . '@' . __FUNCTION__), 'Stars');
        return view('code.stars');
    }
}
