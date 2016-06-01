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
class FireImp extends Fire
{

    protected $defaultCooldownMin = 15;
    protected $defaultCooldownMax = 30;

    public function setUsages()
    {
        $this->config['usages'] = 2;
    }

    protected function spellEffect($data)
    {
        $cells = [];
        for($y = -1; $y <= 1; $y++) {
            for($x = -1; $x <= 1; $x++) {
                if ($x != 0 && $y != 0) {
                    $cells[] = [$this->mage->getX() + $x, $this->mage->getY() + $y];
                }
            }
        }
        $cellFound = false;
        while ($cells && !$cellFound) {
            $cell = ChanceHelper::extractOne($cells);
            if ($this->world->isPassable($cell[0], $cell[1])) {
                $cellFound = true;
                break;
            }
        }
        if (!$cellFound) {
            throw new MessageException('Seems like there is a problem to find a place for imp :(');
        }
        $unit = $this->world->addUnit(1001, $cell[0], $cell[1]);
        $unit->say('I`m here to serve master!', Game::ANIMATION_STAGE_MAGE_AFTER_ACTION_SPEECH);
        if ($unit) {
            GameBuilder::animateEvent(Game::EVENT_NAME_ADD_UNIT,
                ['unit' => $unit->exportForView(),
                'targetX' => $cell[0] - $this->mage->getX(),
                'targetY' => $cell[1] - $this->mage->getY(),
                ],
                Game::ANIMATION_STAGE_MAGE_ACTION_3);
            $this->game->addAnimationEvent(Game::EVENT_NAME_MAGE_SPELL_CAST, [
                'spell' => $this->name,
                'targetX' => $cell[0] - $this->mage->getX(),
                'targetY' => $cell[1] - $this->mage->getY(),
            ], Game::ANIMATION_STAGE_MAGE_ACTION_2);
        } else {
            throw new MessageException('I have a problem to put Imp in that place...');
        }

        return true;
    }

}