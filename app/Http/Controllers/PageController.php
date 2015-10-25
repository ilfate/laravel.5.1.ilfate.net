<?php

namespace Ilfate\Http\Controllers;

use Ilfate\Helper\Breadcrumbs;

class PageController extends BaseController
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
        return view('pages.index');
    }

    /**
     * Cv page
     *
     * @return \Illuminate\View\View
     */
    public function cv()
    {
        $this->breadcrumbs->addLink(action($this->getCurrentClass() . '@' . __FUNCTION__), 'CV');
        view()->share('bodyClass', 'cv');
        return view('pages.cv');
    }

    /**
     * Skills page
     *
     * @return \Illuminate\View\View
     */
    public function skills()
    {
        $this->breadcrumbs->addLink(action($this->getCurrentClass() . '@' . 'cv'), 'CV');
        $this->breadcrumbs->addLink(action($this->getCurrentClass() . '@' . __FUNCTION__), 'Skills');
        return view('pages.skills');
    }

    /**
     * My photo page
     *
     * @return \Illuminate\View\View
     */
    public function photo()
    {
        $this->breadcrumbs->addLink(action($this->getCurrentClass() . '@' . __FUNCTION__), 'Photo');
        $data = array(
            'images_gallery' => array(
                array('img' => '/images/my/baikal1.jpg', 'down-shift' => 0.1),
                array('img' => '/images/my/berlin1.jpg'),
                array('img' => '/images/my/snow1.jpg', 'down-shift' => 0.1),
                array('img' => '/images/my/berlin2.jpg'),
                array('img' => '/images/my/tu1.jpg'),
                array('img' => '/images/my/code1.jpg'),
                array('img' => '/images/my/snow3.jpg'),
                array('img' => '/images/my/ilfate2.jpg'),
                array('img' => '/images/my/tu2.jpg'),
                array('img' => '/images/my/snow0.jpg'),
                array('img' => '/images/my/aust2.jpg', 'down-shift' => 0.1),
                array('img' => '/images/my/aust3.jpg'),
                array('img' => '/images/my/is1.jpg'),
                array('img' => '/images/my/ilfate2.png'),

            )
        );
        return view('pages.photo', $data);
    }
}
