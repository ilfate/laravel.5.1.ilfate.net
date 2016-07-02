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
class BurnCitiesToTheGround extends Fire
{
    protected $availablePatterns = [];

    protected $defaultCooldownMin = 8;
    protected $defaultCooldownMax = 12;


    public function setUsages()
    {
        $this->config['usages'] = 2;
    }

    protected function spellEffect($data)
    {

        $x = $mageX = $this->mage->getX();
        $y = $mageY = $this->mage->getY();
        $units = $this->world->getUnitsAround($x, $y, 5);

        $damage = $this->mage->getDamage(1, Spell::ENERGY_SOURCE_FIRE);
        $animationTargets = [];

        foreach ($units as $unit) {
            $unit->damage($damage, Game::ANIMATION_STAGE_MAGE_ACTION_EFFECT, Spell::ENERGY_SOURCE_FIRE);
            $unit->burn(3, Game::ANIMATION_STAGE_MAGE_ACTION_EFFECT);
            $animationTargets[] = [$unit->getX() - $x, $unit->getY() - $y];
        }
        $this->game->addAnimationEvent(Game::EVENT_NAME_MAGE_SPELL_CAST, [
            'spell' => $this->name,
            'targets' => $animationTargets
        ], Game::ANIMATION_STAGE_MAGE_ACTION_2);
        return true;
    }
}