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
namespace Ilfate\MageSurvival\Spells\Fire;

use Ilfate\MageSurvival\ChanceHelper;
use Ilfate\MageSurvival\Game;
use Ilfate\MageSurvival\Spells\DamageSpell;
use Ilfate\MageSurvival\Spells\Fire;
use Ilfate\MageSurvival\Spells\FireDamageSpell;
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
class ButthurtJump extends Fire
{

    protected $availablePatterns = [8];

    protected $defaultCooldownMin = 0;
    protected $defaultCooldownMax = 1;

    protected function spellEffect($data)
    {
        $x = $this->mage->getX();
        $y = $this->mage->getY();
        $d = $this->mage->getD();
        switch ($d) {
            case 0: $center = function($x, $y) {$y -= 4; return [$x, $y];}; break;
            case 1: $center = function($x, $y) {$x += 4; return [$x, $y];}; break;
            case 2: $center = function($x, $y) {$y += 4; return [$x, $y];}; break;
            case 3: $center = function($x, $y) {$x -= 4; return [$x, $y];}; break;
            default: throw new \Exception('Direction was wrong');
        }
        
        list($centerX, $centerY) = $center($x, $y);
        $possibleLandings = [];
        for ($iy = -1; $iy <= 1; $iy ++) {
            for ($ix = -1; $ix <= 1; $ix ++) {
                $possibleLandings[] = [$centerX + $ix, $centerY + $iy];
            }
        }
        $landing = false;
        while ($possibleLandings) {
            $mayBeLanding = ChanceHelper::extractOne($possibleLandings);
            if ($this->world->isPassable($mayBeLanding[0], $mayBeLanding[1])) {
                $landing = $mayBeLanding;
                break;
            }
        }
        if (!$landing) {
            list($x1, $y1) = $this->world->getNextCoord($x, $y, $d);
            list($x2, $y2) = $this->world->getNextCoord($x1, $y1, $d);
            if ($this->world->isPassable($x2, $y2)) {
                $landing = [$x2, $y2];
            } else if ($this->world->isPassable($x1, $y1)) {
                $landing = [$x1, $y1];
            }
        }
        if ($landing) {
            $this->mage->forceMove($landing[0], $landing[1], $this->getPreviousStage());
            if (ChanceHelper::chance(12)) {
                $this->mage->say('Ouch! Damn that hurts!!!', Game::ANIMATION_STAGE_MAGE_ACTION_EFFECT_2);
            }
        }
        return true;
    }

    public function setUsages()
    {
        $this->config['usages'] = mt_rand(10, 20);
    }
}