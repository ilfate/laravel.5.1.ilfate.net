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
class Earth extends Event
{

    public static function Sand($data)
    {
        $data['no-move'] = true;
        return $data;
    }
    public static function RemoveSand($data) {
        /**
         * @var Unit $unit
         */
        $unit = $data[Event::KEY_OWNER];
        $unit->removeFlag(AliveCommon::FLAG_QUICKSAND);
        GameBuilder::getGame()->addAnimationEvent(Game::EVENT_NAME_UNIT_REMOVE_STATUS, [
            'id' => $unit->getId(), 'flag' => AliveCommon::FLAG_QUICKSAND
        ], Game::ANIMATION_STAGE_UNIT_ACTION_2);
        return $data;

    }

    public static function damageBuff($data) {
        if ($data['value'] > 0 && $data['source'] == Spell::ENERGY_SOURCE_EARTH) {
            $data['value'] += 2;
        }
        return $data;
    }

    public static function Stone($actionData)
    {
        $actionData['no-move']   = true;
        $actionData['skip-turn'] = true;
        return $actionData;
    }
    public static function RemoveStone($actionData) {
        /**
         * @var Unit $unit
         */
        $unit = $actionData[Event::KEY_OWNER];
        $unit->removeFlag(AliveCommon::FLAG_STONED);
        GameBuilder::getGame()->addAnimationEvent(Game::EVENT_NAME_UNIT_REMOVE_STATUS, [
            'id' => $unit->getId(), 'flag' => AliveCommon::FLAG_STONED
        ], Game::ANIMATION_STAGE_UNIT_ACTION_2);
        return $actionData;

    }
}