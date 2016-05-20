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
namespace Ilfate\MageSurvival\Attacks;
use Ilfate\Mage;
use Ilfate\MageSurvival\AliveCommon;
use Ilfate\MageSurvival\Game;
use Ilfate\MageSurvival\GameBuilder;
use Ilfate\MageSurvival\Unit;
use Ilfate\User;

/**
 * PHP version 5
 *
 * @category
 * @package
 * @author    Ilya Rubinchik <ilfate@gmail.com>
 * @license   Proprietary license.
 * @link      http://ilfate.net
 */
abstract class AbstractAttack
{
    /**
     * @var Unit
     */
    protected $unit;
    /**
     * @var AliveCommon
     */
    protected $target;
    protected $config;

    public function __construct(Unit $unit, AliveCommon $target, $config)
    {
        $this->unit = $unit;
        $this->target = $target;
        $this->config = $config;
    }

    protected function standartAnimate()
    {
        $mage = GameBuilder::getGame()->getMage();
        $mX = $mage->getX();
        $mY = $mage->getY();
        GameBuilder::animateEvent(Game::EVENT_NAME_UNIT_ATTACK, [

            'attack'  => $this->config,
            'targetX' => $this->target->getX() - $mX,
            'targetY' => $this->target->getY() - $mY,
            'fromX'   => $this->unit->getX() - $mX,
            'fromY'   => $this->unit->getY() - $mY
        ], Game::ANIMATION_STAGE_UNIT_ACTION_2);
    }

    abstract public function trigger();
}