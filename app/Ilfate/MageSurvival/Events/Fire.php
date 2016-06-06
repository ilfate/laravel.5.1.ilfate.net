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

use Ilfate\MageSurvival\Event;
use Ilfate\MageSurvival\Game;
use Ilfate\MageSurvival\GameBuilder;
use Ilfate\MageSurvival\Spell;
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
class Fire extends Event
{

    public static function Burn($data) {
        /**
         * @var Unit $unit
         */
        $unit = $data[Event::KEY_OWNER];
        if (!$unit->isAlive()) {
            return $data;
        }
        $unit->damage(1, Game::ANIMATION_STAGE_UNIT_ACTION_3, Spell::ENERGY_SOURCE_FIRE);
        return $data;
    }
    public static function RemoveBurn($data) {
        /**
         * @var Unit $unit
         */
        $unit = $data[Event::KEY_OWNER];
        $unit->removeFlag('burn');
        GameBuilder::getGame()->addAnimationEvent(Game::EVENT_NAME_UNIT_REMOVE_STATUS, [
            'id' => $unit->getId(), 'flag' => 'burn'
        ], Game::ANIMATION_STAGE_UNIT_ACTION_3);
        return $data;
    }
}