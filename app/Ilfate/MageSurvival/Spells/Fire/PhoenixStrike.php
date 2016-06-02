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
class PhoenixStrike extends Fire
{
    protected $availablePatterns = [8, 9];

    protected $defaultCooldownMin = 3;
    protected $defaultCooldownMax = 7;


    public function setUsages()
    {
        $this->config['usages'] = 2;
    }

    protected function spellEffect($data)
    {
        list($phoenixTargetX,$phoenixTargetY) = $this->affectedCells[0];

        $x = $mageX = $this->mage->getX();
        $y = $mageY = $this->mage->getY();
        $d = $this->mage->getD();
        switch ($d) {
            case 0: $move = function($x, $y) {$y -= 1; return [$x, $y];}; break;
            case 1: $move = function($x, $y) {$x += 1; return [$x, $y];}; break;
            case 2: $move = function($x, $y) {$y += 1; return [$x, $y];}; break;
            case 3: $move = function($x, $y) {$x -= 1; return [$x, $y];}; break;
            default: throw new \Exception('Direction was wrong');
        }
        $step = 0;
        $targetsForAnimation = [];
        $damage = $this->mage->getDamage(1, Spell::ENERGY_SOURCE_FIRE);
        while ($x != $phoenixTargetX || $y != $phoenixTargetY) {
            list($x, $y) = $move($x, $y);

            $targetsForAnimation[$step]['point'] = [$x - $mageX, $y - $mageY];
            $units = $this->world->getUnitsAround($x, $y, 2, [Unit::TEAM_TYPE_HOSTILE, Unit::TEAM_TYPE_NEUTRAL]);

            for($i = 0; $i < 2; $i++) {
                if ($units) {
                    $randomKey = array_rand($units);
                    $units[$randomKey]->damage($damage, Game::ANIMATION_STAGE_MAGE_ACTION_2, Spell::ENERGY_SOURCE_FIRE);
                    $targetsForAnimation[$step]['targets'][] = [$units[$randomKey]->getX() - $mageX, $units[$randomKey]->getY() - $mageY];
                    unset($units[$randomKey]);
                }
            }
            $step ++;
        }
        $this->game->addAnimationEvent(Game::EVENT_NAME_MAGE_SPELL_CAST, [
            'spell' => $this->name, 'targetX' => $phoenixTargetX - $mageX, 'targetY' => $phoenixTargetY - $mageY,
            'data' => $targetsForAnimation, 'd' => $d
        ], Game::ANIMATION_STAGE_MAGE_ACTION);
//        $this->mage->forceMove($x, $y, $this->getNormalCastStage());
        return true;
    }
}