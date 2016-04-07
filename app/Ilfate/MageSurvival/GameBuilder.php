<?php
/**
 * TODO: Package description.
 *
 * PHP version 5
 *
 * @category
 * @package
 * @author    Ilya Rubinchik <ilfate@gmail.com>
 *
 * @license   Proprietary license.
 * @link      http://ilfate.net
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

    /**
     * @var Game
     */
    protected static $game;

    /**
     * @param       $message
     * @param null  $type
     * @param array $data
     */
    public static function message($message, $type = null, $data = [])
    {
        self::$game->addMessage($message, $type, $data);
    }

    /**
     * @param       $name
     * @param array $data
     * @param bool  $animationStage
     */
    public static function animateEvent($name, $data = [], $animationStage)
    {
        self::$game->addAnimationEvent($name, $data, $animationStage);
    }

    /**
     * @param Request $request
     *
     * @return Game
     */
    public static function getGame(Request $request = null)
    {
        if (self::$game) {
            return self::$game;
        }
        self::$game = new Game();
        $savedData = $request->session()->get('mageSurvival.savedGame');
        if (!$savedData) {

        }

        $user = User::getUser();
        self::$game->setUser($user);
        $activeMage = $user->mages()->where('status', Mage::MAGE_STATUS_ACTIVE)->first();
        if ($activeMage) {
            // mage ready for battle

            $mage = self::$game->createMageByMageEntity($activeMage);
            self::$game->setMage($mage);
            $worldsCollection = $activeMage->world()->get();
            if ($worldsCollection->isEmpty()) {
                // lets create world
                self::$game->setStatus(Game::STATUS_HOME);

                // TODO: USE IT AGAIN
                // TODO: USE IT AGAIN
                //self::createWorld(self::$game, $activeMage);

            } else {
                self::$game->setStatus(Game::STATUS_BATTLE);
                $worldEntity = $worldsCollection->first();
                $world = new World($worldEntity);
                self::$game->setWorld($world);
            }
        }

        return self::$game;
    }

    public static function getRelativeCoordinats($x, $y)
    {
        return self::getGame()->getMage()->getRelativeCoordinats($x, $y);
    }

    protected static function createWorld(Game $game, Mage $mage)
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