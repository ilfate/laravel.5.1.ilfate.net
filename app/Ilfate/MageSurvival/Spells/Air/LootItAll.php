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
namespace Ilfate\MageSurvival\Spells\Air;

use Ilfate\MageSurvival\ChanceHelper;
use Ilfate\MageSurvival\Game;
use Ilfate\MageSurvival\GameBuilder;
use Ilfate\MageSurvival\Spell;
use Ilfate\MageSurvival\Spells\Air;
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
 * @license   Proprietary license.
 * @link      http://ilfate.net
 */
class LootItAll extends Air
{
    protected $defaultCooldownMin = 5;
    protected $defaultCooldownMax = 10;

    protected function spellEffect($data)
    {
        $objects = GameBuilder::getWorldGenerator()->getActiveObjects($this->mage);
        $targets = [];
        foreach ($objects as $object) {
            if (abs($object->getX() - $this->mage->getX()) <= 5 && abs($object->getY() - $this->mage->getY()) <= 5) {
                $actions = $object->getActions();
                foreach ($actions as $action) {
                    if (!empty($action['method']) && $action['method'] === 'open') {
                        $targets[] = [$object->getX() - $this->mage->getX(), $object->getY() - $this->mage->getY()];
                        $object->open($this->mage);
                        break;
                    }
                }
            }
        }
        if ($targets) {
            if (ChanceHelper::chance(30)) {
                $messages = [
                    'Roll need!',
                    'Mind if I roll need?',
                    'Get over here!',
                ];
                $this->mage->say(ChanceHelper::oneFromArray($messages), Game::ANIMATION_STAGE_MAGE_BEFORE_ACTION_SPEECH);
            }
        } else {
            $this->mage->say('There is nothing to loot here...', Game::ANIMATION_STAGE_MAGE_BEFORE_ACTION_SPEECH);
        }
        $this->game->addAnimationEvent(Game::EVENT_NAME_MAGE_SPELL_CAST, [
            'spell' => $this->name,
            'targets' => $targets,
        ], Game::ANIMATION_STAGE_MAGE_ACTION);
        return true;
    }
}