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
            $x = $oldX = $target->getX();
            $y = $oldY = $target->getY();
            switch ($this->d) {
                case 0: $y -= 1; break;
                case 1: $x += 1; break;
                case 2: $y += 1; break;
                case 3: $x -= 1; break;
            }
            if ($this->world->isPassable($x, $y)) {
                $target->move($x, $y, $this->getNormalCastStage());
            } else {
                $target->damage(1, $this->getNormalCastStage());
            }
        }
        return true;
    }
}