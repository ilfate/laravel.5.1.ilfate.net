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
use Ilfate\MageSurvival\GameBuilder;
use Ilfate\MageSurvival\MessageException;
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
class Bomb extends Fire
{

    protected $defaultCooldownMin = 2;
    protected $defaultCooldownMax = 3;

    protected function spellEffect($data)
    {
        $object = $this->world->addObject(4, $this->mage->getX(), $this->mage->getY());
        if ($object) {
            GameBuilder::animateEvent(Game::EVENT_NAME_ADD_OBJECT,
                ['object' => $object->exportForView()],
                Game::ANIMATION_STAGE_MAGE_ACTION_2);
        } else {
            throw new MessageException('You can`t plant the bomb here');
        }

        return true;
    }
}