<?php

namespace Ilfate\Http\Controllers;

use Ilfate\Series;
use Log;
use Cache;
use Ilfate\GuessStats;
use Ilfate\SeriesImage;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;
use Illuminate\Http\Request;

class GuessGameController extends \Ilfate\Http\Controllers\BaseController
{
    const SESSION_DATA = 'guess.game';

    const CACHE_KEY_STATS_MONTH = 'guess.stats.month';
    const CACHE_KEY_STATS_DAY   = 'guess.stats.day';
    const CACHE_KEY_STATS_TOTAL = 'guess.stats.total';

    const GAME_TURN = 'turn';
    const GAME_STARTED = 'started';
    const GAME_START_TIME = 'start_time';
    const GAME_TURN_START_TIME = 'turn_start_time';
    const GAME_CURRENT_QUESTION = 'current_question';
    const GAME_POINTS = 'points';
    const GAME_ABILITIES = 'abilities';
    const GAME_FINISHED = 'finished';
    const GAME_PREV_QUESTIONS = 'prev_questions';

    const REDDIT = 'http://www.reddit.com/r/GuessSeries/';

    /**
     * Display a listing of the games.
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $game = $this->createGame();
        $game[self::GAME_CURRENT_QUESTION] = $this->getNewQuestion($game['turn']);
        $this->saveGame($game, $request);

        if ($game['turn'] == 1) {
            $firstQuestion = json_encode($this->exportQuestion($game[self::GAME_CURRENT_QUESTION]));
        } else {
            $firstQuestion = '{}';
        }
        view()->share('firstQuestion', $firstQuestion);

        view()->share('page_title', 'Guess Series game');
        view()->share('reddit', self::REDDIT);

        return view('games.guess.index');//, array('game' => $game)
    }

    /**
     * @return \Illuminate\View\View
     */
    public function stats()
    {
        $imageStats = GuessStats::getHardestImage([time() - 24 * 60 * 60, time() + 2 * 60 * 60]);
        $url = SeriesImage::where('id', $imageStats->image_id)->pluck('url');
        view()->share('hardestPicture', $url);
        view()->share('today', $this->getStatsToday());
        view()->share('month', $this->getStatsMonth());
        view()->share('total', $this->getStatsTotal());
        view()->share('reddit', self::REDDIT);
        view()->share('page_title', 'Guess Series Leaderboard');
        return view('games.guess.stats');
    }

    /**
     * @param Request $request
     *
     * @return array
     */
    public function gameStarted(Request $request)
    {
        $game = $this->getGame(false, $request);
        if (!empty($game[self::GAME_STARTED])) {
            return [];
        }
        $game[self::GAME_STARTED] = true;
        $game[self::GAME_START_TIME] = time();
        $game[self::GAME_TURN_START_TIME] = time();
        $this->saveGame($game, $request);
        return [];
    }

    /**
     * @param Request $request
     *
     * @return string
     * @throws \Exception
     */
    public function answer(Request $request)
    {
        $game = $this->getGame(true, $request);
        if (!$game || $game[self::GAME_FINISHED]) {
            if (!$game) {
                Log::error('ERROR. We are in "answer", but the game was not found in session. Terminating.');
            }
            return '[]';
        }
        $id = (int) $request->input('id');
        $seconds = (int) $request->input('seconds');

        if ($game[self::GAME_CURRENT_QUESTION]['correct'] === $id) {
            $result = $this->addPointsToGame($game, $seconds);
            $game[self::GAME_TURN]++;
            $prevQuestions = [$game[self::GAME_CURRENT_QUESTION]['seriesId']];
            if (!empty($game[self::GAME_PREV_QUESTIONS])) {
                $prevQuestions[] = $game[self::GAME_PREV_QUESTIONS][0];
                if (!empty($game[self::GAME_PREV_QUESTIONS][1])) {
                    $prevQuestions[] = $game[self::GAME_PREV_QUESTIONS][1];
                }
            }
            $game[self::GAME_PREV_QUESTIONS] = $prevQuestions;
            $game[self::GAME_CURRENT_QUESTION] = $this->getNewQuestion($game[self::GAME_TURN], $prevQuestions);
            $game[self::GAME_TURN_START_TIME] = time();

            $this->saveGame($game, $request);
            return json_encode([
                'question' => $this->exportQuestion($game[self::GAME_CURRENT_QUESTION]), 
                'result' => $result
                ]);
        } else {
            $name = $request->session()->get('userName', false);
            $game[self::GAME_FINISHED] = true;
            $this->saveResults($game[self::GAME_CURRENT_QUESTION], $request);
            $return = [
                'finish' => true,
                'correctAnswer' => $game[self::GAME_CURRENT_QUESTION]['correct'],
                'correctAnswersNumber' => $game[self::GAME_TURN] - 1,
                'points' => $game[self::GAME_POINTS],
                'name' => $name,
                'stats' => $this->getStatsToday($game[self::GAME_POINTS])
            ];
            
            $this->saveGame($game, $request);
            return json_encode($return);
        }
    }

    /**
     * @param Request $request
     *
     * @return string
     */
    public function timeIsOut(Request $request)
    {
        $name = $request->session()->get('userName', false);
        $game = $this->getGame(false, $request);
        if ($game[self::GAME_FINISHED]) {
            return '[]';
        }
        $return = [
            'correctAnswer' => $game[self::GAME_CURRENT_QUESTION]['correct'],
            'points' => $game[self::GAME_POINTS],
            'correctAnswersNumber' => $game[self::GAME_TURN] - 1,
            'name' => $name,
            'stats' => $this->getStatsToday()
        ];
        $game[self::GAME_FINISHED] = true;
        $this->saveGame($game, $request);
        $this->saveResults($game[self::GAME_CURRENT_QUESTION], $request);
        return json_encode($return);
    }

    /**
     * @param Request $request
     *
     * @return string
     * @throws \Exception
     */
    public function ability(Request $request)
    {
        $id = (int) $request->input('id');
        $game = $this->getGame(false, $request);
        if (in_array($id, $game[self::GAME_ABILITIES])) {
            return '[]';
        }
        $game[self::GAME_ABILITIES][] = $id;
        $game[self::GAME_TURN_START_TIME] = time();
        $result = [
            'id' => $id
        ];
        switch ($id) {
            case 1: // 50/50
                $keysToRemove = [];
                $options = $game[self::GAME_CURRENT_QUESTION]['options'];
                for($i = 0; $i < 3; $i++) {
                    $randKey = array_rand($options);
                    unset($options[$randKey]);
                    if ($randKey == $game[self::GAME_CURRENT_QUESTION]['correct']) {
                        continue;
                    }
                    $keysToRemove[] = $randKey;
                    if (count($keysToRemove) == 2) {
                        break;
                    }
                }
                $result['wrong'] = $keysToRemove;
                break;
            case 2:
                $game[self::GAME_CURRENT_QUESTION] = $this->getNewQuestion($game[self::GAME_TURN]);
                $result['question'] = $this->exportQuestion($game[self::GAME_CURRENT_QUESTION]);
                break;
            case 3:
                $currentLevel = $this->getCurrentLevelConfig($game[self::GAME_TURN]);
                $levelConfig  = \Config::get('guess.game.levels.' . $currentLevel);
                $question     = $game[self::GAME_CURRENT_QUESTION];
                switch($game[self::GAME_CURRENT_QUESTION]['type']) {
                    // find new image for every type of question
                    case 1:// here we need to change image for question
                        $currentImageId = $question['picture']['id'];
                        $seriesId = $question['picture']['series_id'];
                        $imageDifficulty = $levelConfig[3][array_rand($levelConfig[3])];

                        $question['picture'] = $this->getPicture($imageDifficulty, $seriesId, [$currentImageId]);
                        break;
                    case 2:
                        foreach ($question['options'] as $key => &$image) {
                            $imageDifficulty = $levelConfig[3][array_rand($levelConfig[3])];
                            $question['options'][$key] = $this->getPicture($imageDifficulty, $image['series_id'], [$image['id']]);
                        }
                        break;
                }
                $game[self::GAME_CURRENT_QUESTION] = $question;
                $result['question'] = $this->exportQuestion($game[self::GAME_CURRENT_QUESTION]);
                break;
        }

        $this->saveGame($game, $request);
        return json_encode($result);
    }

    /**
     * @param Request $request
     *
     * @return string
     */
    public function saveName(Request $request)
    {
        $name            = $request->input('name');
        $laravel_session = md5($request->cookie('laravel_session'));
        if (!$name) {
            return '[]';
        }

        $request->session()->put('userName', $name);

        $stats = GuessStats::where('laravel_session', '=', $laravel_session)->orderBy('created_at', 'desc')->firstOrFail();
        if (!$stats) {
            Log::warning('No user found to update name. (name=' . $name . ')');
            abort(404);
        }
        $stats->name = $name;
        $stats->save();
        return '{"actions": ["Guess.Game.hideNameForm"]}';
    }

    /**
     * @param         $question
     * @param Request $request
     */
    protected function saveResults($question, Request $request)
    {
        $name     = $request->session()->get('userName', null);
        $game = $this->getGame(false, $request);

        switch($question['type']) {
            case 1:
                $imageId = $question['picture']['id'];
                break;
            case 2:
                $imageId = $question['options'][$question['correct']]['id'];
                break;
        }

        $stats = new GuessStats();
        $stats->points = $game[self::GAME_POINTS];
        $stats->answers = $game[self::GAME_TURN] - 1;
        $stats->ip      = $_SERVER['REMOTE_ADDR'];
        $stats->laravel_session = md5($request->cookie('laravel_session'));
        $stats->name = $name;
        $stats->image_id = $imageId;
        $stats->save();
    }

    /**
     * @param $turn
     *
     * @return int
     */
    protected function getCurrentLevelConfig($turn)
    {
        $difficulty = \Config::get('guess.game.difficulty');
        $currentLevel = 0;
        foreach ($difficulty as $turns => $level) {
            if ($turn <= $turns) {
                $currentLevel = $level - 1;
                break;
            }
        }
        if (!$currentLevel && $turn) {
            $currentLevel = 11;
        }
        return $currentLevel;
    }

    /**
     * @param       $turn
     * @param array $excludeSeriesIds
     *
     * @return array
     * @throws \Exception
     */
    protected function getNewQuestion($turn, $excludeSeriesIds = array()) 
    {
        $currentLevel = $this->getCurrentLevelConfig($turn);
        $levelConfig = \Config::get('guess.game.levels.' . $currentLevel);

        $typeId           = $this->getArrayRandomValue($levelConfig[4]);
        $seriesDifficulty = $this->getArrayRandomValue($levelConfig[2]);

        $question = [
            'sec' => $levelConfig[0],
            'level' => $currentLevel,
            'type' => $typeId,
        ];

        $answerSeries = $this->getRandomSeries($seriesDifficulty, $excludeSeriesIds);
        $question['seriesId'] = $answerSeries['id'];
        switch ($typeId) {
            case 1:
                $imageDifficulty = $levelConfig[3][array_rand($levelConfig[3])];
                $question['picture'] = $this->getPicture($imageDifficulty, $answerSeries['id']);
                //$this->getArrayRandomValue($levelConfig[2])
                $wrong1 = $this->getRandomSeries($seriesDifficulty, [$answerSeries['id']]);
                $wrong2 = $this->getRandomSeries($seriesDifficulty, [$answerSeries['id'], $wrong1['id']]);
                $wrong3 = $this->getRandomSeries($seriesDifficulty, [$answerSeries['id'], $wrong1['id'], $wrong2['id']]);
                $question['all'] = [
                    $answerSeries['name'], 
                    $wrong1['name'],
                    $wrong2['name'],
                    $wrong3['name'],
                ];
                break;
            case 2:
                $question['name'] = $answerSeries['name'];
                //$this->getArrayRandomValue($levelConfig[2])
                $wrong1 = $this->getRandomSeries($seriesDifficulty, [$answerSeries['id']]);
                $wrong2 = $this->getRandomSeries($seriesDifficulty, [$answerSeries['id'], $wrong1['id']]);
                $wrong3 = $this->getRandomSeries($seriesDifficulty, [$answerSeries['id'], $wrong1['id'], $wrong2['id']]);
                $question['all'] = [
                    $this->getPicture($this->getArrayRandomValue($levelConfig[3]), $answerSeries['id']),
                    $this->getPicture($this->getArrayRandomValue($levelConfig[3]), $wrong1['id']),
                    $this->getPicture($this->getArrayRandomValue($levelConfig[3]), $wrong2['id']),
                    $this->getPicture($this->getArrayRandomValue($levelConfig[3]), $wrong3['id']),
                ];
                break;
            default:
                throw new \Exception('this question type is not implemented');
                break;
        }
        $question['options'] = [];
        for ($i = 0; $i < 4; $i++) {
            $randKey = array_rand($question['all']);
            $question['options'][] = $question['all'][$randKey];
            unset($question['all'][$randKey]);
            if ($randKey === 0) {
                $question['correct'] = $i;
            }
        }
        unset($question['all']);
        return $question;
    }

    /**
     * @param null $currentResult
     *
     * @return mixed
     */
    protected function getStatsToday($currentResult = null)
    {
        $cachedStats = Cache::get(self::CACHE_KEY_STATS_DAY, null);
        if ($cachedStats) {
            list($lastElement) = array_slice($cachedStats, -1);
        }
        if (!$cachedStats ||
            ($currentResult &&
                ($lastElement['points'] < $currentResult || count($cachedStats) < 10)
            )
        ) {
            $stats = GuessStats::getTopStatistic([time() - (24 * 60 * 60), time() + (4 * 60 * 60)]);
            foreach ($stats as $key => &$stat) {
                $stat['key'] = $key + 1;
                if (!$stat['name']) {
                    $stat['name'] = '-----';
                }
            }
            $expiresAt = Carbon::now()->addMinutes(10);
            Cache::put(self::CACHE_KEY_STATS_DAY, $stats, $expiresAt);
        } else {
            $stats = $cachedStats;
        }
        return $stats;
    }

    /**
     * @return mixed
     */
    protected function getStatsMonth()
    {
        $cachedStats = Cache::get(self::CACHE_KEY_STATS_MONTH, null);
        if (!$cachedStats) {
            $stats = GuessStats::getTopStatistic([time() - 30 * 24 * 60 * 60, time() + 4 * 60 * 60]);
            foreach ($stats as $key => &$stat) {
                $stat['key'] = $key + 1;
            }
            $expiresAt = Carbon::now()->addMinutes(10);
            Cache::put(self::CACHE_KEY_STATS_MONTH, $stats, $expiresAt);
        } else {
            $stats = $cachedStats;
        }
        return $stats;
    }

    /**
     * @return array
     */
    protected function getStatsTotal()
    {
        $cachedStats = Cache::get(self::CACHE_KEY_STATS_TOTAL, null);
        if (!$cachedStats) {
            $stats = GuessStats::getTotalStatistic();
            $expiresAt = Carbon::now()->addMinutes(15);
            Cache::put(self::CACHE_KEY_STATS_TOTAL, $stats, $expiresAt);
        } else {
            $stats = $cachedStats;
        }
        return $stats;
    }

    /**
     * @param $question
     *
     * @return array
     */
    protected function exportQuestion($question)
    {
        $toExport = [
            'sec' => $question['sec'],
            'type' => $question['type'],
            'options' => $question['options'],
        ];
        switch($question['type']) {
            case 1:
                $toExport['picture'] = $question['picture']['url'];
                break;
            case 2:
                $toExport['name'] = $question['name'];
                foreach ($toExport['options'] as $key => &$value) {
                    $value = $value['url'];
                }
                break;
        }
        return $toExport;
    }

    /**
     * @param bool|false $isRequired
     *
     * @param Request    $request
     *
     * @return array
     */
    protected function getGame($isRequired = false, Request $request)
    {
        $game = $request->session()->get(self::SESSION_DATA, null);
        if (!$game && !$isRequired) {
            $game = $this->createGame();
        }
        return $game;
    }

    /**
     * @return array
     */
    protected function createGame()
    {
        return [
            self::GAME_TURN => 1,
            self::GAME_STARTED => 0,
            'bonuses' => [],
            self::GAME_CURRENT_QUESTION => false,
            self::GAME_POINTS => 0,
            self::GAME_FINISHED => false,
            self::GAME_ABILITIES => [],
        ];
    }

    /**
     * @param         $game
     * @param Request $request
     */
    protected function saveGame($game, Request $request)
    {
        $request->session()->forget(self::SESSION_DATA);
        $request->session()->put(self::SESSION_DATA, $game);
    }

    /**
     * @param $game
     * @param $seconds
     *
     * @return array
     */
    protected function addPointsToGame(&$game, $seconds)
    {
        $question = $game[self::GAME_CURRENT_QUESTION];
        if ($seconds > $question['sec']) {
            // user tried to fake the data
            $seconds = (int) ($question['sec'] * 0.25);
        }
        $phpSeconds = $question['sec'] - (time() - $game[self::GAME_TURN_START_TIME]);
        if ($phpSeconds + 3 < $seconds) {
            $seconds = $seconds * 0.25;
        }
        $k = \Config::get('guess.game.levels.' . $question['level'])[1];
        $points = round($k * $seconds, 1);
        $game[self::GAME_POINTS] += $points;
        return ['k' => $k, 'seconds' => $seconds];
    }

    /**
     * @param bool|false $difficulty
     * @param array      $excludeIds
     *
     * @return mixed
     */
    protected function getRandomSeries($difficulty = false, $excludeIds = array())
    {
        return Series::getRandomSeries($difficulty, $excludeIds);
    }

    /**
     * @param       $difficulty
     * @param null  $seriesId
     * @param array $excludeIds
     *
     * @return mixed
     */
    protected function getPicture($difficulty, $seriesId = null, $excludeIds = array())
    {
        return SeriesImage::getPicture($difficulty, $seriesId, $excludeIds);
    }

    /**
     * @param $array
     *
     * @return mixed
     */
    protected function getArrayRandomValue($array)
    {
        return $array[array_rand($array)];
    }

    /**
     * @return \Illuminate\View\View
     */
    public function admin()
    {
        return view('games.guess.admin.index');
    }
}
