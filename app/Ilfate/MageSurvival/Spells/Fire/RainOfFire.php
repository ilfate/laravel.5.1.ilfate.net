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
class RainOfFire extends Fire
{
    protected $availablePatterns = [10,11,12,13];

    protected $defaultCooldownMin = 3;
    protected $defaultCooldownMax = 5;

    public function setUsages()
    {
        $this->config['usages'] = 6;
    }

    protected function spellEffect($data)
    {
        foreach($this->targets as $target) {
            /**
             * @var Unit $target
             */
            $damage = $this->mage->getDamage(mt_rand(1, 3), Spell::ENERGY_SOURCE_FIRE);
            $target->damage($damage, Game::ANIMATION_STAGE_MAGE_ACTION_EFFECT, Spell::ENERGY_SOURCE_FIRE);
        }

        $this->game->addAnimationEvent(Game::EVENT_NAME_MAGE_SPELL_CAST, [
            'spell' => $this->name,
            'data' => $this->pattern,
        ], Game::ANIMATION_STAGE_MAGE_ACTION_2);

        $this->changeCellsBySpellSource($this->affectedCells, Spell::ENERGY_SOURCE_FIRE);

        return true;
    }
}