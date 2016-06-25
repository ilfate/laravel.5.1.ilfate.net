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
namespace Ilfate\MageSurvival\Spells\Earth;

use Ilfate\MageSurvival\Event;
use Ilfate\MageSurvival\Game;
use Ilfate\MageSurvival\Spell;
use Ilfate\MageSurvival\Spells\Earth;
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
class Astonishing extends Earth
{
    protected $defaultCooldownMin = 6;
    protected $defaultCooldownMax = 8;

    protected function spellEffect($data)
    {
        $target = $this->targets[0];
        
            /**
             * @var Unit $target
             */
        $target->stone(3, Game::ANIMATION_STAGE_MAGE_ACTION_EFFECT);
        Event::create(
            Event::EVENT_UNIT_BEFORE_GET_DAMAGE,
            [Event::KEY_TURNS => 3, Event::KEY_OWNER => $target],
            'Earth:damageBuff'
        );
        
        return true;
    }
}