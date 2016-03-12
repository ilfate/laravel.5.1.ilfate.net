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
class Push extends Air
{
    protected $availablePatterns = [4];

    protected function spellEffect($data)
    {

        foreach($this->targets as $target) {
            /**
             * @var Unit $target
             */
            if ($this->d === false) {
                throw new \Exception('Push spells needs to have direction!');
            }
            $x2 = $x1 = $oldX = $target->getX();
            $y2 = $y1 = $oldY = $target->getY();
            switch ($this->d) {
                case 0: $y1 -= 1; $y2 -= 2; break;
                case 1: $x1 += 1; $x2 += 2; break;
                case 2: $y1 += 1; $y2 += 2; break;
                case 3: $x1 -= 1; $x2 -= 2; break;
            }
            $is1Passable = $this->world->isPassable($x1, $y1);
            $is2Passable = $this->world->isPassable($x2, $y2);
            if ($is1Passable && $is2Passable) {
                $target->move($x2, $y2, $this->getNormalCastStage());
            } else if ($is1Passable) {
                $target->move($x1, $y1, $this->getNormalCastStage());
            } else {
                $target->damage(1, $this->getNormalCastStage());
            }
        }
        return true;
    }
}