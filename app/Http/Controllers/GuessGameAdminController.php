<?php

namespace Ilfate\Http\Controllers;

use Ilfate\GuessStats;
use Ilfate\Helper\Breadcrumbs;
use Ilfate\Series;
use Ilfate\SeriesImage;
use Ilfate\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;

class GuessGameAdminController extends \Ilfate\Http\Controllers\BaseController
{
    const PATH_TO_FILES = '/images/game/guess/';
    const USER_EDIT_RIGHTS = 2;

    /**
     *
     */
    public function __construct()
    {

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
            //redirect();
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
        $destinationPath = public_path() . self::PATH_TO_FILES;


        $extension = $file->getClientOriginalExtension();
        $filename = str_random(16) . '.' . $extension;
        $fileInPath = public_path() . self::PATH_TO_FILES . $filename;
        while (file_exists($fileInPath)) {
            $filename = str_random(16) . '.' . $extension;
            $fileInPath = public_path() . self::PATH_TO_FILES . $filename;
        }
        $upload_success = $file->move($destinationPath, $filename);

        if ($upload_success) {
            $fileRaw = file_get_contents($destinationPath . $filename);
            $image = new SeriesImage();
            $image->image = $fileRaw;
            $image->url = $filename;
            $image->series_id = $seriesId;
            $image->difficulty = $difficulty;

            $image->save();

            //unlink($destinationPath . $filename);
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
        $id = (int) $id;
        $images = SeriesImage::where('series_id', '=', $id)->get();
        $sortedImages = [1 => [], 2 => [], 3 => []];
        foreach ($images as $value) {
            $sortedImages[$value['difficulty']][] = $value->toArray();
        }
        view()->share('images', $sortedImages);
        view()->share('seriesId', $id);
        return view('games.guess.admin.series');
    }

    public function deleteImage($id)
    {
        $user = User::getUser();
        if (!$this->checkForUserEditRights($user)) {
            return response()->json(['forbidden' => 400]);
        }

        $image = SeriesImage::select('id', 'url', 'series_id')->where('id', $id)->first();
        $filename = public_path() . self::PATH_TO_FILES . $image->url;
        $seriesId = $image->series_id;
        if (file_exists($filename)) {
            unlink($filename);
            SeriesImage::where('id', '=', $id)->delete();
            return redirect('GuessSeries/admin/series/' . $seriesId);
        }
        return redirect('GuessSeries/admin/');
    }

    public function toggleActive($id) {
        $user = User::getUser();
        if (!$this->checkForUserEditRights($user)) {
            return redirect('tcg/login');
        }

        $series = Series::where('id', $id)->first();
        if ($series->active) {
            $series->active = 0;
        } else {
            $series->active = 1;
        }
        $series->save();
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
        $this->generateImagesFromDatabase($seriesId);
    }

    /**
     * @param null $seriesId
     */
    protected function generateImagesFromDatabase($seriesId = null)
    {
        if ($seriesId) {
            $images = SeriesImage::where('series_id', '=', $seriesId)->get();
        } else {
            $images = SeriesImage::get();
        }
        foreach ($images as $image) {
            file_put_contents(public_path() . self::PATH_TO_FILES . $image->url, $image->image);
            //file_put_contents(/home/ilfate/www/php/ilfate.net/public/images/game/guess/SLKdXTrglwvDm3ia.jpg): failed to open stream: Permission denied
        }
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
