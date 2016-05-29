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

use Ilfate\MageSurvival\AliveCommon;
use Ilfate\MageSurvival\Spell;
use Ilfate\MageSurvival\Spells\DamageSpell;
use Ilfate\MageSurvival\Spells\Fire;
use Ilfate\MageSurvival\Spells\FireDamageSpell;

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
class FireNova extends Fire
{
    protected $availablePatterns = [1,2,3];

    protected $defaultCooldownMin = 1;
    protected $defaultCooldownMax = 1;

    protected function spellEffect($data)
    {

        foreach($this->targets as $target) {
            /**
             * @var AliveCommon $target
             */
            $target->damage($this->getDamage(), $this->getNormalCastStage(), Spell::ENERGY_SOURCE_FIRE);
        }
        $this->changeCellsBySpellSource($this->affectedCells, Spell::ENERGY_SOURCE_FIRE);
        return true;
    }
}