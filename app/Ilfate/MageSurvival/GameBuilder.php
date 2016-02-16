<?php
/**
 * TODO: Package description.
 *
 * PHP version 5
 *
 * @category
 * @package
 * @author    Ilya Rubinchik <ilfate@gmail.com>
 * @copyright 2016 Watchmaster GmbH
 * @license   Proprietary license.
 * @link      http://www.watchmaster.de
 */
namespace Ilfate\MageSurvival;

use Ilfate\Mage as MageModel;
use Ilfate\Mage;
use Ilfate\MageWorld;
use Ilfate\User;
use Illuminate\Http\Request;

/**
 * PHP version 5
 *
 * @category
 * @package
 * @author    Ilya Rubinchik <ilfate@gmail.com>
 * @license   Proprietary license.
 * @link      http://ilfate.net
 */
class GameBuilder
{

    protected $game;


    public function build($config)
    {

    }

    /**
     * @param Request $request
     *
     * @return Game
     */
    public function getGame(Request $request)
    {
        $game = new Game();

        $savedData = $request->session()->get('mageSurvival.savedGame');
        if (!$savedData) {

        }

        $user = User::getUser();
        $game->setUser($user);
        $activeMage = $user->mages()->where('status', Mage::MAGE_STATUS_ACTIVE)->first();
        if ($activeMage) {
            // mage ready for battle
            $game->setStatus(Game::STATUS_BATTLE);
            $mage = $game->createMageByMageEntity($activeMage);
            $game->setMage($mage);
            $worldsCollection = $activeMage->world()->get();
            if ($worldsCollection->isEmpty()) {
                // lets create world
                $this->createWorld($game, $activeMage);
            } else {
                $worldEntity = $worldsCollection->first();
                $world = new World($worldEntity);
                $game->setWorld($world);
            }
        }

        return $game;
    }

    protected function createWorld(Game $game, Mage $mage)
    {
        $mageWorld = new MageWorld();
        $mageWorld->player_id = $game->getUser()->id;
        $mageWorld->type = 1;
        $mageWorld->save();
        $mage->world_id = $mageWorld->id;
        $world = new World($mageWorld);

        $mageEntity = $game->getMage();
        $mageEntity->world_id = $mageWorld->id;
        $game->setWorld($world);

        $game->initWorld();
    }
}