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

use Ilfate\MageSurvival\Game;
use Ilfate\MageSurvival\GameBuilder;
use Ilfate\MageSurvival\MessageException;
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
class TeslaTrap extends Air
{
    protected $defaultCooldownMin = 10;
    protected $defaultCooldownMax = 15;
    protected $availablePatterns = [20, 21, 22];

    public function setUsages()
    {
        $this->config['usages'] = 2;
    }

    protected function spellEffect($data)
    {
        $rx = $this->pattern[0][0];
        $ry = $this->pattern[0][1];
        $x = $this->mage->getX() + $rx;
        $y = $this->mage->getY() + $ry;
        if (!$this->world->isPassable($x, $y)) {
            throw new MessageException('You can`t place the trap here');
        }
        $object = $this->world->addObject(15, $x, $y);
        if ($object) {
            GameBuilder::animateEvent(Game::EVENT_NAME_ADD_OBJECT,
                ['object' => $object->exportForView()],
                Game::ANIMATION_STAGE_MAGE_ACTION_EFFECT);
        } else {
            throw new MessageException('You can`t place the trap here');
        }
        return true;
    }
}