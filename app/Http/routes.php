<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

//Route::get('/', function () {
//    return view('welcome');
//});

Route::get('/', 'PageController@index');
Route::get('/login', 'PageController@login');
Route::post('/login', 'PageController@loginAction');
Route::get('/logout', 'PageController@logout');
Route::get('/register', 'PageController@registerForm');
Route::post('/register', 'PageController@registerSubmit');

Route::post('/jsLog', 'PageController@jsLog');
//Route::get('/', function () {
//    view('pages.index');
//});
Route::get('Photo', 'PageController@photo');
Route::get('cv', 'PageController@cv');
Route::get('Cv', 'PageController@cv');
Route::get('Cv/Skills', 'PageController@skills');

Route::get('Code', 'CodeController@index');
Route::get('GameTemplate', 'CodeController@gameTemplate');
Route::get('RobotRock', 'CodeController@robotRock');

Route::get('games', 'GamesController@index');
Route::get('Games', 'GamesController@index');

Route::get('MathEffect', 'MathEffectController@index2');
Route::get('MathEffect-old', 'MathEffectController@index');
Route::post('MathEffect/save', array('before' => 'csrf', 'uses' => 'MathEffectController@save'));
Route::post('MathEffect/saveName', array('before' => 'csrf', 'uses' => 'MathEffectController@saveName'));
Route::get('MathEffect/stats', 'MathEffectController@statistic');

Route::get('MathEffect-2', 'MathEffectTwoController@index');

Route::get('GuessSeries', 'GuessGameController@index');
Route::get('GuessSeries/stats', 'GuessGameController@stats');
Route::post('GuessSeries/gameStarted', 'GuessGameController@gameStarted');
Route::post('GuessSeries/answer', 'GuessGameController@answer');
Route::post('GuessSeries/ability', 'GuessGameController@ability');
Route::post('GuessSeries/timeIsOut', 'GuessGameController@timeIsOut');
Route::post('GuessSeries/saveName', 'GuessGameController@saveName');

Route::get('GuessSeries/admin', 'GuessGameAdminController@index');
Route::any('GuessSeries/admin/addSeries', 'GuessGameAdminController@addSeries');
Route::any('GuessSeries/admin/liveStream', 'GuessGameAdminController@liveStream');
Route::any('GuessSeries/admin/addImage', 'GuessGameAdminController@addImage');
Route::any('GuessSeries/admin/generateImages', 'GuessGameAdminController@generateImages');
Route::any('GuessSeries/admin/series/{id}', 'GuessGameAdminController@seriesInfo');
Route::any('GuessSeries/admin/series/toggle/{id}', 'GuessGameAdminController@toggleActive');
Route::any('GuessSeries/admin/deleteImage/{id}', 'GuessGameAdminController@deleteImage');

Route::get('td', 'TdController@index');
Route::post('td/load/wave', 'TdController@loadWave');
Route::post('td/saveStats', 'TdController@saveStats');
Route::post('td/getStats', 'TdController@getStats');
Route::post('td/saveName', array('before' => 'csrf', 'uses' => 'TdController@saveName'));

Route::get('test', 'TestController@index');

Route::get('Vortex', 'VortexController@index');
Route::post('Vortex/action', 'VortexController@action');

Route::get('clicker', 'ClickerController@index');

Route::get('test', 'TestController@index');

Route::get('hex', 'HexController@index');
Route::get('hex/reset', 'HexController@reset');
Route::post('hex/action', 'HexController@action');

Route::get('shipAi', 'ShipAiController@index');
Route::get('shipAi/galaxy', 'ShipAiController@galaxy');
Route::get('shipAi/hex/{id}', 'ShipAiController@hex');
Route::get('shipAi/star/{id}', 'ShipAiController@star');


Route::get('whiteHorde/demo', 'WhiteHordeController@demo');
Route::get('WhiteHorde/demo', 'WhiteHordeController@demo');
Route::get('WhiteHorde/test', 'WhiteHordeController@test');
Route::get('WhiteHorde/fake', 'WhiteHordeController@fake');
Route::get('WhiteHorde', 'WhiteHordeController@index');
Route::get('whiteHorde', 'WhiteHordeController@index');
Route::post('WhiteHorde/action', 'WhiteHordeController@action');


Route::get('Cosmos', 'CosmosController@index');

Route::get('MageSurvival', 'MageSurvivalController@redirect');
Route::get('spellcraft', 'MageSurvivalController@redirect');
Route::get('Spellcraft', 'MageSurvivalController@index');
Route::post('Spellcraft/createMage', 'MageSurvivalController@createMage');
Route::post('Spellcraft/action', 'MageSurvivalController@action');
Route::get('Spellcraft/world/{name}', 'MageSurvivalController@world');
Route::get('Spellcraft/addSpells', 'MageSurvivalController@addAllSpells');
Route::get('Spellcraft/addItems', 'MageSurvivalController@addAllItems');
Route::get('Spellcraft/thanks', 'MageSurvivalController@thanks');
Route::get('Spellcraft/progress', 'MageSurvivalController@progress');

Route::get('Spellcraft/mapBuilder/{name}', 'MageSurvivalController@mapBuilder');
Route::post('Spellcraft/mapBuilder/save', 'MageSurvivalController@saveMapName');
Route::get('Spellcraft/mapBuilder/show/{name}', 'MageSurvivalController@showMap');
Route::get('Spellcraft/mapBuilder/edit/{name}', 'MageSurvivalController@editMap');

Route::get('Spellcraft/admin', 'MageSurvivalController@admin');
Route::get('Spellcraft/admin/page/{userId}/{pageTime}', 'MageSurvivalController@adminPage');
Route::get('Spellcraft/admin/pageDownload/{userId}/{pageTime}', 'MageSurvivalController@adminPageDownload');
Route::get('Spellcraft/publicLog/{userId}/{pageTime}', 'MageSurvivalController@publicLog');
Route::get('Spellcraft/savedLog', 'MageSurvivalController@savedLog');
Route::post('Spellcraft/admin/getActions/{userId}/{pageTime}', 'MageSurvivalController@adminGetActions');


Route::get('deplotment/resetopcache', 'DeploymentController@resetopcache');


Route::get('tcg/me', 'TcgPlayerController@index');
Route::get('tcg/register', 'TcgPlayerController@registerForm');
Route::post('tcg/register/submit', 'TcgPlayerController@registerSubmit');
Route::get('tcg/login', 'TcgPlayerController@login');
Route::post('tcg/login/submit', 'TcgPlayerController@loginSubmit');
Route::get('tcg/logout', 'TcgPlayerController@logout');

Route::get('tcg/createDeck', 'TcgCardController@createDeckForm');
Route::post('tcg/createDeck/submit', 'TcgCardController@createDeck');
Route::get('tcg/changeDeck', 'TcgCardController@changeDeckForm');
Route::post('tcg/changeDeck/submit', 'TcgCardController@changeDeck');
Route::get('tcg/deck/{deckId}', 'TcgCardController@deck');
Route::post('tcg/saveDeck/{deckId}', 'TcgCardController@deckSaveCards');

Route::get('tcg/findBattle', 'TcgBattleController@findBattlePage');
Route::get('tcg/joinQueue/{deckId}', 'TcgBattleController@joinQueue');
Route::get('tcg/leaveQueue', 'TcgBattleController@leaveQueue');
Route::post('tcg/checkQueue', 'TcgBattleController@checkQueue');

Route::get('tcg/battle', 'TcgBattleController@battle');
Route::post('tcg/battle/action', 'TcgBattleController@battleAction');

Route::get('tcg/addBooster', 'TcgCardController@openBooster');

Route::get('tcg/test', 'TcgController@index');
Route::get('tcg/test/player2', 'TcgController@bot');
Route::get('tcg/test/clear', 'TcgController@dropGame');
Route::get('tcg/test/action', 'TcgController@action');
Route::post('tcg/test/action', 'TcgController@actionAjax');

