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
        $target->addFlag('web', GameBuilder::getGame()->getTurn() + 3);
        
        $this->standartAnimate();
    }

    
}