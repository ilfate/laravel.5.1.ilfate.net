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
namespace Ilfate\MageSurvival\Attacks;
use Ilfate\Mage;
use Ilfate\MageSurvival\AliveCommon;
use Ilfate\MageSurvival\Event;
use Ilfate\MageSurvival\Game;
use Ilfate\MageSurvival\GameBuilder;
use Ilfate\MageSurvival\Unit;
use Ilfate\User;

/**
 * PHP version 5
 *
 * @category
 * @package
 * @author    Ilya Rubinchik <ilfate@gmail.com>
 * @license   Proprietary license.
 * @link      http://ilfate.net
 */
class Web extends AbstractAttack
{


    public function trigger()
    {
        $target = $this->target;
        $turn = GameBuilder::getGame()->getTurn() + 3;
        $target->addFlag(AliveCommon::FLAG_WEB, $turn);
        
        $this->standartAnimate();
        GameBuilder::animateEvent(Game::EVENT_NAME_MAGE_ADD_STATUS,
            ['flags' => [AliveCommon::FLAG_WEB => $turn]],
            Game::ANIMATION_STAGE_UNIT_ACTION_3);
        Event::create(
            Event::EVENT_MAGE_AFTER_TURN, [
            Event::KEY_TIMES => 3,
            Event::KEY_ON_COMPLETE => 'Attacks:webRemove'
        ]);
    }

    
}