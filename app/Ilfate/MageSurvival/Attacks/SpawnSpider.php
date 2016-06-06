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
use Ilfate\Geometry2DCells;
use Ilfate\Mage;
use Ilfate\MageSurvival\AliveCommon;
use Ilfate\MageSurvival\ChanceHelper;
use Ilfate\MageSurvival\Event;
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
class SpawnSpider extends AbstractAttack
{


    public function trigger()
    {
        $target = $this->target;
        //$target->addFlag(AliveCommon::FLAG_WEB, GameBuilder::getGame()->getTurn() + 3);
        
        //$this->standartAnimate();

        $world = GameBuilder::getWorld();

        $possibleCells = Geometry2DCells::getNeighbours($this->unit->getX(), $this->unit->getY());

        $unit = false;
        $cell = false;
        for ($i = 0; $i < count($possibleCells); $i++ ) {
            $cell = ChanceHelper::extractOne($possibleCells);
            if ($world->isPassable($cell[0], $cell[1])) {
                $unit = $world->addUnit(5, $cell[0], $cell[1], Game::ANIMATION_STAGE_UNIT_ACTION_3);
                break;
            }
        }
        if ($unit) {
            $unit->addDataValue(Unit::DATA_KEY_NO_LOOT, true);
            $unit->update();
            $mage = GameBuilder::getGame()->getMage();
            $mX   = $mage->getX();
            $mY   = $mage->getY();
            GameBuilder::animateEvent(Game::EVENT_NAME_UNIT_ATTACK, [

                'attack'  => $this->config,
                'targets' => [
                    [$cell[0] - $mX, $cell[1] - $mY],
                ],
                'fromX'   => $this->unit->getX() - $mX,
                'fromY'   => $this->unit->getY() - $mY
            ], Game::ANIMATION_STAGE_UNIT_ACTION_2);
        }
    }

    
}