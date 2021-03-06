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
namespace Ilfate\MageSurvival\Mages;
use Ilfate\MageSurvival\Game;
use Ilfate\MageSurvival\GameBuilder;
use Ilfate\MageSurvival\Generators\WorldGeneratorTutorial;
use Ilfate\MageSurvival\Mage;
use Ilfate\MageSurvival\MessageException;
use Ilfate\MageSurvival\Spell;

/**
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
class Sorcerer extends Mage
{
    const DEFAULT_MAX_HEALTH = 25;

    protected function initMage()
    {
        parent::initMage();
        $this->addItems([1 => 5, 2 => 5, 3 => 5, 4 => 4, 5 => 4, 6 => 3, 7 => 3, 8 => 3, 9 => 2, 10 => 1]);

    }
    
}