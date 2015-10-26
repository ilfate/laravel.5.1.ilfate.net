<?php

namespace Ilfate\Http\Controllers;

use Ilfate\GuessStats;
use Ilfate\Series;
use Ilfate\SeriesImage;
use Ilfate\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;

class GuessGameAdminController extends \Ilfate\Http\Controllers\BaseController
{
    const USER_EDIT_RIGHTS = 2;

    protected $seriesModel;

    /**
     * @param Series $series
     */
    public function __construct(Series $series)
    {
        $this->seriesModel = $series;
    }

    /**
     * Display a listing of the games.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $series = Series::orderBy('name')->get();
        $user = User::getUser();
        $editAllowed = $this->checkForUserEditRights($user);
        view()->share('editAllowed', $editAllowed);
        view()->share('series', $series);

        return view('games.guess.admin.index');
    }

    /**
     * @return \Illuminate\View\View
     */
    public function addSeries(Request $request)
    {
        $user = User::getUser();
        if (!$this->checkForUserEditRights($user)) {
            return new RedirectResponse('/tcg/login');
        }
        if ($request->getMethod() == 'POST') {
            $series = new Series();
            $series->name = $request->get('name');
            $series->year = (int) $request->get('year');
            $series->difficulty = (int) $request->get('difficulty');

            if ($series->name) {
                $series->save();
            }
            return redirect('GuessSeries/admin');
        }
        return view('games.guess.admin.addSeries');
    }

    /**
     * @param Request $request
     *
     * @return mixed
     */
    public function addImage(Request $request)
    {
        $user = User::getUser();
        if (!$this->checkForUserEditRights($user)) {
            return response()->json(['forbidden' => 400]);
        }
        $seriesId = $request->get('id');
        $difficulty = $request->get('difficulty') ?: 1;
        if (!$seriesId) {
            return response()->json(['no id' => 400]);
        }
        $series = Series::find($seriesId);
        if (!$series) {
            return response()->json(['no series found' => 400]);
        }

        $file = $request->file('file');
        $isSaved = $this->seriesModel->addImage($file, $seriesId, $difficulty);

        if ($isSaved) {
            return response()->json(['success' => 200]);
        } else {
            return response()->json(['error' => 400]);
        }
    }

    /**
     * @param $id
     *
     * @return \Illuminate\View\View
     */
    public function seriesInfo($id)
    {
        $user = User::getUser();
        if (!$this->checkForUserEditRights($user)) {
            return redirect('tcg/login');
        }
        $images = $this->seriesModel->getImagesBySeriesId($id);
        $sortedImages = $this->seriesModel->sortImagesByDifficulty($images);
        view()->share('images', $sortedImages);
        view()->share('seriesId', $id);
        return view('games.guess.admin.series');
    }

    /**
     * @param $id
     *
     * @return RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function deleteImage($id)
    {
        $user = User::getUser();
        if (!$this->checkForUserEditRights($user)) {
            return response()->json(['forbidden' => 400]);
        }

        $seriesId = $this->seriesModel->deleteImageById($id);
        if ($seriesId) {
            return redirect('GuessSeries/admin/series/' . $seriesId);
        }
        return redirect('GuessSeries/admin/');
    }

    /**
     * @param $seriesId
     *
     * @return RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function toggleActive($seriesId) {
        $user = User::getUser();
        if (!$this->checkForUserEditRights($user)) {
            return redirect('tcg/login');
        }

        $this->seriesModel->toggleActive($seriesId);
        return redirect('GuessSeries/admin/');
    }

    /**
     * @return \Illuminate\View\View
     */
    public function liveStream()
    {
        $games = GuessStats::getLastGames();
        view()->share('games', $games);
        return view('games.guess.admin.liveStream');
    }

    /**
     * @param Request $request
     *
     * @return mixed
     */
    public function generateImages(Request $request)
    {
        $user = User::getUser();
        if (!$this->checkForUserEditRights($user)) {
            return response()->json(['forbidden' => 400]);
        }
        
        $seriesId = $request->get('seriesId', null);
        $this->seriesModel->generateImagesFromDatabase($seriesId);
    }

    /**
     * @param $user
     *
     * @return bool
     */
    public function checkForUserEditRights($user)
    {
        if ($user->rights < self::USER_EDIT_RIGHTS) {
            return false;
        }
        return true;
    }
}
