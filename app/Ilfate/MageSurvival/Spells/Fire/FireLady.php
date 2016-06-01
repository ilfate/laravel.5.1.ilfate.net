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
class FireLady extends Fire
{
    protected $availablePatterns = [];

    protected $defaultCooldownMin = 4;
    protected $defaultCooldownMax = 6;

    protected function spellEffect($data)
    {
        $visibleUnits = $this->game->getWorldGenerator()->getVisibleUnits($this->mage);
        /**
         * @var Unit $target
         */
        $target = ChanceHelper::oneFromArray($visibleUnits);

        $mageX = $mage = $this->mage->getX();
        $mageY = $mage = $this->mage->getY();
        $this->game->addAnimationEvent(Game::EVENT_NAME_MAGE_SPELL_CAST, [
            'spell' => $this->name, 'targetX' => $target->getX() - $mageX, 'targetY' => $target->getY() - $mageY
        ], $this->getNormalCastStage());
        $this->setEffectStage();

        $damage = $this->mage->getDamage(mt_rand(1, 6), Spell::ENERGY_SOURCE_FIRE);
        $target->damage($damage, $this->getNormalCastStage(), Spell::ENERGY_SOURCE_FIRE);

        return true;
    }
}