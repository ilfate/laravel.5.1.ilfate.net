<?php

namespace Ilfate\Http\Controllers;

use Ilfate\Helper\Breadcrumbs;
use Ilfate\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Validator;

class PageController extends BaseController
{
    const NEXT_PAGE_TO_GO = 'ilfate.next-page-to-go';
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
     * Main page
     *
     * @return \Illuminate\View\View
     */
    public function login(Request $request)
    {
        view()->share('mobileFriendly', true);
        $nextPageToGo = $request->get('page');
        if ($nextPageToGo) {
            $request->session()->set(self::NEXT_PAGE_TO_GO, $nextPageToGo);
        }
        $player = User::getUser();
        if ($player->id && !$player->is_guest) {
            if ($nextPageToGo) {
                return redirect($nextPageToGo);
            }
            return redirect('/games');
        }
        $formErros = $request->session()->get('formErrors', null);
        if ($formErros) {
            $request->session()->forget('formErrors');
            view()->share('formErrors', $formErros);
        }
        return view('pages.login');
    }
    
    public function loginAction(Request $request)
    {

        $email = $request->get('email');
        $password = $request->get('password');
        if (Auth::attempt(array('email' => $email, 'password' => $password),
            true))
        {
            $whereToRedirect = $request->session()->get(self::NEXT_PAGE_TO_GO);
            if ($whereToRedirect) {
                return redirect($whereToRedirect);
            } else {
                return redirect('/games');
            }
        }
        $request->session()->set('formErrors' , [
            ['message' => \Lang::get('tcg.authFail'), 'type' => 'danger'],
            ['message' => \Lang::get('tcg.tryToRegister', ['url' => '/tcg/register']), 'type' => 'info'],
        ]);
        return redirect('/login');
    }

    public function logout(Request $request)
    {
        $nextPageToGo = $request->get('page');
        Auth::logout();
        if ($nextPageToGo) {
            return redirect($nextPageToGo);
        }
        return redirect('/games');
    }

    /**
     * @param Request $request
     *
     * @return mixed
     */
    public function registerForm(Request $request)
    {
        view()->share('mobileFriendly', true);
        $formDefaults = $request->session()->get('formDefaults', null);
        if ($formDefaults) {
            view()->share('formDefaults', $formDefaults);
            $request->session()->forget('formDefaults');
        }
        $nextPageToGo = $request->get('page');
        if ($nextPageToGo) {
            $request->session()->set(self::NEXT_PAGE_TO_GO, $nextPageToGo);
        }
        $player = User::getUser();
        if ($player->id && !$player->is_guest) {
            if ($nextPageToGo) {
                return redirect($nextPageToGo);
            }
            return redirect('/games');
        }

        return view('pages.register');
    }

    public function registerSubmit(Request $request)
    {
        $email = $request->get('email');
        $password = $request->get('password');
        $password_confirm = $request->get('password_confirm');
        $name = $request->get('name');

        $player = User::getUser();

        $validator = Validator::make(
            array(
                'name' => $name,
                'password' => $password,
                'email' => $email
            ),
            array(
                'name' => 'required|min:4|max:20|unique:users',
                'password' => 'required|min:6|max:60|in:' . $password_confirm,
                'email' => 'required|email|unique:users|max:60'
            )
        );

        if ($validator->fails())
        {
            $request->session()->set('formDefaults' , [
                'name' => $name,
                'email' => $email
            ]);
            return redirect('/register')->withErrors($validator);
        }

        $player->email = $email;
        $player->password = \Hash::make($password);
        $player->name = $name;
        $player->is_guest = 0;

        $player->save();

        Auth::loginUsingId($player->getId(), true);

        $whereToRedirect = $request->session()->get(self::NEXT_PAGE_TO_GO);
        if ($whereToRedirect) {
            return redirect($whereToRedirect);
        } else {
            return redirect('/games');
        }
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
