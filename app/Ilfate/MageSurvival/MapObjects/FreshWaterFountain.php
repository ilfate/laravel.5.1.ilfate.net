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
namespace Ilfate\MageSurvival\MapObjects;

use Ilfate\Geometry2DCells;
use Ilfate\MageSurvival\Game;
use Ilfate\MageSurvival\GameBuilder;
use Ilfate\MageSurvival\Mage;
use Ilfate\MageSurvival\MapObject;
use Ilfate\MageSurvival\ChanceHelper;

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
class FreshWaterFountain extends MapObject
{

    protected $isPassable = false;

    public function trigger($animationStage)
    {
        $mage = GameBuilder::getGame()->getMage();
        $mage->heal(2, Game::ANIMATION_STAGE_TURN_END_EFFECTS_2);
        
        GameBuilder::animateEvent(Game::EVENT_NAME_OBJECT_ACTIVATE,
            ['action' => 'fountainHeal',
             'targetX' => $this->getX() - $mage->getX(),
             'targetY' => $this->getY() - $mage->getY(),
            ],
            $animationStage);
    }

    public function activate()
    {
        // ok let`s check is bomb should explode

        $mage = GameBuilder::getGame()->getMage();
        $dx = abs($mage->getX() - $this->getX());
        $dy = abs($mage->getY() - $this->getY());
        if ($dx <= 1 && $dy <= 1) {
            $this->trigger(Game::ANIMATION_STAGE_TURN_END_EFFECTS);
        }
        
        return ;
    }
}