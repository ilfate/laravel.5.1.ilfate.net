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
use Ilfate\MageSurvival\Spell;
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
class FaceCanon extends Fire
{
    protected $availablePatterns = [5,6,7];

    protected $defaultCooldownMin = 3;
    protected $defaultCooldownMax = 6;

    protected function spellEffect($data)
    {
        foreach($this->targets as $target) {
            $damage = $this->mage->getDamage(2, Spell::ENERGY_SOURCE_FIRE);
            /**
             * @var Unit $target
             */
            $target->damage($damage, Game::ANIMATION_STAGE_MAGE_ACTION_EFFECT, Spell::ENERGY_SOURCE_FIRE);
        }
        //$this->setNexStage();
        $x = $this->mage->getX();
        $y = $this->mage->getY();
        $d = $this->mage->getD();
        switch ($d) {
            case 0: $move = function($x, $y) {$y += 1; return [$x, $y];}; break;
            case 1: $move = function($x, $y) {$x -= 1; return [$x, $y];}; break;
            case 2: $move = function($x, $y) {$y -= 1; return [$x, $y];}; break;
            case 3: $move = function($x, $y) {$x += 1; return [$x, $y];}; break;
            default: throw new \Exception('Direction was wrong');
        }
        for ($i = 0; $i < 3; $i++) {
            list($nx, $ny) = $move($x, $y);
            if (!$this->world->isPassable($nx, $ny)) {
                break;
            }
            $x = $nx; $y = $ny;
        }
        $this->changeCellsBySpellSource($this->affectedCells, Spell::ENERGY_SOURCE_FIRE);
        $this->mage->forceMove($x, $y, Game::ANIMATION_STAGE_MAGE_ACTION_3);
        if (ChanceHelper::chance(3)) {
            $this->mage->say('Surprise Motherfucker!!', Game::ANIMATION_STAGE_MAGE_BEFORE_ACTION_SPEECH);
        }
        return true;
    }
}