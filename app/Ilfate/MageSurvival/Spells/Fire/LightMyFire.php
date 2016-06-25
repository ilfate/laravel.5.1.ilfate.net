<?php namespace Ilfate\MageSurvival\Spells\Fire;

use Ilfate\MageSurvival\ChanceHelper;
use Ilfate\MageSurvival\Event;
use Ilfate\MageSurvival\Game;
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
class LightMyFire extends Fire
{

    protected $defaultCooldownMin = 2;
    protected $defaultCooldownMax = 4;

    protected function spellEffect($data)
    {
        foreach($this->targets as $target) {
            /**
             * @var Unit $target
             */
            $target->burn(4, $this->getNormalCastStage());
            
        }
        if (ChanceHelper::chance(5)) {
            $this->mage->say('Come on baby, light my fire 
Try to set the night on fire ', Game::ANIMATION_STAGE_MAGE_BEFORE_ACTION_SPEECH);
        }

        return true;
    }
}