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
namespace Ilfate\MageSurvival\Events;

use Ilfate\MageSurvival\AliveCommon;
use Ilfate\MageSurvival\Event;
use Ilfate\MageSurvival\Game;
use Ilfate\MageSurvival\GameBuilder;
use Ilfate\MageSurvival\Unit;

/**
 * TODO: Short description.
 * TODO: Long description here.
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
class Attacks extends Event
{

    public static function webRemove($data) {
        /**
         * @var Unit $unit
         */
        $mage = GameBuilder::getGame()->getMage();
        $turn = GameBuilder::getGame()->getTurn();
        $flagValue = $mage->getFlag(AliveCommon::FLAG_WEB);
        if ($flagValue > $turn + 1) {
            return $data;
        }
        $mage->removeFlag(AliveCommon::FLAG_WEB);
        GameBuilder::getGame()->addAnimationEvent(Game::EVENT_NAME_MAGE_REMOVE_STATUS, [
            'flag' => AliveCommon::FLAG_WEB
        ], Game::ANIMATION_STAGE_TURN_END_EFFECTS);
        return $data;

    }
}