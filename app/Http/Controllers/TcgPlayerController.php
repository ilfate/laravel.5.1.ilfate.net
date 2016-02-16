<?php

namespace Ilfate\Http\Controllers;

use Ilfate\Card;
use Ilfate\Deck as DeckModel;
use Ilfate\Helper\Breadcrumbs;
use Ilfate\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use Ilfate\Tcg\Game;

class TcgPlayerController extends \Ilfate\Http\Controllers\BaseController
{
    /**
     * @var Breadcrumbs
     */
    protected $breadcrumbs;

    /**
     * @var Game
     */
    protected $game;

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
    public function index(Request $request)
    {
        $request->session()->forget(User::GUEST_USER_SESSION_KEY);
        $player = User::getUser();
        $cardsCount = 0;
        $decks = [];
        if ($player->id) {
            $player->touch();
            $cardsCount = Card::getMyCardsCount();
            $decks = DeckModel::getMyDecks();
        }

        view()->share('decks', $decks);
        view()->share('player', [
            'id'   => $player->getId(),
            'name' => $player->getName(),
            'auth' => !! $player->id,
            'cardsCount' => $cardsCount,
        ]);

        return view('games.tcg.player.index');
    }

    /**
     * @param Request $request
     *
     * @return mixed
     */
    public function registerForm(Request $request)
    {
        $player = User::getUser();

        $formDefaults = $request->session()->get('formDefaults', null);
        if ($formDefaults) {
            view()->share('formDefaults', $formDefaults);
            $request->session()->forget('formDefaults');
        }

        view()->share('player', [
            'id'   => $player->getId(),
            'name' => $player->getName(),
            'auth' => $player->id,
        ]);

        return view('games.tcg.player.register');
    }

    public function registerSubmit(Request $request)
    {
        $email = $request->get('email');
        $password1 = $request->get('password1');
        $password2 = $request->get('password2');
        $name = $request->get('name');

        $player = User::getUser();

        $validator = Validator::make(
            array(
                'name' => $name,
                'password' => $password1,
                'email' => $email
            ),
            array(
                'name' => 'required|min:4|max:20|unique:users',
                'password' => 'required|min:6|max:60|in:' . $password2,
                'email' => 'required|email|unique:users|max:60'
            )
        );

        if ($player->id || $validator->fails())
        {
            $request->session()->set('formDefaults' , [
                'name' => $name,
                'email' => $email
            ]);
            return redirect('tcg/register')->withErrors($validator);
        }

        $player->email = $email;
        $player->password = $password1;
        $player->name = $name;

        $player->save();

        Card::createDefaultKings($player->id);

        Auth::loginUsingId($player->getId(), true);

        return redirect('tcg/me');
    }

    public function login(Request $request)
    {
        $player = User::getUser();
        if ($player->id) {
            return redirect('tcg/me');
        }
        $formErros = $request->session()->get('formErrors', null);
        if ($formErros) {
            $request->session()->forget('formErrors');
            view()->share('formErrors', $formErros);
        }
        return view('games.tcg.player.login');
    }

    public function loginSubmit(Request $request)
    {
        $email = $request->get('email');
        $password = $request->get('password');

        $player = User::getUser();

        if ($player->id)
        {
            // user is already logged in
            return redirect('tcg/me');
        }

        if (Auth::attempt(array('email' => $email, 'password' => $password),
            true))
        {
            return redirect('tcg/me');
        }
        $request->session()->set('formErrors' , [
            ['message' => Lang::get('tcg.authFail'), 'type' => 'danger'],
            ['message' => Lang::get('tcg.tryToRegister', ['url' => '/tcg/register']), 'type' => 'info'],
        ]);
        return redirect('tcg/login');
    }

    public function logout()
    {
        Auth::logout();
        return redirect('tcg/me');
    }
}
