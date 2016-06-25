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
namespace Ilfate\MageSurvival\Spells\Earth;

use Ilfate\Geometry2DCells;
use Ilfate\MageSurvival\Game;
use Ilfate\MageSurvival\Spell;
use Ilfate\MageSurvival\Spells\Earth;
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
class StalactitesFall extends Earth
{
    protected $defaultCooldownMin = 3;
    protected $defaultCooldownMax = 5;
    
    protected $availablePatterns = [];
    
    protected $amountOfStalactites = 3;

    public function setUsages()
    {
        $this->config['usages'] = 7;
    }

    protected function spellEffect($data)
    {
        $mx = $this->mage->getX();
        $my = $this->mage->getY();
        
        $stalactitesLocations = [];
        $location = [];
        $directHitDamage = $this->mage->getDamage(5, Spell::ENERGY_SOURCE_EARTH);
        $damage = $this->mage->getDamage(1, Spell::ENERGY_SOURCE_EARTH);
        for ($i = 0; $i < $this->amountOfStalactites; $i++) {
            
            while (!$location || in_array($location, $stalactitesLocations)) {
                $location = [mt_rand(-5, 5), mt_rand(-5, 5)];
            }
            $stalactitesLocations[] = $location;
            $units = $this->world->getUnitsAround($location[0] + $mx, $location[1] + $my, 1);
            foreach ($units as $unit) {
                if ($unit->getX() == $location[0] + $mx && $unit->getY() == $location[1] + $my) {
                    $dmg = $directHitDamage;
                } else {
                    $dmg = $damage;
                }
                $unit->damage($dmg, Game::ANIMATION_STAGE_MAGE_ACTION_EFFECT, Spell::ENERGY_SOURCE_EARTH);
            }
            if ($location[0] == 0 && $location[1] == 0) {
                $this->mage->damage($directHitDamage, Game::ANIMATION_STAGE_MAGE_ACTION_EFFECT, Spell::ENERGY_SOURCE_EARTH);
            } else if (Geometry2DCells::isNeighbours(0, 0, $location[0], $location[1])) {
                $this->mage->damage($damage, Game::ANIMATION_STAGE_MAGE_ACTION_EFFECT, Spell::ENERGY_SOURCE_EARTH);
            }
        }

        $this->game->addAnimationEvent(Game::EVENT_NAME_MAGE_SPELL_CAST, [
            'spell' => $this->name,
            'targets' => $stalactitesLocations,
        ], Game::ANIMATION_STAGE_MAGE_ACTION_2);

        return true;
    }
}