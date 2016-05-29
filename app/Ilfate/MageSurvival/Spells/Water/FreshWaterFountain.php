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
namespace Ilfate\MageSurvival\Spells\Water;

use Ilfate\MageSurvival\ChanceHelper;
use Ilfate\MageSurvival\Game;
use Ilfate\MageSurvival\GameBuilder;
use Ilfate\MageSurvival\MessageException;
use Ilfate\MageSurvival\Spells\DamageSpell;
use Ilfate\MageSurvival\Spells\Fire;
use Ilfate\MageSurvival\Spells\Water;
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
class FreshWaterFountain extends Water
{

    protected $defaultCooldownMin = 2;
    protected $defaultCooldownMax = 3;

    protected $availablePatterns = [19];

    public function setUsages()
    {
        $this->config['usages'] = 1;
    }

    protected function spellEffect($data)
    {
        $cell = [$this->pattern[0][0] + $this->mage->getX(), $this->mage->getY() + $this->pattern[0][1]];
        if (!$this->world->isPassable($cell[0], $cell[1])) {
            throw new MessageException('You can`t place a fountain here');
        }
        $object = $this->world->addObject(7,  + $cell[0], $cell[1]);
        if ($object) {
            GameBuilder::animateEvent(Game::EVENT_NAME_ADD_OBJECT,
                ['object' => $object->exportForView()],
                Game::ANIMATION_STAGE_MAGE_ACTION_2);
        } else {
            throw new MessageException('You can`t place a fountain here');
        }

        return true;
    }
}