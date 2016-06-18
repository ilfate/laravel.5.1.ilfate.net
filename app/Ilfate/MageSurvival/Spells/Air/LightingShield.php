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
namespace Ilfate\MageSurvival\Spells\Air;

use Ilfate\MageSurvival\Event;
use Ilfate\MageSurvival\Game;
use Ilfate\MageSurvival\GameBuilder;
use Ilfate\MageSurvival\Spell;
use Ilfate\MageSurvival\Spells\Air;
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
 * @license   Proprietary license.
 * @link      http://ilfate.net
 */
class LightingShield extends Air
{
    protected $defaultCooldownMin = 8;
    protected $defaultCooldownMax = 10;

    public function setUsages()
    {
        $this->config['usages'] = 8;
    }

    protected function spellEffect($data)
    {
        Event::create(
            Event::EVENT_MAGE_AFTER_TURN, [
            Event::KEY_TIMES => 5,
            'range' => 1
        ],
            'Air:zip');
        Event::create(
            Event::EVENT_MAGE_BEFORE_GET_DAMAGE, [
            Event::KEY_TURNS => 5,
            'source' => Spell::ENERGY_SOURCE_AIR
        ],
            'General:protection');
        return true;
    }
}