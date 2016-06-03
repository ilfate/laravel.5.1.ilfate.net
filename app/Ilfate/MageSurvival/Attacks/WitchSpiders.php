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
class WitchSpiders extends AbstractAttack
{


    public function trigger()
    {
        $target = $this->target;
        //$target->addFlag(AliveCommon::FLAG_WEB, GameBuilder::getGame()->getTurn() + 3);
        
        //$this->standartAnimate();

        $world = GameBuilder::getWorld();

        $possibleCells = [];
        $dx = $target->getX() - $this->unit->getX();
        $dy = $target->getY() - $this->unit->getY();
        $xm = 1;
        $ym = 1;
        if ($dy < 0) {
            $ym = -1;
        }
        if ($dx < 0) {
            $xm = -1;
        }

        for ($iy = 0; $iy < abs($dy) + 1; $iy ++) {
            for ($ix = 0; $ix < abs($dx) + 1; $ix ++) {
                if (($ix != 0 || $iy != 0) && ($iy != abs($dy) + 1 || $ix != abs($dx) + 1)) {
                    $spaunCell = [$this->unit->getX() + ($ix * $xm), $this->unit->getY() + ($iy * $ym)];
                    if ($world->isPassable($spaunCell[0], $spaunCell[1])) {
                        $possibleCells[] = $spaunCell;
                    }
                }
            }
        }
        $addCells = 0;
        if (count($possibleCells) <= 6) { $addCells = 2; }
        if (count($possibleCells) <= 4) { $addCells = 4; }
        if (count($possibleCells) <= 2) { $addCells = 6; }

        if ($addCells) {
            for($i = 0; $i < $addCells; $i++) {
                $aCell = ChanceHelper::oneFromArray($possibleCells);
                for($in = 0; $in < 8; $in++) {
                    $rx = mt_rand(0, 4) - 2;
                    $ry = mt_rand(0, 4) - 2;
                    $nCell = [$aCell[0] + $rx, $aCell[1] + $ry];
                    if (!in_array($nCell, $possibleCells) && $world->isPassable($nCell[0], $nCell[1])) {
                        $possibleCells[] = $nCell;
                        break;
                    }
                }
            }
        }
        
        if (count($possibleCells) < 4) {
            // fuck

            $counter = 0;
            for ($d = 0; $d < 4; $d++) {
                $nx = $target->getX();
                $ny = $target->getY();
                while (!$world->isPassable($nx, $ny) || in_array([$nx, $ny], $possibleCells) || $counter < 30) {
                    switch ($d) {
                        case 0:
                            $ny -= 1;
                            break;
                        case 1:
                            $nx += 1;
                            break;
                        case 2:
                            $ny += 1;
                            break;
                        case 3:
                            $nx -= 1;
                            break;
                    }
                    $counter++;
                }
                $possibleCells[] = [$nx, $ny];
            }
        }

        $cell1 = ChanceHelper::extractOne($possibleCells);
        $cell2 = ChanceHelper::extractOne($possibleCells);
        $cell3 = ChanceHelper::extractOne($possibleCells);
        $cell4 = ChanceHelper::extractOne($possibleCells);
        $world->addUnit(4, $cell1[0], $cell1[1], Game::ANIMATION_STAGE_UNIT_ACTION_3);
        $world->addUnit(4, $cell2[0], $cell2[1], Game::ANIMATION_STAGE_UNIT_ACTION_3);
        $world->addUnit(5, $cell3[0], $cell3[1], Game::ANIMATION_STAGE_UNIT_ACTION_3);
        $world->addUnit(5, $cell4[0], $cell4[1], Game::ANIMATION_STAGE_UNIT_ACTION_3);

        $mage = GameBuilder::getGame()->getMage();
        $mX = $mage->getX();
        $mY = $mage->getY();
        GameBuilder::animateEvent(Game::EVENT_NAME_UNIT_ATTACK, [

            'attack'  => $this->config,
            'targets' => [
                [$cell1[0] - $mX, $cell1[1] - $mY],
                [$cell2[0] - $mX, $cell2[1] - $mY],
                [$cell3[0] - $mX, $cell3[1] - $mY],
                [$cell4[0] - $mX, $cell4[1] - $mY],
            ],
            'fromX'   => $this->unit->getX() - $mX,
            'fromY'   => $this->unit->getY() - $mY
        ], Game::ANIMATION_STAGE_UNIT_ACTION_2);
    }

    
}